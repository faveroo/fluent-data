<?php

namespace Gabriel\FluentData\Routing;

use Closure;

class Router
{
    protected array $routes = [];

    public function get(string $url, Closure|array|string $action): void
    {
        $this->add('GET', $url, $action);
    }

     public function post(string $uri, Closure|array|string $action): void
    {
        $this->add('POST', $uri, $action);
    }

    public function patch(string $uri, Closure|array|string $action): void
    {
        $this->add('PATCH', $uri, $action);
    }

    public function put(string $uri, Closure|array|string $action): void
    {
        $this->add('PUT', $uri, $action);
    }

    public function delete(string $uri, Closure|array|string $action): void
    {
        $this->add('DELETE', $uri, $action);
    }

    protected function add(string $method, string $uri, Closure|array|string $action): void
    {
        $this->routes[$method][$this->normalizeUri($uri)] = $action;
    }

    public function dispatch(string $method, string $uri): mixed
    {
        $uri = $this->normalizeUri(parse_url($uri, PHP_URL_PATH) ?: '/');

        $action = $this->routes[$method][$uri] ?? null;

        if (!$action) {
            http_response_code(404);
            return '404 Not Found';
        }

        if ($action instanceof Closure) {
            return $action();
        }

        if (is_array($action)) {
            [$class, $method] = $action;
            return (new $class())->$method();
        }

        return $action;
    }

    protected function normalizeUri(string $uri): string
    {
        $uri = '/' . trim($uri, '/');

        return $uri === '/' ? '/' : rtrim($uri, '/');
    }
}