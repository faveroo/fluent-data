<?php

namespace Gabriel\FluentData\DTO\Attributes;

use Attribute;
use Gabriel\FluentData\Contracts\Ruler;
use Gabriel\FluentData\Validation\Rule;

#[Attribute]
class ArrayString implements Ruler
{
    public function rule(): Rule
    {
        return new \Gabriel\FluentData\Validation\Rules\ArrayString;
    }
}