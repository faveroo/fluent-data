<?php

namespace Gabriel\FluentData\Facades;

use Gabriel\FluentData\Container\Container;

abstract class Facade
{
    protected static array $resolvedInstances = [];

    protected static Container $container;

    public static function setContainer(
        Container $container
    ): void {
        static::$container = $container;
    }

    protected static function resolveInstance(): mixed
    {
        $accessor = static::getFacadeAccessor();

        if (
            isset(static::$resolvedInstances[$accessor])
        ) {
            return static::$resolvedInstances[$accessor];
        }

        return static::$resolvedInstances[$accessor]
        = static::$container->make($accessor);
    }

    abstract protected static function getFacadeAccessor(): string;

    public static function __callStatic(
        string $method,
        array $args    
    ): mixed {
        $instance = static::resolveInstance();

        return $instance->$method(...$args);
    }
}