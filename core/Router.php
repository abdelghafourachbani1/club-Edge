<?php


class Router
{
    // Singlton Pattern NOT Implemented Yet
    private array $routes = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'PATCH' => [],
        'DELETE' => [],
        'OPTIONS' => [],
    ];

    private array $middlewareMap = [
        'auth' => \App\Middlewares\AuthMiddleware::class,
        'role' => \App\Middlewares\RoleMiddleware::class,
    ];

    public function get(string $path, $handler): self
    {
        return $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, $handler): self
    {
        return $this->addRoute('POST', $path, $handler);
    }

    public function any(string $path, $handler): self
    {
        foreach (array_keys($this->routes) as $method) {
            $this->addRoute($method, $path, $handler);
        }
        return $this;
    }

    public function middleware(array|string $middleware): self
    {
        // $_SESSION['user'] = ['role' => 'admin', 'email' => 'admin@test.com']; // for testing
        unset($_SESSION['user']);
        $methods = array_keys($this->routes);
        foreach ($methods as $method) {
            if (!empty($this->routes[$method])) {
                $lastIndex = count($this->routes[$method]) - 1;
                $currentMiddleware = (array)$middleware;
                $this->routes[$method][$lastIndex]['middleware'] = array_merge(
                    $this->routes[$method][$lastIndex]['middleware'] ?? [],
                    $currentMiddleware
                );
            }
        }
        return $this;
    }

    public function dispatch(?string $requestUri = null, ?string $method = null): void
    {
        $method = strtoupper($method ?? ($_SERVER['REQUEST_METHOD'] ?? 'GET'));
        $requestUri = $requestUri ?? ($_SERVER['REQUEST_URI'] ?? '/');

        $path = parse_url($requestUri, PHP_URL_PATH) ?? '/';

        $basePath = '';
        if (defined('BASE_URL')) {
            $basePath = parse_url(BASE_URL, PHP_URL_PATH) ?? '';
        }
        if ($basePath !== '') {
            $basePath = '/' . trim($basePath, '/');
            if (strncasecmp($path, $basePath, strlen($basePath)) === 0) {
                $path = substr($path, strlen($basePath));
            }
        }


        if (str_starts_with($path, '/index.php')) {
            $path = substr($path, 10);
        }

        $path = '/' . trim($path, '/');
        if ($path === '//') $path = '/';

        foreach ($this->routes[$method] ?? [] as $route) {
            $params = [];
            if ($this->match($route['path'], $path, $params)) {
                $this->runMiddleware($route['middleware'] ?? [], $params);
                $this->invoke($route['handler'], $params);
                return;
            }
        }

        // 404

        http_response_code(404);

        $viewPath = BASE_PATH . '/App/Views/404.php';
        if (file_exists($viewPath)) {
            include $viewPath;
        } else {
            echo "404 Not Found";
        }
    }

    private function addRoute(string $method, string $path, $handler): self
    {
        $method = strtoupper($method);
        $path = '/' . trim($path, '/');
        if ($path === '//') {
            $path = '/';
        }

        $this->routes[$method][] = [
            'path' => $path,
            'handler' => $handler,
            'middleware' => [],
        ];

        return $this;
    }

    private function match(string $routePath, string $requestPath, array &$params): bool
    {
        if ($routePath === $requestPath) {
            return true;
        }

        $pattern = preg_replace('#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#', '(?P<$1>[^/]+)', $routePath);
        $pattern = '#^' . $pattern . '$#';

        if (!preg_match($pattern, $requestPath, $matches)) {
            return false;
        }

        foreach ($matches as $key => $value) {
            if (is_string($key)) {
                $params[$key] = $value;
            }
        }

        return true;
    }

    private function invoke($handler, array $params): void
    {
        if (is_callable($handler)) {
            call_user_func_array($handler, $params);
            return;
        }

        if (is_string($handler) && str_contains($handler, '@')) {
            [$controllerName, $method] = explode('@', $handler, 2);
            if (!class_exists($controllerName)) {
                throw new RuntimeException("Controller not found: {$controllerName}");
            }
            $controller = new $controllerName();
            if (!method_exists($controller, $method)) {
                throw new RuntimeException("Method not found: {$controllerName}::{$method}");
            }
            call_user_func_array([$controller, $method], $params);
            return;
        }

        throw new InvalidArgumentException('Invalid route handler');
    }

    private function runMiddleware(array $middlewares, array $params): void
    {
        foreach ($middlewares as $middleware) {
            $args = [];
            if (str_contains($middleware, ':')) {
                [$middleware, $argString] = explode(':', $middleware, 2);
                $args = explode(',', $argString);
            }

            $middlewareClass = $this->middlewareMap[$middleware] ?? $middleware;

            if (!class_exists($middlewareClass)) {
                throw new RuntimeException("Middleware class not found: {$middlewareClass}");
            }

            $middlewareInstance = new $middlewareClass();
            if (!$middlewareInstance instanceof MiddlewareInterface) {
                throw new RuntimeException("Middleware {$middlewareClass} must implement MiddlewareInterface");
            }

            if (!empty($args)) {
                $middlewareInstance->handle($params, ...$args);
            } else {
                $middlewareInstance->handle($params);
            }
        }
    }
}
