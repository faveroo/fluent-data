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

    public function first(): mixed
    {
        return reset($this->items);
    }

    public function last(): mixed
    {
        return end($this->items);
    }
}