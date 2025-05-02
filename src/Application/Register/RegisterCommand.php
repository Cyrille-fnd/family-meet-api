<?php

declare(strict_types=1);

namespace App\Application\Register;

use App\Application\CommandInterface;
use App\Domain\ValueObject\RegisterInformation;

final readonly class RegisterCommand implements CommandInterface
{
    public function __construct(public RegisterInformation $registerInformation)
    {
    }
}
