<?php

namespace Gabriel\FluentData\Pipeline\Pipes;

class TrimName
{
    public function handle(array $payload, callable $next): mixed
    {
        $payload['name'] = trim($payload['name']);

        return $next($payload);
    }
}
