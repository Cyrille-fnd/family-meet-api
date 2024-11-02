<?php

declare(strict_types=1);

namespace App\Meet\Application\Signup;

use App\Meet\Application\CommandInterface;
use App\Meet\Domain\ValueObject\SignupInformation;

final readonly class SignupCommand implements CommandInterface
{
    public function __construct(public SignupInformation $signupInformation)
    {
    }
}
