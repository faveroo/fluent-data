<?php

namespace Gabriel\FluentData\Support;

class Str
{
    public static function slug(string $value): string
    {
        return strtolower(
            str_replace(' ', '-', trim($value))
        );
    }

    public static function studly(string $value): string
    {
        $value = str_replace(
            ['-', '_'],
            ' ',
            $value
        );

        $value = ucwords($value);

        return str_replace(' ', '', $value);
    }

    public static function camel(string $value): string
    {
        return lcfirst(
            static::studly($value)
        );
    }

    public static function snake(string $value): string
    {
        return strtolower(
            preg_replace(
                '/(.)(?=[A-Z])/u',
                '$1_',
                $value
            )
        );
    }

    public static function startsWith(
        string $haystack,
        string $needle
    ): bool {
        return str_starts_with(
            $haystack,
            $needle
        );
    }

    public static function endsWith(
        string $haystack,
        string $needle
    ): bool {
        return str_ends_with(
            $haystack,
            $needle
        );
    }

    public static function contains(
        string $haystack,
        string $needle
    ): bool {
        return str_contains($haystack, $needle);
    }
}