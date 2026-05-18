<?php

namespace Gabriel\FluentData\Facades;

/**
 * @method static mixed first(array $items, ?callable $callback = null, mixed $default = null)
 * @method static mixed last(array $items, ?callable $callback = null, mixed $default = null)
 * @method static bool contains(array $items, mixed $value)
 * @method static array filter(array $items, callable $callback)
 * @method static array pluck(array $items, string $key)
 * @method static array only(array $items, array $keys)
 */
class Arr extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Gabriel\FluentData\Support\Arr::class;
    }
}
