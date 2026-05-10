<?php

namespace Gabriel\FluentData\Facades;

class Route extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Gabriel\FluentData\Routing\Router::class;
    }
}