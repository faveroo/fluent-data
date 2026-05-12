<?php

namespace Gabriel\FluentData\DTO\Attributes;

use Attribute;
use Gabriel\FluentData\Validation\Rule;

#[Attribute]
class Email
{
    public function rule(): Rule
    {
        return new \Gabriel\FluentData\Validation\Rules\Email;
    }
}