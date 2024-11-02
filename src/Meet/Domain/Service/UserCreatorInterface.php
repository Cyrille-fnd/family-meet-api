<?php

declare(strict_types=1);

namespace App\Meet\Domain\Service;

use App\Meet\Anticorruption\User;
use App\Meet\Domain\ValueObject\SignupInformation;

interface UserCreatorInterface
{
    public function create(SignupInformation $signupInformation): User;
}
