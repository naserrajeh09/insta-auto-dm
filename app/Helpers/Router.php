<?php
// app/Helpers/Router.php

namespace App\Helpers;

class Router
{
    protected array $routes = [
        'GET' => [],
        'POST' => [],
    ];

    /**
     * تعريف مسار GET
     */
    public function get(string $uri, $action): void
    {
        $this->routes['GET'][$this->normalizeUri($uri)] = $action;
    }

    /**
     * تعريف مسار POST
     */
    public function post(string $uri, $action): void
    {
        $this->routes['POST'][$this->normalizeUri($uri)] = $action;
    }

    /**
     * تنفيذ الراوتر
     */
    public function dispatch(string $uri, string $method): void
    {
        $uri = $this->normalizeUri(parse_url($uri, PHP_URL_PATH) ?? '/');

        $action = $this->routes[$method][$uri] ?? null;

        if (!$action) {
            http_response_code(404);
            echo "404 - Page Not Found";
            return;
        }

        if (is_callable($action)) {
            call_user_func($action);
        } elseif (is_array($action) && count($action) === 2) {
            [$class, $method] = $action;

            if (!class_exists($class)) {
                throw new \Exception("Controller class {$class} not found.");
            }

            $controller = new $class();

            if (!method_exists($controller, $method)) {
                throw new \Exception("Method {$method} not found in controller {$class}.");
            }

            call_user_func([$controller, $method]);
        } else {
            throw new \Exception("Invalid route action for URI: {$uri}");
        }
    }

    /**
     * تنظيف الـ URI (إزالة / من النهاية)
     */
    protected function normalizeUri(string $uri): string
    {
        $uri = rtrim($uri, '/');
        return $uri === '' ? '/' : $uri;
    }
}
