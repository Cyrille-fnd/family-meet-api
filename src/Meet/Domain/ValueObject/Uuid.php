<?php

declare(strict_types=1);

namespace App\Meet\Domain\ValueObject;

use Ramsey\Uuid\Uuid as RamseyUuid;

abstract readonly class Uuid implements \Stringable
{
    final public function __construct(
        private string $value,
    ) {
    }

    public static function create(): static
    {
        return new static(RamseyUuid::uuid4()->toString());
    }

    public static function fromString(string $value): static
    {
        return new static(RamseyUuid::fromString($value)->toString());
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
