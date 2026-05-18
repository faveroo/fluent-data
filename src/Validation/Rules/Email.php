<?php

namespace Gabriel\FluentData\Validation\Rules;

use Gabriel\FluentData\Validation\Rule;

class Email implements Rule
{
    public function passes(string $field, mixed $value): bool
    {
        if (is_null($value)) {
            return true;
        }

        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    public function message(string $field): string
    {
        return "The {$field} field must be a valid email address.";
    }
}
