<?php

namespace App\Command;

use Kreait\Firebase\Exception\AuthException;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateUserCommand extends ContainerAwareCommand
{
    protected static $defaultName = 'app:create-user';

    protected function configure()
    {
        $this
            ->setDescription('Creates a Firebase user')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $io->note('All properties are optional, you can omit them by pressing <ENTER>.');

        $properties = [
            'displayName' => $io->ask('Display name'),
            'photo' => $io->ask('Photo URL'),
            'email' => $email = $io->ask('Email'),
            'emailverified' => $email && $io->confirm('Is the email address verified?', false),
            'phoneNumber' => $io->ask('Phone Number'),
        ];

        $properties = array_filter($properties, function ($value) {
            return null !== $value;
        });

        $firebase = $this->getContainer()->get('kreait_firebase.default');

        try {
            $user = $firebase->getAuth()->createUser($properties);
        } catch (AuthException $e) {
            $io->error($e->getMessage());

            return 1;
        }

        $io->success('The following user has been stored:');
        $io->writeln(json_encode($user, JSON_PRETTY_PRINT));

        return 0;
    }
}
