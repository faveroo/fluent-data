<?php

namespace Gabriel\FluentData\Support;

class Str
{
    public function slug(string $value): string
    {
        return strtolower(
            str_replace(' ', '-', trim($value))
        );
    }

    public function studly(string $value): string
    {
        $value = str_replace(
            ['-', '_'],
            ' ',
            $value
        );

        $value = ucwords($value);

        return str_replace(' ', '', $value);
    }

    public function camel(string $value): string
    {
        return lcfirst(
            static::studly($value)
        );
    }

    public function snake(string $value): string
    {
        return strtolower(
            preg_replace(
                '/(.)(?=[A-Z])/u',
                '$1_',
                $value
            )
        );
    }

    public function startsWith(
        string $haystack,
        string $needle
    ): bool {
        return str_starts_with(
            $haystack,
            $needle
        );
    }

    public function endsWith(
        string $haystack,
        string $needle
    ): bool {
        return str_ends_with(
            $haystack,
            $needle
        );
    }

    public function contains(
        string $haystack,
        string $needle
    ): bool {
        return str_contains($haystack, $needle);
    }

    public function random(
        int $length
    ): string {
        return substr(
            str_shuffle(
                'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'
            ),
            0,
            $length
        );
    }
}