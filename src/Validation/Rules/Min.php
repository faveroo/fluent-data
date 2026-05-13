<?php

namespace Gabriel\FluentData\Validation\Rules;

use Gabriel\FluentData\Validation\Rule;

class Min implements Rule
{
    public function __construct(public int $length) {}

    public function passes(string $field, mixed $value): bool
    {
            if (is_null($value)) {
                return true;
            }

            if (is_string($value)) {
                return mb_strlen(
                            trim((string) $value)
                        ) >= $this->length;
            }

            if (is_array($value)) {
                return count($value) >= $this->length;
            }
            

            if (is_numeric($value)) {
                return $value >= $this->length;
            }


            return false;
    }

    public function message(
        string $field
    ): string {
        return "{$field} deve contar pelo menos {$this->length} caracteres";
    }
}