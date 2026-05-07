<?php

namespace Gabriel\FluentData\Pipes;

class UpperStrings
{
    public function handle(array $data, mixed $next)
    {
        $data['name'] = strtoupper($data['name']);

        return $next($data);
    }
}