<?php

namespace Gabriel\FluentData\DTO\Data;

use Gabriel\FluentData\Contracts\Arrayable;
use Gabriel\FluentData\Contracts\Jsonable;
use Gabriel\FluentData\Validation\ValidationException;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionProperty;

/**
 * @phpstan-consistent-constructor
 */
abstract class Data implements Arrayable, Jsonable
{
    protected array $masked = [];

    protected array $only = [];

    public static function fromArray(array $data): static
    {
        static::validate($data);

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

            $name = $property->getName();

            if (array_key_exists($name, $data)) {
                $value = static::hydratePropertyValue(
                    $property,
                    $data[$name]
                );

                $property->setValue(
                    $instance,
                    $value
                );
            }
        }

        return $instance;
    }

    public static function validate(array $data): void
    {
        $reflection = new ReflectionClass(static::class);

        $errors = [];

        foreach ($reflection->getProperties() as $property) {

            if ($property->isStatic()) {
                continue;
            }

            $field = $property->getName();

            $value = $data[$field] ?? null;

            foreach ($property->getAttributes() as $attribute) {

                $instance = $attribute->newInstance();

                if (! method_exists($instance, 'rule')) {
                    continue;
                }

                $rule = $instance->rule();

                if (! $rule->passes($field, $value)) {
                    $errors[$field][] = $rule->message($field);
                }
            }
        }

        if (! empty($errors)) {
            throw new ValidationException($errors);
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
        $attributes = array_map(
            fn (mixed $value) => $this->normalizeArrayValue($value),
            $attributes
        );

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

    public function toJson(
        int $options = JSON_PRETTY_PRINT
    ): string {
        return json_encode(
            $this->toArray(),
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

    protected static function propertyDataClass(
        ReflectionProperty $property,
    ): ?string {
        $type = $property->getType();

        if (! $type instanceof ReflectionNamedType) {
            return null;
        }

        if ($type->isBuiltin()) {
            return null;
        }

        $className = $type->getName();

        if (is_subclass_of($className, self::class)) {
            return $className;
        }

        return null;
    }

    protected static function hydratePropertyValue(
        ReflectionProperty $property,
        mixed $value
    ): mixed {
        $dataClass = static::propertyDataClass($property);

        if($dataClass !== null && is_array($value)) {
            return $dataClass::fromArray($value);
        }

        return $value;
    }

    protected function normalizeArrayValue(mixed $value): mixed
    {
        if ($value instanceof Arrayable) {
            return $value->toArray();
        }

        if (is_array($value)) {
            return array_map(
                fn (mixed $item) => $this->normalizeArrayValue($item),
                $value
            );
        }

        return $value;
    }
}
