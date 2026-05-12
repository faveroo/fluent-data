<?php

namespace Gabriel\FluentData\Pipeline\Pipes;

use Gabriel\FluentData\Contracts\Pipe;

class UpperName implements Pipe
{
    public function handle(mixed $payload, callable $next): mixed
    {
        $payload['name'] = strtoupper($payload['name']);

        return $next($payload);
    }
}
