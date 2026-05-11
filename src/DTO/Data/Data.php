<?php

namespace Gabriel\FluentData\DTO\Data;

use Exception;
use Gabriel\FluentData\Contracts\Arrayable;
use Gabriel\FluentData\Contracts\Jsonable;
use Gabriel\FluentData\DTO\Attributes\Email;
use Gabriel\FluentData\DTO\Attributes\Required;
use JsonSerializable;
use ReflectionClass;
use ReflectionProperty;

abstract class Data implements Arrayable, Jsonable, JsonSerializable
{
    protected array $masked = [];

    protected array $only = [];

    public static function fromArray(array $data): static
    {
        $instance = new static();
        $reflection = new ReflectionClass($instance);

        foreach ($reflection->getProperties() as $property) {
            if (
                $property->isStatic()
                || in_array(
                    $property->getName(),
                    $instance->internalProperties(),
                    true
                )
            ) {
                continue;
            }

            static::validate($property, $data);

            $name = $property->getName();

            if (array_key_exists($name, $data)) {
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

        if (
            $required
            && !array_key_exists($property->getName(), $data)
        ) {
            throw new Exception(
                "{$property->getName()} is required"
            );
        }

        if (
            $email
            && array_key_exists($property->getName(), $data)
            && !filter_var(
                $data[$property->getName()],
                FILTER_VALIDATE_EMAIL
            )
        ) {
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

    public function only(array $fields): static
    {
        $this->only = $fields;

        return $this;
    }

    public function toArray(): array
    {
        $attributes = get_object_vars($this);

        foreach ($this->internalProperties() as $property) {
            unset($attributes[$property]);
        }

        if ($this->only !== []) {
            $attributes = array_intersect_key(
                $attributes,
                array_flip($this->only)
            );
        }

        if ($this->masked !== []) {
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

    protected function internalProperties(): array
    {
        return [
            'masked',
            'only',
        ];
    }
}
