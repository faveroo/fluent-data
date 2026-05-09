<?php

namespace Gabriel\FluentData\Facades;

class DB extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Gabriel\FluentData\Database\Database::class;
    }
}
