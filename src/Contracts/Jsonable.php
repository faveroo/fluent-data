<?php

namespace Gabriel\FluentData\Contracts;

interface Jsonable 
{
    public function toJson(): mixed;
}