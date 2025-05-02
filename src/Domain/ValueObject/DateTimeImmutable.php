<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use Carbon\CarbonImmutable;

final readonly class DateTimeImmutable
{
    public function __construct(private string $value)
    {
        $this->ensureIsValid($this->value);
    }

    public static function fromString(string $value): self
    {
        return self::fromDateTime(CarbonImmutable::parse($value));
    }

    public static function fromIso8601(string $value): self
    {
        return new self($value);
    }

    public static function fromDateTime(\DateTimeInterface $value): self
    {
        return new self($value->format('c'));
    }

    public static function create(string $value = 'now'): self
    {
        return self::fromDateTime(new CarbonImmutable($value));
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function afterThan(self $other): bool
    {
        return $this->value > $other->value;
    }

    public function afterOrEqualsThan(self $other): bool
    {
        return $this->value >= $other->value;
    }

    public function beforeThan(self $other): bool
    {
        return $this->value < $other->value;
    }

    public function beforeOrEqualsThan(self $other): bool
    {
        return $this->value <= $other->value;
    }

    public function format(string $format): string
    {
        return $this->toDateTime()->format($format);
    }

    public function toDateTime(): \DateTimeImmutable
    {
        return new CarbonImmutable($this->value);
    }

    private function ensureIsValid(string $value, string $format = 'c'): void
    {
        try {
            CarbonImmutable::createFromFormat($format, $value);
        } catch (\Exception $e) {
            throw new \RuntimeException($e->getMessage(), 0, $e);
        }
    }
}
