<?php

namespace Src\Services;

class Router
{
    private $routes = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'DELETE' => []
    ];

    public function get($route, $action) {
        $this->routes['GET'][$route] = $action;
    }

    public function post($route, $action) {
        $this->routes['POST'][$route] = $action;
    }

    public function put($route, $action) {
        $this->routes['PUT'][$route] = $action;
    }

    public function delete($route, $action) {
        $this->routes['DELETE'][$route] = $action;
    }

    public function resolve($uri, $method)
    {
        if (isset($this->routes[$method][$uri])) {
            $this->executeAction($this->routes[$method][$uri], []);
            return;
        }

        foreach ($this->routes[$method] as $route => $controllerAction) {
            $routePattern = preg_replace('/\{[^}]+\}/', '([^/]+)', $route);
            $routePattern = "#^" . $routePattern . "$#";

            if (preg_match($routePattern, $uri, $matches)) {
                array_shift($matches); // Удаляем полный совпадающий элемент
                $this->executeAction($controllerAction, $matches);
                return;
            }
        }

        http_response_code(404);
        echo json_encode(['error' => 'Route not found']);
    }

    protected function executeAction($controllerAction, $params)
    {
        $controllerParts = explode('@', $controllerAction);
        $controllerName = "Src\\Controllers\\" . $controllerParts[0];
        $methodName = $controllerParts[1];

        $controller = new $controllerName();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'PUT') {
            $data = json_decode(file_get_contents('php://input'), true);
            if (!$data) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid JSON']);
                return;
            }
            $controller->$methodName($data, ...$params);
        } else {
            $controller->$methodName(...$params);
        }
    }
}
