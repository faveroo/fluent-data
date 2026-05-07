<?php

namespace Gabriel\FluentData\Collections;

use Gabriel\FluentData\Collections\Traits\EnumeratesValues;
use Gabriel\FluentData\Collections\Traits\Macroable;
use Gabriel\FluentData\Contracts\Arrayable;
use Gabriel\FluentData\Support\Fluent;

class Collection extends Fluent implements Arrayable
{
    use Macroable, EnumeratesValues;

    protected array $items = [];

    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    public function each(callable $callback): static
    {
        foreach ($this->items as $key => $items)
            {
                $callback($items, $key);
            }

        return $this;
        
    }

    public function toArray(): array
    {
        return $this->items;
    }
}