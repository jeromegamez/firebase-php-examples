<?php

declare(strict_types=1);

namespace Gamez\Kreait\Firebase\Samples\Console\Command;

use Gamez\Kreait\Firebase\Samples\Console\FirebaseAwareCommand;
use Gamez\Kreait\Firebase\Samples\Console\FirebaseAwareTrait;
use Kreait\Firebase\Request\CreateUser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class PopulateWithFakeUsers extends Command implements FirebaseAwareCommand
{
    use FirebaseAwareTrait;

    protected function configure()
    {
        $this
            ->setName('auth:populate')
            ->setDescription('Creates fake users in your user base')
            ->addOption('amount', null, InputOption::VALUE_OPTIONAL, 'The amount of users to create.', '1');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $amountOption = $input->getOption('amount');

        if (!ctype_digit($amountOption) || (int) $amountOption < 1) {
            throw new InvalidArgumentException('The amount must be a positive integer.');
        }

        $amount = (int) $amountOption;

        $faker = \Faker\Factory::create();
        $auth = $this->firebase->getAuth();

        for ($i = 0; $i < $amount; $i++) {
            $userRecord = $auth->createUser(CreateUser::new()
                ->withDisplayName($faker->name)
                ->withEmail($faker->safeEmail)
                ->withPhotoUrl($faker->imageUrl())
            );

            $io->success('User created: '.json_encode($userRecord, JSON_PRETTY_PRINT));
        }

        $io->newLine();
        $io->success('Congratulations, you are a faker.');

        return 0;
    }
}
