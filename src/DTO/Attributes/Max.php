<?php

namespace Gabriel\FluentData\DTO\Attributes;

use Attribute;
use Gabriel\FluentData\Contracts\Ruler;
use Gabriel\FluentData\Validation\Rule;
use Gabriel\FluentData\Validation\Rules\Max as MaxRule;

#[Attribute]
class Max implements Ruler
{
    public function __construct(public int $value)
    {
    }

    public function rule(): Rule
    {
        return new MaxRule($this->value);
    }
}
