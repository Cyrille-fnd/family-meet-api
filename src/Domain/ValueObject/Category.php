<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

enum Category: string
{
    case TRAVAIL = 'travail';
    case RESTAURANT = 'restaurant';
    case CLUBBING = 'clubbing';
    case SPORT = 'sport';
    case JEUX = 'jeux';
    case BAR = 'bar';
    case VOYAGE = 'voyage';
    case CINEMA = 'cinema';
}
