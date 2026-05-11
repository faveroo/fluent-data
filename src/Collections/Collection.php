<?php

namespace Gabriel\FluentData\Collections;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;

use Gabriel\FluentData\Collections\Traits\EnumeratesValues;
use Gabriel\FluentData\Collections\Traits\Macroable;
use Gabriel\FluentData\Contracts\Arrayable;
use Gabriel\FluentData\Contracts\Jsonable;
use Gabriel\FluentData\Support\Fluent;
use Override;
use Traversable;

class Collection extends Fluent implements
    Arrayable,
    ArrayAccess,
    Countable,
    IteratorAggregate,
    Jsonable
{
    use Macroable, EnumeratesValues;

    protected array $items = [];

    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    public function __get(string $key): mixed
    {
        return new HighOrderCollectionProxy(
            $this,
            $key
        );
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }

    public function offsetExists($offset): bool
    {
        return isset($this->items[$offset]);
    }

    public function offsetGet($offset): mixed
    {
        return $this->items[$offset];
    }

    public function offsetSet($offset, $value): void
    {
        $this->items[$offset] = $value;
    }

    public function offsetUnset($offset): void
    {
        unset($this->items[$offset]);
    }

    public function toArray(): array
    {
        return $this->items;
    }

    public function toJson(): string
    {
        return json_encode($this->items, JSON_PRETTY_PRINT);
    }
}