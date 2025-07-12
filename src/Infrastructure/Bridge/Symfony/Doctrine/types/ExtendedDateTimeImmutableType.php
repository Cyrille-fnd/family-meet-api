<?php

declare(strict_types=1);

namespace App\Infrastructure\Bridge\Symfony\Doctrine\types;

use App\Domain\ValueObject\DateTimeImmutable;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\DateTimeImmutableType;

class ExtendedDateTimeImmutableType extends DateTimeImmutableType
{
    public const string NAME = 'extended_datetime_immutable';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        if (!$value instanceof DateTimeImmutable) {
            throw new \InvalidArgumentException(\sprintf('Expected instance of %s, got %s', DateTimeImmutable::class, get_debug_type($value)));
        }

        return $value->toDateTime()->format($platform->getDateTimeFormatString());
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?DateTimeImmutable
    {
        if (null === $value || $value instanceof DateTimeImmutable) {
            return $value;
        }

        return DateTimeImmutable::fromDateTime(parent::convertToPHPValue($value, $platform));
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function getMappedDatabaseTypes(AbstractPlatform $platform): array
    {
        return [self::NAME];
    }
}
