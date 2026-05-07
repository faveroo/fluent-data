<?php

namespace Gabriel\FluentData\Pipeline;

class Pipeline
{
    protected mixed $passable;

    protected array $pipes = [];

    public static function make(mixed $passable): static
    {
        return new static($passable);
    }

    public function __construct(mixed $passable)
    {
        $this->passable = $passable;
    }

    public function through(array $pipes): static
    {
        $this->pipes = $pipes;
        var_dump($this);
        return $this;
    }

    public function then(callable $destination): mixed
    {
        $pipeline = array_reduce(
            array_reverse($this->pipes),
            fn ($stack, $pipe) =>
                fn ($passable) =>
                    (new $pipe)->handle($passable, $stack),
            $destination
        );

        return $pipeline($this->passable);
    }
}