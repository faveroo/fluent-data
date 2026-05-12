<?php

namespace Gabriel\FluentData\Validation\Rules;

use Gabriel\FluentData\Validation\Rule;

class Required implements Rule
{
    public function passes(string $field, mixed $value): bool
    {
        return !is_null($value) &&  $value !== '';   
    }

    public function message(string $field): string
    {
        return "O campo {$field} é obrigatório.";
    }
}