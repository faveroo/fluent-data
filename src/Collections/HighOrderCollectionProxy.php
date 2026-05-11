<?php

namespace Gabriel\FluentData\Collections;

class HighOrderCollectionProxy
{
    public function __construct(
        protected Collection $collection,
        protected string $method
    ) {}

    public function __get(string $prop): Collection
    {
        return $this->collection->{$this->method}(
            fn ($item) => is_array($item)
                ? ($item[$prop] ?? null)
                : ($item->{$prop} ?? null)
        );
    }
}
