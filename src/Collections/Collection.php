<?php

namespace Gabriel\FluentData\Collections;

use ArrayAccess;
use ArrayIterator;
use Gabriel\FluentData\Collections\Traits\EnumeratesValues;
use Gabriel\FluentData\Collections\Traits\Macroable;
use Gabriel\FluentData\Contracts\Arrayable;
use Gabriel\FluentData\Contracts\Enumerable;
use Gabriel\FluentData\Contracts\Jsonable;
use Gabriel\FluentData\Support\Fluent;
use JsonSerializable;
use Traversable;

class Collection extends Fluent implements
    Arrayable,
    ArrayAccess,
    Enumerable,
    JsonSerializable,
    Jsonable
{
    use Macroable;
    use EnumeratesValues;

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
        if ($offset === null) {
            $this->items[] = $value;
            return;
        }

        $this->items[$offset] = $value;
    }

    public function offsetUnset($offset): void
    {
        unset($this->items[$offset]);
    }

    public function all(): array
    {
        return $this->items;
    }

    public function toArray(): array
    {
        return array_map(
            fn (mixed $item) => $this->normalizeItem($item),
            $this->items
        );
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }

    public function toJson(
        int $options = JSON_PRETTY_PRINT
    ): string {
        return json_encode(
            $this->jsonSerialize(),
            $options
        );
    }

    protected function normalizeItem(mixed $item): mixed
    {
        if ($item instanceof Arrayable) {
            return $item->toArray();
        }

        if ($item instanceof JsonSerializable) {
            return $this->normalizeItem(
                $item->jsonSerialize()
            );
        }

        if (is_array($item)) {
            return array_map(
                fn (mixed $value) => $this->normalizeItem($value),
                $item
            );
        }

        return $item;
    }

}
