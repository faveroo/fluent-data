<?php

namespace Gabriel\FluentData\Validation\Rules;

use Gabriel\FluentData\Validation\Rule;

class StringType implements Rule
{
    public function passes(string $field, mixed $value): bool
    {
        if (is_null($value)) {
            return true;
        }

        return is_string($value);
    }

    public function message(string $field): string
    {
        return "The {$field} field must be a string.";
    }
}
