<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

enum Sex: string
{
    case MALE = 'male';
    case FEMALE = 'female';
}
