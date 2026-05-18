<?php

namespace Gabriel\FluentData\Facades;

/**
 * @method static string slug(string $value)
 * @method static string studly(string $value)
 * @method static string camel(string $value)
 * @method static string snake(string $value)
 * @method static bool startsWith(string $haystack, string $needle)
 * @method static bool endsWith(string $haystack, string $needle)
 * @method static bool contains(string $haystack, string $needle)
 * @method static string random(int $length = 16)
 * @method static array ascii(string $str)
 * @method static string randomize(string $str)
 * @method static string binary(string $str)
 */
class Str extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Gabriel\FluentData\Support\Str::class;
    }
}
