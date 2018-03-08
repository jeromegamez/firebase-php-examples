<?php

declare(strict_types=1);

namespace Gamez\Kreait\Firebase\Samples\Console\Command;

use Gamez\Kreait\Firebase\Samples\Console\FirebaseAwareCommand;
use Gamez\Kreait\Firebase\Samples\Console\FirebaseAwareTrait;
use Kreait\Firebase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ResetProject extends Command implements FirebaseAwareCommand
{
    use FirebaseAwareTrait;

    protected function configure()
    {
        $this
            ->setName('reset-project')
            ->setDescription('Resets the Firebase project to its initial state.');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->caution('This will delete everything in your Firebase project!');

        if (!$io->confirm('Proceed?', false)) {
            return 0;
        }

        // ---------------------------------------------------------------

        $output->write('Resetting Realtime Database Rules... ');

        $this->firebase->getDatabase()
            ->updateRules(Firebase\Database\RuleSet::default())
        ;

        $output->writeln('<info>DONE</info>');

        // ---------------------------------------------------------------

        $output->write('Emptying Realtime Database... ');

        $this->firebase->getDatabase()
            ->getReference('/')
            ->remove();

        $output->writeln('<info>DONE</info>');

        // ---------------------------------------------------------------

        $output->write('Emptying Cloud Storage... ');

        foreach ($this->firebase->getStorage()->getBucket()->objects() as $object) {
            /** @var \Google\Cloud\Storage\StorageObject $object*/
            $object->delete();
        }

        $output->writeln('<info>DONE</info>');

        // ---------------------------------------------------------------

        $output->write('Deleting all users... ');

        $auth = $this->firebase->getAuth();
        foreach ($auth->listUsers() as $user) {
            $auth->deleteUser($user->uid);
        }

        $output->writeln('<info>DONE</info>');

        // ---------------------------------------------------------------

        $io->newLine();
        $io->success('Congratulations, you have nothing left.');

        return 0;
    }
}
