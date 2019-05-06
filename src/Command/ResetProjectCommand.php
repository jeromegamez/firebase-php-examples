<?php

namespace App\Command;

use Kreait\Firebase;
use Kreait\Firebase\Database\RuleSet;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ResetProjectCommand extends Command
{
    protected static $defaultName = 'app:reset-project';

    /** @var Firebase */
    private $firebase;

    public function __construct(Firebase $firebase)
    {
        $this->firebase = $firebase;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Reset parts of a Firebase project to its initial state')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        if ($io->confirm('Reset database rules?', false)) {
            $this->firebase->getDatabase()->updateRules(RuleSet::default());
            $io->success('Done!');
        }

        if ($io->confirm('Empty realtime database?', false)) {
            $this->firebase->getDatabase()->getReference('/')->remove();
            $io->success('Done!');
        }

        if ($io->confirm('Empty cloud storage?', false)) {
            foreach ($this->firebase->getStorage()->getBucket()->objects() as $object) {
                $object->delete();
            }
            $io->success('Done!');
        }

        if ($io->confirm('Delete all users?', false)) {
            $auth = $this->firebase->getAuth();

            foreach ($auth->listUsers() as $user) {
                $auth->deleteUser($user->uid);
            }
            $io->success('Done!');
        }
    }
}
