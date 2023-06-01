<?php
namespace App\EventListener;

use Symfony\Component\Console\Event\ConsoleErrorEvent;
use TurboLabIt\Messengers\SlackMessenger;


class CommandFailureListener
{
    public function __construct(protected SlackMessenger $slackMessenger)
    {
    }


    public function onCommandFailure(ConsoleErrorEvent $event)
    {
        // https://symfony.com/doc/current/components/console/events.html#the-consoleevents-error-event

        $text =
            "🛑 godaddy-tools is failing" . PHP_EOL . PHP_EOL .
            "Command: *" . $event->getCommand()?->getName() . "*" . PHP_EOL .
            "Error: *" . $event->getError()->getMessage() . "*";

        $this->slackMessenger->sendErrorMessage($text);
    }
}
