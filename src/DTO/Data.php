<?php

namespace Gabriel\FluentData\DTO;

use Exception;
use Gabriel\FluentData\DTO\Attributes\Email;
use ReflectionClass;
use ReflectionProperty;
use Gabriel\FluentData\DTO\Attributes\Required;

abstract class Data
{
    public static function fromArray(array $data): static
    {
        $instance = new static;

        $reflection = new ReflectionClass($instance);

        foreach($reflection->getProperties() as $property) {
            static::validate($property, $data);
            
            $name = $property->getName();

            if(array_key_exists($name, $data)) {
                $property->setValue($instance, $data[$name]);
            }
        }

        return $instance;
    }

    public static function validate(
        ReflectionProperty $property,
        array $data
    ): void {
        $required = $property->getAttributes(Required::class);
        $email = $property->getAttributes(Email::class);

        if($required && !array_key_exists(
            $property->getName(),
            $data
        )) {
            throw new Exception(
                "{$property->getName()} is required"
            );
        }

        if($email && array_key_exists(
            $property->getName(),
            $data
        ) && !filter_var(
            $data[$property->getName()],
            FILTER_VALIDATE_EMAIL
        )) {
            throw new Exception(
                "{$property->getName()} must be a valid email"
            );
        }
    }
}