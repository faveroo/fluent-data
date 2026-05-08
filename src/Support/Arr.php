<?php

namespace Gabriel\FluentData\Support;

class Arr
{
    public function first(array $array): mixed
    {
        return array_first($array);
    }

    public function last(array $array): mixed
    {
        return array_last($array);
    }
}