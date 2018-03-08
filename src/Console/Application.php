<?php

declare(strict_types=1);

namespace Gamez\Kreait\Firebase\Samples\Console;

use Gamez\Kreait\Firebase\Samples\Console\Command\PopulateWithFakeUsers;
use Gamez\Kreait\Firebase\Samples\Console\FirebaseAwareCommand;
use Gamez\Kreait\Firebase\Samples\Console\Command\ResetProject;
use Kreait\Firebase;

class Application extends \Symfony\Component\Console\Application
{
    protected $credentials;

    public function __construct()
    {
        parent::__construct('Kreait Firebase SDK Samples');
    }

    protected function getDefaultCommands()
    {
        return array_merge(parent::getDefaultCommands(), $this->getFirebaseSampleCommands());
    }

    protected function getFirebaseSampleCommands(): array
    {
        $firebase = (new Firebase\Factory())
            ->withServiceAccount(Firebase\ServiceAccount::fromJsonFile(getenv('FIREBASE_SAMPLE_CREDENTIALS')))
            ->create();

        return array_map(function (FirebaseAwareCommand $command) use ($firebase) {
            $command->setFirebase($firebase);

            return $command;
        },[
            new ResetProject(),
            new PopulateWithFakeUsers()
        ]);
    }
}
