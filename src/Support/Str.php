<?php

namespace Gabriel\FluentData\Support;

class Str
{
    public function slug(string $value): string
    {
        return strtolower(
            preg_replace('/[^a-zA-Z0-9]+/', '-', trim($value))
        );
    }

    public function studly(string $value): string
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

    public function camel(string $value): string
    {
        return lcfirst(
            static::studly($value)
        );
    }

    public function snake(string $value): string
    {
        $value = trim($value); // remove espaço extra
        $value = preg_replace('/[\s-]+/', '_', $value); // espaço e hífen viram underscore
        $value = preg_replace('/(.)(?=[A-Z])/u', '$1_', $value); // underscore antes de letras maiúsculas
        $value = preg_replace('/_+/', '_', $value); // remove underscore duplicados
        return strtolower($value);
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

    public function ascii(
        string $str 
    ): array
    {
        return array_values(unpack("C*", $str));
    } 
}
