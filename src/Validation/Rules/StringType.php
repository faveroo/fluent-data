<?php

namespace Gabriel\FluentData\Validation\Rules;

use Gabriel\FluentData\Validation\Rule;

class StringType implements Rule
{
    public function passes(string $field, mixed $value): bool
    {
        if(is_null($value)) {
            return true;
        }

        return is_string($value);
    }

    public function message(string $field): string
    {
        return "O campo {$field} deve ser do tipo string.";
    }
}