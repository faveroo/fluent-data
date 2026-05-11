<?php

namespace Gabriel\FluentData\Pipeline\Pipes;

class UpperName
{
    public function handle(array $payload, callable $next): mixed
    {
        $payload['name'] = strtoupper($payload['name']);

        return $next($payload);
    }
}
