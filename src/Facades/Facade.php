<?php

namespace Gabriel\FluentData\Facades;

use Gabriel\FluentData\Container\Container;

abstract class Facade
{
    protected static Container $container;

    public static function setContainer(
        Container $container
    ): void {
        static::$container = $container;
    }

    protected static function resolveInstance(): mixed
    {
        return static::$container->make(
            static::getFacadeAccessor()
        );
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