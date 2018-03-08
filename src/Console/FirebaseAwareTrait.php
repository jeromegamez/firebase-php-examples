<?php

declare(strict_types=1);

namespace Gamez\Kreait\Firebase\Samples\Console;

use Kreait\Firebase;

trait FirebaseAwareTrait
{
    /**
     * @var Firebase
     */
    protected $firebase;

    public function setFirebase(Firebase $firebase): void
    {
        $this->firebase = $firebase;
    }
}
