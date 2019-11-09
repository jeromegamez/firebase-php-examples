<?php

namespace App\Command;

use Kreait\Firebase\Auth;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateUserCommand extends Command
{
    protected static $defaultName = 'app:create-user';

    /** @var Auth */
    private $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;

        parent::__construct();
    }

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

        $properties = array_filter($properties, static function ($value) {
            return null !== $value;
        });

        $user = $this->auth->createUser($properties);

        $io->success('The following user has been stored:');
        $io->writeln(json_encode($user, JSON_PRETTY_PRINT));

        return 0;
    }
}
