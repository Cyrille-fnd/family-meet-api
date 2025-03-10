<?php

declare(strict_types=1);

namespace App\Meet\Infrastructure\Symfony\Bridge\Messenger;

use App\Meet\Application\CommandBusInterface;
use App\Meet\Application\CommandInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class CommandBus implements CommandBusInterface
{
    public function __construct(private MessageBusInterface $commandBus)
    {
    }

    public function dispatch(CommandInterface $command): void
    {
        $this->commandBus->dispatch($command);
    }
}
