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

        foreach ($items as $index => $item) {
            $value = is_array($item)
                ? ($item[$key] ?? null)
                : ($item->{$key} ?? null);

            if ($value !== null) {
                $results[$index] = $value;
            }
        }

        return $results;
    }

    public function only(
        array $items,
        array $keys
    ): array {
        return array_intersect_key(
            $items,
            array_flip($keys)
        );
    }

    public function get(
        array $items,
        string|int|null $key,
    ): mixed {
        if($key === null) {
            return $items;
        }

        $segments = explode('.', (string) $key);
        $value = $items;

        foreach ($segments as $segment) {
            if(!is_array($value) || !array_key_exists($segment, $value)) {
                return value(null);
            }

            $value = $value[$segment];
        }

        return $value;
    }

    public function has(
        array $items,
        string|int $key
    ): bool {
        $segments = explode('.', (string) $key);
        $value = $items;

        foreach ($segments as $segment) {
            if (! is_array($value) || ! array_key_exists($segment, $value)) {
                return false;
            }

            $value = $value[$segment];
        }

        return true;
    }

    public function set(
        array $items,
        string|int $key,
        mixed $value
    ): array {
        if ($key === null) {
            return $items;
        }

        $segments = explode('.', (string) $key);
        $current = &$items;

        foreach ($segments as $segment) {
            if (! isset($current[$segment]) || ! is_array($current[$segment])) {
                $current[$segment] = [];
            }

            $current = &$current[$segment];
        }

        $current = $value;

        return $items;
    }

    public function forget(
        array $items,
        string|int $key
    ): array {
        $segments = explode('.', (string) $key);
        $current = &$items;

        while(count($segments) > 1) {
            $segment = array_shift($segments);

            if(! isset($current[$segment]) || ! is_array($current[$segment])) {
                return $items;
            }

            $current = &$current[$segment];
        }

        unset($current[array_shift($segments)]);
        return $items;
    }

    public function except(
        array $items,
        array $keys
    ): array {
        foreach ($keys as $key) {
            $items = $this->forget($items, $key);
        }

        return $items;
    }

    public function dot(
        array $items,
        string $prepend = ''
    ): array {
        $results = [];

        foreach ($items as $key => $value) {
            $fullKey = $prepend === ''
                ? (string) $key
                : $prepend . '.' . $key;

            if (is_array($value) && $value !== []) {
                $results = array_merge(
                    $results,
                    $this->dot($value, $fullKey)
                );
            } else {
                $results[$fullKey] = $value;
            }
        }

        return $results;
    }

    public function undot(array $items): array
    {
        $results = [];

        foreach ($items as $key => $value) {
            $results = $this->set($results, $key, $value);
        }

        return $results;
    }

    public function flatten(array $items): array
    {
        $results = [];

        foreach ($items as $value) {
            if (is_array($value)) {
                $results = array_merge($results, $this->flatten($value));
            } else {
                $results[] = $value;
            }
        }

        return $results;
    }
}
