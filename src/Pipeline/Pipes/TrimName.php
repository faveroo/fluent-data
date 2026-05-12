<?php

namespace Gabriel\FluentData\Pipeline\Pipes;

use Gabriel\FluentData\Contracts\Pipe;

class TrimName implements Pipe
{
    public function handle(mixed $payload, callable $next): mixed
    {
        $payload['name'] = trim($payload['name']);

        return $next($payload);
    }
}
