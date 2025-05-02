<?php

declare(strict_types=1);

namespace App\Application;

interface EventBusInterface
{
    public function dispatch(EventInterface $event): void;
}
