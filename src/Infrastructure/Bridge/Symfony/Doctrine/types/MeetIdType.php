<?php

declare(strict_types=1);

namespace App\Infrastructure\Bridge\Symfony\Doctrine\types;

use App\Domain\ValueObject\Identity\MeetId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class MeetIdType extends StringType
{
    public const string NAME = 'meet_id';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        if (!$value instanceof MeetId) {
            throw new \InvalidArgumentException(\sprintf('Expected instance of %s, got %s', MeetId::class, get_debug_type($value)));
        }

        return $value->value();
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): MeetId
    {
        if (!$value instanceof MeetId && !\is_string($value)) {
            throw new \InvalidArgumentException(\sprintf('Expected instance of %s, got %s', MeetId::class, get_debug_type($value)));
        }

        if ($value instanceof MeetId) {
            return $value;
        }

        return MeetId::fromString($value);
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
