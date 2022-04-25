<?php

namespace App\Command;

use Kreait\Firebase\Contract\Auth;
use Kreait\Firebase\Contract\Database;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class GetDatabaseSnapshot extends Command
{
    protected static $defaultName = 'app:get-database-snapshot';

    private Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Returns a full snapshot of the Realtime Database');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->writeln(json_encode($this->database->getReference()->getValue(), JSON_PRETTY_PRINT));

        return 0;
    }
}
