<?php

declare(strict_types=1);

namespace App\Infrastructure\Bridge\Symfony\Doctrine\types;

use App\Domain\ValueObject\Identity\GuestId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class GuestIdType extends StringType
{
    public const string NAME = 'guest_id';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        if (!$value instanceof GuestId) {
            throw new \InvalidArgumentException(\sprintf('Expected instance of %s, got %s', GuestId::class, get_debug_type($value)));
        }

        return $value->value();
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): GuestId
    {
        if (!$value instanceof GuestId && !\is_string($value)) {
            throw new \InvalidArgumentException(\sprintf('Expected instance of %s, got %s', GuestId::class, get_debug_type($value)));
        }

        if ($value instanceof GuestId) {
            return $value;
        }

        return GuestId::fromString($value);
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
