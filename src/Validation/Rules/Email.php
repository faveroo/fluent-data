<?php

namespace Gabriel\FluentData\Validation\Rules;

use Gabriel\FluentData\Validation\Rule;

class Email implements Rule
{
    public function passes(string $field, mixed $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;        
    }

    public function message(string $field): string
    {
        return "O campo {$field} deve ser um e-mail válido";
    }
}