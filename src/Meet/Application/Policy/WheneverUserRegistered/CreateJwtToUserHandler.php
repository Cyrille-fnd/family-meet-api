<?php

declare(strict_types=1);

namespace App\Meet\Application\Policy\WheneverUserRegistered;

use App\Meet\Domain\Event\UserRegisteredEvent;

final readonly class CreateJwtToUserHandler
{
    public function __invoke(UserRegisteredEvent $event): void
    {
        // TODO
    }
}
