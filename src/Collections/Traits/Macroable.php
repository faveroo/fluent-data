<?php

namespace Gabriel\FluentData\Collections\Traits;

use BadMethodCallException;
use Closure;

trait Macroable
{
    protected static array $macros = [];

    public static function macro(string $name, Closure $macro): void
    {
        static::$macros[$name] = $macro;
    }

    public function __call(string $method, array $args)
    {
        if (! isset(static::$macros[$method])) {
            throw new BadMethodCallException(
                "Method {$method} does not exist."
            );
        }

        return static::$macros[$method]
            ->bindTo($this, static::class)(...$args);
    }
}
