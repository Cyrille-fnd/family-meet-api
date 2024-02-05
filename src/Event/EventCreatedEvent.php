<?php

namespace App\Event;

use App\Entity\Event as FamilyEvent;
use Symfony\Contracts\EventDispatcher\Event;

class EventCreatedEvent extends Event
{
    private FamilyEvent $event;

    public function __construct(FamilyEvent $event)
    {
        $this->event = $event;
    }

    public function getEvent(): FamilyEvent
    {
        return $this->event;
    }
}
