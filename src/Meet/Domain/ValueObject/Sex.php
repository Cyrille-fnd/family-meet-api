<?php

declare(strict_types=1);

namespace App\Meet\Domain\ValueObject;

enum Sex: string
{
    case MALE = 'male';
    case FEMALE = 'female';
}
