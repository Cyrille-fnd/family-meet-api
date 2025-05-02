<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\Bridge\Messenger;

use App\Application\CommandBusInterface;
use App\Application\CommandInterface;
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
