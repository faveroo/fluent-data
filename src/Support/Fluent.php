<?php

namespace Gabriel\FluentData\Support;

/**
 * @phpstan-consistent-constructor
 */
abstract class Fluent
{
    public static function make(...$args): static
    {
        return new static(...$args);
    }
}
