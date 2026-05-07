<?php

namespace Gabriel\FluentData\DTO;

use Exception;
use ReflectionClass;
use ReflectionProperty;
use Gabriel\FluentData\DTO\Attributes\Required;

abstract class Data
{
    public function fromArray(array $data): static
    {
        $instance = new static;

        $reflection = new ReflectionClass($instance);

        foreach($reflection->getProperties() as $property) {
            static::validateRequired($property, $data);
            
            $name = $property->getName();

            if(array_key_exists($name, $data)) {
                $property->setValue($instance, $data[$name]);
            }
        }

        return $instance;
    }

    public static function validateRequired(
        ReflectionProperty $property,
        array $data
    ): void {
        $required = $property->getAttributes(Required::class);

        if($required && !array_key_exists(
            $property->getName(),
            $data
        )) {
            throw new Exception(
                "{$property->getName()} is required"
            );
        }
    }
}