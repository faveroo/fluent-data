<?php

namespace Gabriel\FluentData\Validation;

interface Rule
{
    public function passes(string $field, mixed $value): bool;

    public function message(string $field): string;
}