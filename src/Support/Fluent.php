<?php

namespace Gabriel\FluentData\Support;

abstract class Fluent
{
    public static function make(...$args): static
    {
        return new static(...$args);
    }
}