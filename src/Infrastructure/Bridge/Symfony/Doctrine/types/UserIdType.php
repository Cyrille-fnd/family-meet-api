<?php

declare(strict_types=1);

namespace App\Infrastructure\Bridge\Symfony\Doctrine\types;

use App\Domain\ValueObject\Identity\UserId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class UserIdType extends StringType
{
    public const string NAME = 'user_id';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        if (!$value instanceof UserId) {
            throw new \InvalidArgumentException(\sprintf('Expected instance of %s, got %s', UserId::class, get_debug_type($value)));
        }

        return $value->value();
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): UserId
    {
        if (!$value instanceof UserId && !\is_string($value)) {
            throw new \InvalidArgumentException(\sprintf('Expected instance of %s, got %s', UserId::class, get_debug_type($value)));
        }

        if ($value instanceof UserId) {
            return $value;
        }

        return UserId::fromString($value);
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
