<?php

namespace App\Command;

use Kreait\Firebase\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\MessageTarget;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class FcmSendMessageCommand extends Command
{
    protected static $defaultName = 'app:fcm:send-message';

    /** @var Messaging */
    private $messaging;

    public function __construct(Messaging $messaging)
    {
        $this->messaging = $messaging;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Send an FCM message')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $target = $io->choice('Please select a target', ['Topic', 'Condition', 'Registration Token']);
        $validator = static function ($answer) use ($target) {
            if (empty($answer)) {
                throw new InvalidArgumentException('The '.$target.' must not be empty');
            }

            return $answer;
        };

        switch ($target) {
            case 'Topic':
                $topic = $io->ask('Please enter the name of the topic', null, $validator);
                $message = CloudMessage::withTarget(MessageTarget::TOPIC, $topic);
                break;
            case 'Condition':
                $condition = $io->ask('Please enter the condition', null, $validator);
                $message = CloudMessage::withTarget(MessageTarget::CONDITION, $condition);
                break;
            case 'Registration Token':
                $registrationToken = $io->ask('Please enter the registration token', null, $validator);
                $message = CloudMessage::withTarget(MessageTarget::TOKEN, $registrationToken);
                break;
            default:
                throw new InvalidArgumentException("Invalid message target {$target}");
        }

        $message = $message->withNotification([
            'title' => $io->ask('Please enter the title of your message'),
            'body' => $io->ask('Please enter the body of your message'),
        ]);

        $responseData = $this->messaging->send($message);

        $io->success('The message has been sent and the API returned the following:');
        $io->writeln(json_encode($responseData, JSON_PRETTY_PRINT));

        return 0;
    }
}
