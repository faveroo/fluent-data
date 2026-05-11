<?php

namespace Gabriel\FluentData\DTO\Data;

use Exception;
use Gabriel\FluentData\DTO\Attributes\Email;
use ReflectionClass;
use ReflectionProperty;
use Gabriel\FluentData\DTO\Attributes\Required;
use JsonSerializable;

abstract class Data implements JsonSerializable
{
    protected array $masked = [];

    protected array $show = [];

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

    public function masked(array $fields): static
    {
        $this->masked = $fields;

        return $this;
    }

    public function show(array $fields): static
    {
        $this->show = $fields;

        return $this;
    }

    public function toArray(): array
    {
        $attributes = get_object_vars($this);

        unset(
            $attributes['masked'],
            $attributes['show']
        );

        if (!empty($this->show)) {
            $attributes = array_intersect_key(
                $attributes,
                array_flip($this->show)
            );
        }

        if (!empty($this->masked)) {
            $attributes = array_diff_key(
                $attributes,
                array_flip($this->masked)
            );
        }

        return $attributes;
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }

    public function toJson(
        int $options = JSON_PRETTY_PRINT
    ): string {
        return json_encode(
            $this->jsonSerialize(),
            $options
        );
    }
}