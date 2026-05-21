<?php

namespace Gabriel\FluentData\Support;

class Str
{
    public function slug(string $value): string
    {
        return strtolower(
            preg_replace('/[^\p{L}\p{N}]+/u', '-', trim($value))
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
        $value = trim($value);
        $value = preg_replace('/[\s-]+/', '_', $value);
        $value = preg_replace('/(.)(?=[A-Z])/u', '$1_', $value);
        $value = preg_replace('/_+/', '_', $value);
        return strtolower($value);
    }

    public function kebab(string $value): string
    {
        $value = trim($value);

        $value = preg_replace('/([a-z0-9])([A-Z])/', '$1-$2', $value);
        $value = preg_replace('/[\s_\-]+/', '-', $value);

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
    ): array {
        return array_values(unpack("C*", $str));
    }

    public function randomize(
        string $str
    ): string {
        return str_shuffle($str);
    }

    public function binary(
        string $str
    ): string {
        $chars = str_split($str);
        $binary = [];

        foreach ($chars as $char) {
            $binary[] = str_pad(decbin(ord($char)), 8, '0', STR_PAD_LEFT);
        }

        return implode(' ', $binary);
    }

    public function limit(
        string $str,
        int $limit
    ): string {
        return substr($str, 0, $limit);
    }

    function before(string $string, string $search): string
    {
        $position = strpos($string, $search);

        return $position === false
            ? $string
            : substr($string, 0, $position);
    }

    function after(string $string, string $search): string
    {
        $position = strpos($string, $search);

        return $position === false
            ? $string
            : substr($string, $position + strlen($search));
    }

    function between(string $string, string $start, string $end): string
    {
        $startPos = strpos($string, $start);
        
        if ($startPos === false) {
            return '';
        }

        $startPos += strlen($start);
        $endPos = strpos($string, $end, $startPos);

        if ($endPos === false) {
            return '';
        }

        return substr($string, $startPos, $endPos - $startPos);
    }

    function has(string $string, string $search, int $flag = 0): bool
    {
        if($flag === 1) {
            return stripos($string, $search) !== false;
        }

        return strpos($string, $search) !== false;

    }

    public function replace(
        string|array $search,
        string|array $replace,
        string|array $subject
    ): string|array {
        return str_replace($search, $replace, $subject);
    }

    public function uuid(): string
    {
        $bytes = random_bytes(16);

        $bytes[6] = chr((ord($bytes[6]) & 0x0f) | 0x40);
        $bytes[8] = chr((ord($bytes[8]) & 0x3f) | 0x80);

        return vsprintf(
            '%s%s-%s-%s-%s-%s%s%s',
            str_split(bin2hex($bytes), 4)
        );
    }
}
