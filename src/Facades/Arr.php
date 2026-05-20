<?php

namespace Gabriel\FluentData\Facades;

/**
 * @method static mixed first(array $items, ?callable $callback = null, mixed $default = null)
 * @method static mixed last(array $items, ?callable $callback = null, mixed $default = null)
 * @method static bool contains(array $items, mixed $value)
 * @method static array filter(array $items, callable $callback)
 * @method static array pluck(array $items, string $key)
 * @method static array only(array $items, array $keys)
 * @method static mixed get(array $items, string|int|null $key)
 * @method static bool has(array $items, string|int $key)
 * @method static array set(array $items, string|int $key, mixed $value)
 * @method static array forget(array $items, string|int $key)
 * @method static array except(array $items, array $keys)
 * @method static array dot(array $items, string $prepend = '')
 * @method static array undot(array $items)
 * @method static array flatten(array $items)
 */
class Arr extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Gabriel\FluentData\Support\Arr::class;
    }
}
