<?php

namespace App\EventSubscriber;

use App\Event\NewHomeworkAdded;
use App\Message\NotifyStudentsMessage;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class HomeworkEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
          NewHomeworkAdded::class => 'onNewHomeworkAdded'
        ];
    }

    /**
     * @throws ExceptionInterface
     */
    public function onNewHomeworkAdded(NewHomeworkAdded $event): void
    {
        $homework = $event->getHomework();
        $this->messageBus->dispatch(new NotifyStudentsMessage($homework->getId()));
    }
}
