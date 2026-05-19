<?php

namespace Gabriel\FluentData\Validation\Rules;

use Gabriel\FluentData\Validation\Rule;

class ArrayType implements Rule
{
    public function __construct(protected ?string $type = null)
    {
    }

    public function passes(string $field, mixed $value): bool
    {
        if (is_null($value)) {
            return true;
        }

        if (! is_array($value)) {
            return false;
        }

        if ($this->type === null || $value === []) {
            return true;
        }

        return $this->allArray($value, $this->type);
    }

    private function allArray(array $array, ?string $type): bool
    {
        $type = mb_strtolower($type);
        $method = "is_{$type}";

        foreach ($array as $item) {
            if (is_array($item)) {
                if (! $this->allArray($item, $type)) {
                    return false;
                }
            } elseif (! $method($item)) {
                return false;
            }
        }

        return true;
    }

    public function message(string $field): string
    {
        return "The {$field} field must be an array of {$this->type}.";
    }
}
