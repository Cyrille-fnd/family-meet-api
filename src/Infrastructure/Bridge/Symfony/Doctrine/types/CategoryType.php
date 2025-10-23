<?php

declare(strict_types=1);

namespace App\Infrastructure\Bridge\Symfony\Doctrine\types;

use App\Domain\ValueObject\Category;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class CategoryType extends StringType
{
    public const string NAME = 'category';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        if (!$value instanceof Category) {
            throw new \InvalidArgumentException(\sprintf('Expected instance of %s, got %s', Category::class, get_debug_type($value)));
        }

        return $value->value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): Category
    {
        if (!\is_string($value)) {
            throw new \InvalidArgumentException(\sprintf('Expected instance of string, got %s', get_debug_type($value)));
        }

        return Category::from($value);
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
