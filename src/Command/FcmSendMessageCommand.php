<?php

namespace App\Command;

use Kreait\Firebase\Messaging\ConditionalMessage;
use Kreait\Firebase\Messaging\MessageToRegistrationToken;
use Kreait\Firebase\Messaging\MessageToTopic;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class FcmSendMessageCommand extends ContainerAwareCommand
{
    protected static $defaultName = 'app:fcm:send-message';

    protected function configure()
    {
        $this
            ->setDescription('Send an FCM message')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $messaging = $this->getContainer()->get('kreait_firebase.default')->getMessaging();

        $io = new SymfonyStyle($input, $output);

        $target = $io->choice('Please select a target', ['Topic', 'Condition', 'Registration Token']);
        $validator = function ($answer) use ($target) {
            if (empty($answer)) {
                throw new InvalidArgumentException('The '.$target.' must not be empty');
            }

            return $answer;
        };

        switch ($target) {
            case 'Topic':
                $topic = $io->ask('Please enter the name of the topic', null, $validator);
                $message = MessageToTopic::create($topic);
                break;
            case 'Condition':
                $condition = $io->ask('Please enter the condition', null, $validator);
                $message = ConditionalMessage::create($condition);
                break;
            case 'Registration Token':
                $registrationToken = $io->ask('Please enter the registration token', null, $validator);
                $message = MessageToRegistrationToken::create($registrationToken);
                break;
        }

        $message = $message->withNotification([
            'title' => $io->ask('Please enter the title of your message'),
            'body' => $io->ask('Please enter the body of your message'),
        ]);

        $responseData = $messaging->send($message);

        $io->success('The message has been sent and the API returned the following:');
        $io->writeln(json_encode($responseData, JSON_PRETTY_PRINT));
    }
}
