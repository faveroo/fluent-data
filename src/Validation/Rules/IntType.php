<?php

namespace Gabriel\FluentData\Validation\Rules;

use Gabriel\FluentData\Validation\Rule;

class IntType implements Rule
{
    public function passes(string $field, mixed $value): bool
    {
        if(is_null($value)) {
            return true;
        }

        return is_int($value);
    }

    public function message(string $field): string
    {
        return "O campo {$field} deve ser do tipo int.";
    }
}