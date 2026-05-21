<?php

namespace Gabriel\FluentData\Facades;

/**
 * @method static string slug(string $value)
 * @method static string studly(string $value)
 * @method static string camel(string $value)
 * @method static string snake(string $value)
 * @method static string kebab(string $value)
 * @method static bool startsWith(string $haystack, string $needle)
 * @method static bool endsWith(string $haystack, string $needle)
 * @method static bool contains(string $haystack, string $needle)
 * @method static string random(int $length = 16)
 * @method static array ascii(string $str)
 * @method static string randomize(string $str)
 * @method static string binary(string $str)
 * @method static string limit(string $str, int $limit)
 * @method static string before(string $string, string $search)
 * @method static string after(string $string, string $search)
 * @method static string between(string $string, string $start, string $end)
 * @method static bool has(string $string, string $search, int $flag = 0)
 * @method static string|array replace(string $search, string $replace, string $subject)
 * @method static string uuid()
 */
class Str extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Gabriel\FluentData\Support\Str::class;
    }
}
