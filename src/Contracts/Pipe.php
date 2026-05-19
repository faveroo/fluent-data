<?php

namespace Gabriel\FluentData\Contracts;

interface Pipe
{
    public function handle(mixed $payload, callable $next): mixed;
}
