<?php

namespace App\Command;

use Kreait\Firebase\Contract\Auth;
use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Contract\Storage;
use Kreait\Firebase\Database\RuleSet;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ResetProjectCommand extends Command
{
    protected static $defaultName = 'app:reset-project';

    private Auth $auth;
    private Database $database;
    private Storage $storage;

    public function __construct(Auth $auth, Database $database, Storage $storage)
    {
        parent::__construct();

        $this->auth = $auth;
        $this->database = $database;
        $this->storage = $storage;
    }

    protected function configure()
    {
        $this->setDescription('Reset parts of a Firebase project to its initial state');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        if ($io->confirm('Reset realtime database rules?', false)) {
            $this->database->updateRules(RuleSet::default());
            $io->success('Realtime database rules have been reset.');
        }

        if ($io->confirm('Empty realtime database?', false)) {
            $this->database->getReference('/')->remove();
            $io->success('Realtime database has been emptied');
        }

        if ($io->confirm('Empty cloud storage?', false)) {
            foreach ($this->storage->getBucket()->objects() as $object) {
                $object->delete();
            }
            $io->success('Cloud Storage has been emptied!');
        }

        if ($io->confirm('Delete all users?', false)) {
            $userCount = 0;

            foreach ($this->auth->listUsers() as $user) {
                $this->auth->deleteUser($user->uid);
                $io->writeln('Deleted user with ID '.$user->uid);
                ++$userCount;
            }

            $io->success("{$userCount} users have been deleted");
        }

        return 0;
    }
}
