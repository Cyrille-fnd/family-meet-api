<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

class Collection implements \Countable
{
    /**
     * @param array<int, mixed> $items
     */
    public function __construct(
        private array $items,
        private ?int $totalElements = null,
    ) {
        $this->totalElements ?? $this->totalElements = \count($this->items);
    }

    /**
     * @param array<int, mixed> $items
     */
    public static function fromArray(array $items): self
    {
        return new self($items);
    }

    public static function create(): self
    {
        return new self([]);
    }

    public function filter(\Closure $func): self
    {
        return static::fromArray(
            array_values(
                array_filter($this->items, $func)
            )
        );
    }

    /**
     * @return array<int, mixed>
     */
    public function map(\Closure $func): array
    {
        return array_map($func, $this->items);
    }

    public function add(mixed $item): void
    {
        if ($this->contains($item)) {
            return;
        }

        $this->items[] = $item;
        ++$this->totalElements;
    }

    public function count(): int
    {
        return $this->totalElements ?? 0;
    }

    private function contains(mixed $item): bool
    {
        return \in_array($item, $this->items, true);
    }

    /**
     * @return array<int, mixed>
     */
    public function toArray(): array
    {
        return array_values($this->items);
    }

    public function remove(mixed $item): void
    {
        foreach ($this->items as $key => $value) {
            if ($value === $item) {
                unset($this->items[$key]);
                --$this->totalElements;

                return;
            }
        }
    }
}
