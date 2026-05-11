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
        return new static(
            array_filter($this->items, $callback)
        );
    }

    public function contains(mixed $value): bool
    {
        return in_array($value, $this->items);
    }

    public function reject(callable $callback): static{
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
        return end($this->items);
    }

    public function each(callable $callback): static
    {
        foreach ($this->items as $key => $items)
            {
                $callback($items, $key);
            }

        return $this;
        
    }

    public function pluck(string $key): static
    {
        return new static(
            array_map(
                fn($item) => $item[$key] ?? null,
                $this->items
            )
        );
    }
}
