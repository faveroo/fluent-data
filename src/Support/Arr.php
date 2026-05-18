<?php

namespace Gabriel\FluentData\Support;

class Arr
{
    public function first(
        array $items,
        ?callable $callback = null,
        mixed $default = null
    ): mixed {
        if ($callback === null) {
            return $items === []
                ? value($default)
                : reset($items);
        }

        foreach ($items as $key => $item) {
            if ($callback($item, $key)) {
                return $item;
            }
        }

        return value($default);
    }

    public function last(
        array $items,
        ?callable $callback = null,
        mixed $default = null
    ): mixed {
        if ($callback === null) {
            return $items === []
                ? value($default)
                : end($items);
        }

        return $this->first(
            array_reverse($items, true),
            $callback,
            $default
        );
    }

    public function contains(
        array $items,
        mixed $value
    ): bool {
        return in_array($value, $items, true);
    }

    public function filter(
        array $items,
        callable $callback
    ): array {
        return array_filter($items, $callback, ARRAY_FILTER_USE_BOTH);
    }

    public function pluck(
        array $items,
        string $key
    ): array {
        $results = [];

        foreach($items as $index => $item) {
            $value = is_array($item)
                ? ($item[$key] ?? null)
                : ($item->{$key} ?? null);
                
            if($value !== null) {
                $results[$index] = $value;
            }
        }

        return $results;
    }

    public function only(
        array $items,
        array $keys
    ) : array
    {
        return array_intersect_key(
            $items,
            array_flip($keys)
        );  
    }
}
