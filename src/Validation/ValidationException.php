<?php

namespace Gabriel\FluentData\Validation;

use Exception;

class ValidationException extends Exception
{
    public function __construct(
        protected array $errors
    )
    {
        parent::__construct('Validação falhou.');
    }

    public function errors(): array
    {
        return $this->errors;
    }
}