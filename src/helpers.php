<?php

use Gabriel\FluentData\Collections\Collection;

if(!function_exists('collect')) {
    function collect(array $items): Collection
    {
        return new Collection($items);
    }
}

if(!function_exists('dd')) {
    function dd(mixed ...$vars): never
    {
        var_dump(...$vars);

        die();
    }
}

if(!function_exists('tap')) {
    function tap(
        mixed $value,
        callable $callback
    ): mixed {

        $callback($value);

        return $value;
    }
}

if(!function_exists('value')) {
    function value(mixed $value): mixed 
    {
        return $value instanceof Closure
            ? $value()
            : $value;
    }
}