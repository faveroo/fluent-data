<?php

namespace Gabriel\FluentData\Support;

class Str
{
    public static function slug(string $value): string
    {
        return strtolower(
            preg_replace('/[^a-zA-Z0-9]+/', '-', trim($value))
        );
    }

    public static function studly(string $value): string
    {
        $value = str_replace(
            ['-', '_'],
            ' ',
            $value
        );

        return str_replace(
            ' ',
            '',
            ucwords($value)
        );
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

    public static function random(
        int $length = 16
    ): string {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $maxIndex = strlen($alphabet) - 1;
        $random = '';

        for ($index = 0; $index < $length; $index++) {
            $random .= $alphabet[random_int(0, $maxIndex)];
        }

        return $random;
    }
}
