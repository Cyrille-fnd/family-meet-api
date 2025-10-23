<?php

declare(strict_types=1);

namespace App\Infrastructure\Bridge\Symfony\Doctrine\types;

use App\Domain\ValueObject\Identity\HostId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class HostIdType extends StringType
{
    public const string NAME = 'host_id';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        if (!$value instanceof HostId) {
            throw new \InvalidArgumentException(\sprintf('Expected instance of %s, got %s', HostId::class, get_debug_type($value)));
        }

        return $value->value();
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): HostId
    {
        if (!$value instanceof HostId && !\is_string($value)) {
            throw new \InvalidArgumentException(\sprintf('Expected instance of %s, got %s', HostId::class, get_debug_type($value)));
        }

        if ($value instanceof HostId) {
            return $value;
        }

        return HostId::fromString($value);
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
