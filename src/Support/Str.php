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

    public static function contains(
        string $haystack,
        string $needle
    ): bool {
        return str_contains($haystack, $needle);
    }
}