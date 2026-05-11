<?php

use Gabriel\FluentData\Collections\Collection;

if(!function_exists('collect')) {
    function collect(array $items): Collection
    {
        return new Collection($items);
    }
}

if(!function_exists('dd')) {
    function dd(mixed ...$vars): never
    {
        var_dump(...$vars);

        die();
    }
}

if(!function_exists('tap')) {
    function tap(
        mixed $value,
        callable $callback
    ): mixed {

        $callback($value);

        return $value;
    }
}

if(!function_exists('value')) {
    function value(mixed $value): mixed 
    {
        return $value instanceof Closure
            ? $value()
            : $value;
    }
}

if(!function_exists('env')) {
    function env(string $key, mixed $dafault = null): mixed
    {
        $value = $_ENV[$key] ?? getenv($key);

        return $value !== false && $value !== null
            ? $value
            : value($dafault);
    }
}

if(!function_exists('load_env')) {
    function load_env(string $path): void
    {
        if(!file_exists($path)) {
            return;
        }

        foreach(file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            $line = trim($line);
            
            if($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            [$key, $value] = array_pad(explode('=', $line, 2), 2, '');


            $key = trim($key);
            $value = trim($value);

            $_ENV[$key] = $value;
            putenv("{$key}={$value}");
        }
    }
}