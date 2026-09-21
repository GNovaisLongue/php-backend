<?php

declare(strict_types=1);

namespace App\Http;

use Closure;
use Exception;
use ReflectionFunction;

/**
 * Minimal front-controller router.
 *
 * Fixes vs. previous version:
 *  - matches on path only (query string stripped)
 *  - trailing-slash tolerant, urldecoded
 *  - `{param}` compiles to `([^/]+)` (no cross-segment greedy match)
 *  - safe array_combine (count-mismatch can no longer fatal)
 *  - Request is injected by name; route results that are Response
 *    instances (or arrays) are actually sent to the client
 */
class Router
{
    private string $url = '';
    private string $prefix = '';
    /** @var array<string,array<string,array<string,mixed>>> */
    private array $routes = [];
    private Request $request;
    private string $groupPrefix = '';

    public function __construct(string $url, ?Request $request = null)
    {
        $this->request = $request ?? new Request();
        $this->url     = $url;
        $this->setPrefix();
    }

    private function setPrefix(): void
    {
        $parseUrl = parse_url($this->url);
        $this->prefix = is_array($parseUrl) ? ($parseUrl['path'] ?? '') : '';
    }

    /**
     * @param array<int|string,mixed> $params
     */
    private function addRoute(string $method, string $route, array $params = []): void
    {
        $route = $this->groupPrefix . $route;

        foreach ($params as $key => $value) {
            if ($value instanceof Closure) {
                $params['controller'] = $value;
                unset($params[$key]);
                break;
            }
        }

        $params['variables'] = [];

        if (preg_match_all('/\{([^\/}]+)\}/', $route, $matches)) {
            $route = (string) preg_replace('/\{[^\/}]+\}/', '([^/]+)', $route);
            $params['variables'] = $matches[1];
        }

        $validationPattern = '/^' . str_replace('/', '\/', $route) . '$/';

        $this->routes[$validationPattern][$method] = $params;
    }

    public function get(string $route, array $params = []): void
    {
        $this->addRoute('GET', $route, $params);
    }

    public function post(string $route, array $params = []): void
    {
        $this->addRoute('POST', $route, $params);
    }

    public function put(string $route, array $params = []): void
    {
        $this->addRoute('PUT', $route, $params);
    }

    public function patch(string $route, array $params = []): void
    {
        $this->addRoute('PATCH', $route, $params);
    }

    public function delete(string $route, array $params = []): void
    {
        $this->addRoute('DELETE', $route, $params);
    }

    /** Group routes under a common prefix for ordering/readability. */
    public function group(string $prefix, callable $callback): void
    {
        $previous = $this->groupPrefix;
        $this->groupPrefix .= $prefix;
        $callback($this);
        $this->groupPrefix = $previous;
    }

    private function getUri(): string
    {
        $path = $this->request->getPath();

        if ($this->prefix !== '' && str_starts_with($path, $this->prefix)) {
            $path = substr($path, strlen($this->prefix));
        }

        $path = '/' . ltrim(urldecode($path === '' ? '/' : $path), '/');

        // Trailing-slash tolerant (except root).
        if (strlen($path) > 1) {
            $path = rtrim($path, '/');
        }

        return $path === '' ? '/' : $path;
    }

    /** @return array<string,mixed> */
    private function getRoute(): array
    {
        $uri = $this->getUri();
        $httpMethod = $this->request->getHttp();

        foreach ($this->routes as $validationPattern => $methods) {
            if (preg_match($validationPattern, $uri, $matches)) {
                if (isset($methods[$httpMethod])) {
                    unset($matches[0]);
                    $matches = array_values($matches);

                    /** @var list<string> $keys */
                    $keys = $methods[$httpMethod]['variables'] ?? [];
                    $vars = [];
                    foreach ($keys as $i => $name) {
                        $vars[$name] = $matches[$i] ?? null;
                    }
                    $vars['request'] = $this->request;

                    $methods[$httpMethod]['variables'] = $vars;

                    return $methods[$httpMethod];
                }
                throw new Exception('Method not allowed', 405);
            }
        }
        throw new Exception('URL not found', 404);
    }

    public function run(): void
    {
        try {
            $route = $this->getRoute();

            if (!isset($route['controller']) || !$route['controller'] instanceof Closure) {
                throw new Exception('URL can not be processed!', 500);
            }

            $args = [];
            $reflection = new ReflectionFunction($route['controller']);
            foreach ($reflection->getParameters() as $parameter) {
                $name = $parameter->getName();
                if ($name === 'request') {
                    $args[$name] = $this->request;
                    continue;
                }
                if (array_key_exists($name, $route['variables'])) {
                    $args[$name] = $route['variables'][$name];
                    continue;
                }
                $args[$name] = $parameter->isDefaultValueAvailable()
                    ? $parameter->getDefaultValue()
                    : null;
            }

            $result = call_user_func_array($route['controller'], $args);

            if ($result instanceof Response) {
                $result->sendResponse();
                return;
            }
            if (is_array($result)) {
                Response::json($result)->sendResponse();
                return;
            }
            if (is_string($result)) {
                Response::html($result)->sendResponse();
                return;
            }
            if ($result === null) {
                return; // Controller already echoed/sent (legacy views).
            }

            Response::html((string) $result)->sendResponse();
        } catch (Exception $e) {
            $code = (int) $e->getCode();
            if ($code < 400 || $code >= 600) {
                $code = 500;
            }
            // JSON errors for API consistency (legacy plain-text kept for 404 HTML hits is unnecessary).
            Response::error($e->getMessage(), $code)->sendResponse();
        }
    }
}
