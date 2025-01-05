<?php

declare(strict_types=1);

namespace App\Meet\Infrastructure\Symfony\Bridge\Messenger;

use App\Meet\Application\EventBusInterface;
use App\Meet\Application\EventInterface;
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
