<?php

namespace App\EventSubscriber;

use App\Event\NewGradeAdded;
use App\Message\NotifyParentMessage;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class GradeEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
          NewGradeAdded::class => 'onNewGradeAdded'
        ];
    }

    /**
     * @throws ExceptionInterface
     */
    public function onNewGradeAdded(NewGradeAdded $event): void
    {
        $grade = $event->getGrade();
        $parent = $event->getParent();
        if ($parent?->getContactEmail()){
            $this->messageBus->dispatch(new NotifyParentMessage($grade->getId()));
        }
    }
}
