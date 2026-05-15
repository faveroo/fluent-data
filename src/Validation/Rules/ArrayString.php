<?php

namespace Gabriel\FluentData\Validation\Rules;

use Gabriel\FluentData\Validation\Rule;

class ArrayString implements Rule
{
    public function passes(string $field, mixed $value): bool
    {
        if(is_null($value) || empty($value) || !(is_array($value))) {
            return true;
        }
        return $this->allStrings($value);
    }

    private function allStrings(array $array): bool
    {
        foreach ($array as $item) {
            if (is_array($item)) {
                if (!$this->allStrings($item)) {
                    return false;
                }
            } elseif (!is_string($item)) {
                return false;
            }
        }

        return true;
    }

    public function message(string $field): string
    {
        return "O campo {$field} deve ser um array de string";
    }
}