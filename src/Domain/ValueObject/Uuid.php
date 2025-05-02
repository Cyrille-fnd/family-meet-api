<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use Ramsey\Uuid\Uuid as RamseyUuid;

abstract readonly class Uuid implements \Stringable
{
    final public function __construct(protected string $value)
    {
        $this->ensureIsValidUuid($this->value);
    }

    public static function create(): static
    {
        return new static(RamseyUuid::uuid4()->toString());
    }

    public static function fromString(string $id): static
    {
        return new static(RamseyUuid::fromString($id)->toString());
    }

    final public function value(): string
    {
        return $this->value;
    }

    final public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    private function ensureIsValidUuid(string $id): void
    {
        if (!RamseyUuid::isValid($id)) {
            throw new \InvalidArgumentException(\sprintf('<%s> doest not allow the value <%s>', self::class, $id));
        }
    }
}
