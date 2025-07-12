<?php

declare(strict_types=1);

namespace App\Infrastructure\Bridge\Symfony\Messenger;

use App\Application\EventDispatcherInterface;
use App\Application\EventInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class EventDispatcher implements EventDispatcherInterface
{
    public function __construct(private MessageBusInterface $eventBus)
    {
    }

    public function dispatch(EventInterface $event): void
    {
        $this->eventBus->dispatch($event);
    }
}
