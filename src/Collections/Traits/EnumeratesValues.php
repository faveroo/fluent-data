<?php

namespace Gabriel\FluentData\Collections\Traits;

trait EnumeratesValues
{
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
            '='   => fn($a, $b) => $a == $b,
            '=='  => fn($a, $b) => $a == $b,
            '===' => fn($a, $b) => $a === $b,
            '!='  => fn($a, $b) => $a != $b,
            '!==' => fn($a, $b) => $a !== $b,
            '>'   => fn($a, $b) => $a > $b,
            '<'   => fn($a, $b) => $a < $b,
            '>='  => fn($a, $b) => $a >= $b,
            '<='  => fn($a, $b) => $a <= $b,
        ];

        return new static(array_filter(
            $this->items,
            function ($item) use ($key, $value, $operator, $operators) {

                if (! isset($operators[$operator])) {
                    return false;
                }

                return $operators[$operator](
                    $item[$key],
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
            $match = match ($operator) {
                '=', '=='  => $item[$key] == $value,
                '==='      => $item[$key] === $value,
                '!='       => $item[$key] != $value,
                '!=='      => $item[$key] !== $value,
                '>'        => $item[$key] > $value,
                '<'        => $item[$key] < $value,
                '>='       => $item[$key] >= $value,
                '<='       => $item[$key] <= $value,
                default    => false,
            };

            if ($match) {
                return $item;
            }
        }

        return null;
    }
}
