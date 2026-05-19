<?php

namespace Gabriel\FluentData\Collections\Traits;

trait EnumeratesValues
{
    protected function valueFromItem(
        mixed $item,
        string|callable|null $key = null
    ): mixed {
        if (is_callable($key)) {
            return $key($item);
        }

        if ($key === null) {
            return $item;
        }

        if (is_array($item)) {
            return $item[$key] ?? null;
        }

        if (is_object($item)) {
            return $item->{$key} ?? null;
        }

        return null;
    }

    public function map(callable $callback): static
    {
        return new static(
            array_map($callback, $this->items)
        );
    }

    public function filter(callable $callback): static
    {
        $new = array_filter($this->items, $callback);
        return new static(array_values($new));
    }

    public function contains(mixed $value): bool
    {
        return in_array($value, $this->items, true);
    }

    public function reject(callable $callback): static
    {
        return $this->filter(
            fn ($item) => !$callback($item)
        );
    }

    public function reduce(
        callable $callback,
        mixed $init = null
    ): mixed {
        return array_reduce(
            $this->items,
            $callback,
            $init
        );
    }

    public function first(): mixed
    {
        if ($this->items === []) {
            return null;
        }

        return reset($this->items);
    }

    public function last(): mixed
    {
        if ($this->items === []) {
            return null;
        }

        return end($this->items);
    }

    public function each(callable $callback): static
    {
        foreach ($this->items as $key => $item) {
            $callback($item, $key);
        }

        return $this;
    }

    public function pluck(string $key): static
    {
        return new static(
            array_map(
                fn ($item) => is_array($item)
                    ? ($item[$key] ?? null)
                    : ($item->{$key} ?? null),
                $this->items
            )
        );
    }

    public function where(
        string $key,
        mixed $value,
        string $operator = '='
    ): static {
        $operators = [
            '='   => fn ($a, $b) => $a == $b,
            '=='  => fn ($a, $b) => $a == $b,
            '===' => fn ($a, $b) => $a === $b,
            '!='  => fn ($a, $b) => $a != $b,
            '!==' => fn ($a, $b) => $a !== $b,
            '>'   => fn ($a, $b) => $a > $b,
            '<'   => fn ($a, $b) => $a < $b,
            '>='  => fn ($a, $b) => $a >= $b,
            '<='  => fn ($a, $b) => $a <= $b,
        ];

        return new static(array_filter(
            $this->items,
            function ($item) use ($key, $value, $operator, $operators) {

                if (! isset($operators[$operator])) {
                    return false;
                }

                return $operators[$operator](
                    $this->valueFromItem($item, $key),
                    $value
                );
            }
        ));
    }

    public function firstWhere(
        string $key,
        mixed $value,
        string $operator = '='
    ): mixed {
        foreach ($this->items as $item) {
            $itemValue = $this->valueFromItem($item, $key);

            $match = match ($operator) {
                '=', '=='  => $itemValue == $value,
                '==='      => $itemValue === $value,
                '!='       => $itemValue != $value,
                '!=='      => $itemValue !== $value,
                '>'        => $itemValue > $value,
                '<'        => $itemValue < $value,
                '>='       => $itemValue >= $value,
                '<='       => $itemValue <= $value,
                default    => false,
            };

            if ($match) {
                return $item;
            }
        }

        return null;
    }

    public function sum(
        string|callable|null $callback = null
    ): int|float {
        if ($callback === null) {
            return array_sum($this->items);
        }

        return array_reduce(
            $this->items,
            function ($carry, $item) use ($callback) {
                $value = $this->valueFromItem($item, $callback);

                return $carry + $value;
            },
            0
        );
    }

    public function avg(
        string|callable|null $callback = null
    ): float|null {

        if (empty($this->items)) {
            return null;
        }

        return (float) $this->sum($callback) / count($this->items);
    }

    public function groupBy(
        string|callable $key
    ): static {
        $grouped = [];

        foreach ($this->items as $item) {
            $groupKey = $this->valueFromItem($item, $key) ?? '';

            $grouped[$groupKey][] = $item;
        }

        return new static($grouped);
    }

    public function sortBy(string|callable $key): static
    {
        $items = $this->items;

        usort(
            $items,
            function (mixed $left, mixed $right) use ($key): int {
                return $this->valueFromItem($left, $key)
                    <=> $this->valueFromItem($right, $key);
            }
        );

        return new static($items);
    }

    public function keyBy(string|callable $key): static
    {
        $results = [];

        foreach ($this->items as $item) {
            $results[$this->valueFromItem($item, $key)] = $item;
        }

        return new static($results);
    }

    public function unique(?string $column = null): static
    {
        $results = [];
        $seen = [];

        foreach ($this->items as $item) {
            $value = $this->valueFromItem($item, $column);
            $serialized = is_scalar($value) || $value === null
                ? $value
                : serialize($value);

            if (in_array($serialized, $seen, true)) {
                continue;
            }

            $seen[] = $serialized;
            $results[] = $item;
        }

        return new static($results);
    }

    public function take(int $limit): static
    {
        if ($limit === 0) {
            return new static([]);
        }

        if ($limit > 0) {
            return new static(
                array_slice($this->items, 0, $limit)
            );
        }

        return new static(
            array_slice($this->items, $limit)
        );
    }

    public function chunk(int $size): static
    {
        if ($size < 1) {
            return new static([]);
        }

        return new static(
            array_map(
                fn (array $chunk) => new static($chunk),
                array_chunk($this->items, $size)
            )
        );
    }
}
