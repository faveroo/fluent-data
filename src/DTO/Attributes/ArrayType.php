<?php

namespace Gabriel\FluentData\DTO\Attributes;

use Attribute;
use Gabriel\FluentData\Contracts\Ruler;
use Gabriel\FluentData\Validation\Rule;
use Gabriel\FluentData\Validation\Rules\ArrayType as ArrayTypeRule;

#[Attribute]
class ArrayType implements Ruler
{
    public function __construct(protected ?string $type = null) {}
    public function rule(): Rule
    {
        return new ArrayTypeRule($this->type);
    }
}