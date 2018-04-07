<?php

namespace App\Command;

use Google\Cloud\Storage\StorageObject;
use Kreait\Firebase\Database\RuleSet;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ResetProjectCommand extends ContainerAwareCommand
{
    protected static $defaultName = 'app:reset-project';

    protected function configure()
    {
        $this
            ->setDescription('Reset parts of a Firebase project to its initial state')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $firebase = $this->getContainer()->get('kreait_firebase.default');

        $io = new SymfonyStyle($input, $output);

        if ($io->confirm('Reset database rules?', false)) {
            $firebase->getDatabase()->updateRules(RuleSet::default());
            $io->success('Done!');
        }

        if ($io->confirm('Empty realtime database?', false)) {
            $firebase->getDatabase()->getReference('/')->remove();
            $io->success('Done!');
        }

        if ($io->confirm('Empty cloud storage?', false)) {
            array_map(function (StorageObject $object) {
                $object->delete();
            }, iterator_to_array($firebase->getStorage()->getBucket()->objects()));
            $io->success('Done!');
        }

        if ($io->confirm('Delete all users?', false)) {
            $auth = $firebase->getAuth();

            foreach ($auth->listUsers() as $user) {
                $auth->deleteUser($user->uid);
            }
            $io->success('Done!');
        }
    }
}
