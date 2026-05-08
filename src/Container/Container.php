<?php

namespace Gabriel\FluentData\Container;

use Closure;
use Exception;
use ReflectionClass;
use ReflectionParameter;

class Container
{
    protected array $bindings = [];
    protected array $singletons = [];
    protected array $instances = [];

    public function bind(
        string $abstract,
        Closure|string $concrete
    ): void {
        $this->bindings[$abstract] = $concrete;
    }

    public function singleton(
        string $abstract,
        Closure|string $concrete
    ): void {
        $this->singletons[$abstract] = $concrete;
    }

    public function make(string $abstract): mixed 
    {
        if(isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        if(isset($this->singletons[$abstract])) {
            return $this->instances[$abstract]
                = $this->resolve(
                    $this->singletons[$abstract]
                );
        }

        $concrete = $this->bindings[$abstract] 
            ?? $abstract;

        return $this->resolve($concrete);
    }

    public function resolve(
        Closure|string $concrete
    ): mixed {
        if ($concrete instanceof Closure) {
            return $concrete($this);
        }

        $reflection = new ReflectionClass($concrete);

        if(!$reflection->isInstantiable()) {
            throw new Exception("Class {$concrete} is not instantiable.");
        }

        $constructor = $reflection->getConstructor();

        if(!$constructor) {
            return new $concrete;
        }

        $dependecies = array_map(
            fn (ReflectionParameter $param) =>
                $this->resolveDependency($param),
            $constructor->getParameters()
        );

        return new $concrete(...$dependecies);
    }

    protected function resolveDependency(
        ReflectionParameter $parameter
    ): mixed {
        $type = $parameter->getType();

        if(!$type) {
            throw new Exception(
                "Cannot resolve parameter {$parameter->getName()}"
            );
        }

        return $this->make($type->getName());
    }
}