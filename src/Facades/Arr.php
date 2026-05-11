<?php

namespace Gabriel\FluentData\Facades;

class Arr extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Gabriel\FluentData\Support\Arr::class;
    }
}
