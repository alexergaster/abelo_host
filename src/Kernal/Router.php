<?php

namespace App\Kernal;

class Router
{
    private array $routes = [];
    private string $prefix = '';

    public function get(string $path, callable|string $handler): void
    {
        $this->add('GET', $path, $handler);
    }

    public function post(string $path, callable|string $handler): void
    {
        $this->add('POST', $path, $handler);
    }

    private function add(string $method, string $path, callable|string $handler): void
    {
        $fullPath = '/' . trim($this->prefix . '/' . trim($path, '/'), '/');

        $this->routes[$method][] = ['path' => $fullPath, 'handler' => $handler];
    }

    public function group(string $prefix, callable $callback): void
    {
        $previousPrefix = $this->prefix;

        $this->prefix = '/' . trim($this->prefix . '/' . trim($prefix, '/'), '/');

        try {
            $callback($this);
        } finally {
            $this->prefix = $previousPrefix;
        }
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';
        $path = rawurldecode($path);

        foreach ($this->routes[$method] ?? [] as $route) {
            $pattern = preg_replace(
                '/\{([a-zA-Z_][a-zA-Z0-9_]*)}/',
                '(?P<$1>[^/]+)',
                $route['path']
            );

            if (!preg_match('#^' . $pattern . '$#D', $path, $matches)) {
                continue;
            }

            $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

            $handler = $this->resolveHandler($route['handler']);

            call_user_func_array($handler, array_values($params));

            return;
        }

        http_response_code(404);
        echo 'Page not found';
    }

    public function load(string $file, string $prefix = ''): void
    {
        $this->group($prefix, function () use ($file): void {
            $router = $this;

            require $file;
        });
    }

    private function resolveHandler(callable|string $handler): callable
    {
        if (is_string($handler) && class_exists($handler)) {
            $handler = new $handler();
        }

        if (!is_callable($handler)) {
            throw new \RuntimeException('Invalid route handler');
        }

        return $handler;
    }
}