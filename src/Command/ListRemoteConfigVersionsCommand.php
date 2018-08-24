<?php

namespace App\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ListRemoteConfigVersionsCommand extends ContainerAwareCommand
{
    protected static $defaultName = 'app:remote-config:list-versions';

    protected function configure()
    {
        $this
            ->setDescription('Lists all remote config versions')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $firebase = $this->getContainer()->get('kreait_firebase.default');

        $headers = ['#', 'Updated at', 'Updated by', 'Type', 'Origin'];
        $rows = [];

        foreach ($firebase->getRemoteConfig()->listVersions() as $version) {
            $rows[] = [
                $version->versionNumber(),
                $version->updatedAt()->format('Y-m-d H:i:s'),
                $version->user()->name() ?? 'Service Account',
                $version->updateType(),
                $version->updateOrigin(),
            ];
        }

        $io->table($headers, $rows);
    }
}
