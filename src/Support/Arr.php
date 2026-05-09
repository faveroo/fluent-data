<?php

namespace Gabriel\FluentData\Support;

use Composer\Autoload\ClassLoader;

class Arr
{
    public function first(array $array): mixed
    {
        return array_first($array);
    }

    public function last(array $array): mixed
    {
        return array_last($array);
    }

    public function contains(array $array, mixed $value): bool
    {
        return in_array($value, $array);
    }

    public function filter(array $array, callable $callback): array
    {
        return array_filter($array, $callback);
    }
}