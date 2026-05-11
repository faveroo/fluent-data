<?php

namespace Gabriel\FluentData\Facades;

use BadMethodCallException;
use ReflectionClass;

abstract class Facade
{
    abstract protected static function getFacadeAccessor(): string;

    public static function __callStatic(
        string $method,
        array $args
    ): mixed {
        $accessor = static::getFacadeAccessor();

        $reflection = new $accessor();

        if (!is_callable([$reflection, $method])) {
            throw new BadMethodCallException(
                "Method {$method} does not exist on facade accessor {$accessor}."
            );
        }


        return $reflection->$method(...$args);
    }
}
