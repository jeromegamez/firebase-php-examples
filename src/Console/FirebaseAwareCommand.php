<?php

declare(strict_types=1);

namespace Gamez\Kreait\Firebase\Samples\Console;

use Kreait\Firebase;

interface FirebaseAwareCommand
{
    public function setFirebase(Firebase $firebase): void;
}
