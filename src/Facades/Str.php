<?php

namespace Gabriel\FluentData\Facades;

class Str extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Gabriel\FluentData\Support\Str::class;
    }
}
