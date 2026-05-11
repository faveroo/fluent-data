<?php

use Gabriel\FluentData\DTO\Attributes\Email;
use Gabriel\FluentData\DTO\Attributes\Required;
use Gabriel\FluentData\DTO\Data;

require 'vendor/autoload.php';

class UserDTO extends Data
{
    #[Required]
    #[Email]
    protected string $email;
}

$user = UserDTO::fromArray(['nome' => 'gabriel']);