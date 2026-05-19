<?php

namespace Gabriel\FluentData\Validation;

use Exception;

class ValidationException extends Exception
{
    public function __construct(
        protected array $errors
    ) {
        parent::__construct('Validation was failed.');
    }

    public function errors(): array
    {
        return $this->errors;
    }
}
