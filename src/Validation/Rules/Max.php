<?php

namespace Gabriel\FluentData\Validation\Rules;

use Gabriel\FluentData\Validation\Rule;

class Max implements Rule
{
    public function __construct(protected int $length)
    {
    }

    public function passes(string $field, mixed $value): bool
    {
        if (is_null($value)) {
            return true;
        }

        if (is_array($value)) {
            return count($value) <= $this->length;
        }

        if (is_numeric($value)) {
            return $value <= $this->length;
        }

        if (is_string($value)) {
            return mb_strlen($value) <= $this->length;
        }

        return false;
    }

    public function message(string $field): string
    {
        return "The {$field} field must not be greater than {$this->length} characters.";
    }
}
