<?php

namespace Gabriel\FluentData\Pipes;

class TrimStrings
{
    public function handle(array $data, mixed $next)
    {
        $data['name'] = trim($data['name']);

        return $next($data);
    }
}