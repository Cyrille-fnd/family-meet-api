<?php

declare(strict_types=1);

namespace App\Meet\Application;

interface CommandBusInterface
{
    public function dispatch(CommandInterface $command): void;
}
