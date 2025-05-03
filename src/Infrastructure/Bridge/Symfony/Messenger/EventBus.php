<?php

declare(strict_types=1);

namespace App\Infrastructure\Bridge\Symfony\Messenger;

use App\Application\EventBusInterface;
use App\Application\EventInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class EventBus implements EventBusInterface
{
    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    public function dispatch(EventInterface $event): void
    {
        $this->messageBus->dispatch($event);
    }
}
