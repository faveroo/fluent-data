<?php

namespace Gabriel\FluentData\Support;

class Arr
{
    public static function first(
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

    public static function last(
        array $items,
        ?callable $callback = null,
        mixed $default = null
    ): mixed {
        if ($callback === null) {
            return $items === []
                ? value($default)
                : end($items);
        }

        return static::first(
            array_reverse($items, true),
            $callback,
            $default
        );
    }

    public static function contains(
        array $items,
        mixed $value
    ): bool {
        return in_array($value, $items, true);
    }

    public static function filter(
        array $items,
        callable $callback
    ): array {
        return array_filter($items, $callback, ARRAY_FILTER_USE_BOTH);
    }

    public static function pluck(
        array $items,
        string $key
    ): array {
        return array_map(
            fn ($item) => is_array($item)
                ? ($item[$key] ?? null)
                : ($item->{$key} ?? null),
            $items
        );
    }
}
