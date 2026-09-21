<?php

declare(strict_types=1);

namespace App\Http;

/**
 * Immutable-ish HTTP request value object.
 * Parses query, form and JSON bodies. Works on Apache, CLI and
 * php -S (getallheaders() fallback included).
 */
class Request
{
    private string $httpMethod;
    private string $uri;
    /** @var array<string,mixed> */
    private array $queryParams;
    /** @var array<string,mixed> */
    private array $postVars;
    /** @var array<string,string> */
    private array $headers;
    /** @var array<string,mixed> */
    private array $body;

    /** @param array<string,mixed>|null $body override for tests */
    public function __construct(?array $body = null)
    {
        $this->queryParams = $_GET ?? [];
        $this->postVars    = $_POST ?? [];
        $this->headers     = self::fetchHeaders();
        $this->httpMethod  = strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET'));
        $this->uri         = (string) ($_SERVER['REQUEST_URI'] ?? '/');
        $this->body        = $body ?? $this->parseBody();
    }

    /** @return array<string,string> */
    private static function fetchHeaders(): array
    {
        if (function_exists('getallheaders')) {
            $headers = getallheaders();
            if (is_array($headers)) {
                return $headers;
            }
        }

        // Fallback for php -S / nginx / CLI: rebuild from $_SERVER.
        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (str_starts_with($key, 'HTTP_')) {
                $name = str_replace('_', '-', strtolower(substr($key, 5)));
                $headers[ucwords($name, '-')] = is_array($value) ? implode(',', $value) : (string) $value;
            } elseif ($key === 'CONTENT_TYPE' || $key === 'CONTENT_LENGTH') {
                $name = str_replace('_', '-', strtolower($key));
                $headers[ucwords($name, '-')] = (string) $value;
            }
        }

        return $headers;
    }

    /** @return array<string,mixed> */
    private function parseBody(): array
    {
        $contentType = $this->getHeader('Content-Type', '');
        if (stripos($contentType, 'application/json') !== false) {
            $raw = file_get_contents('php://input');
            if (is_string($raw) && $raw !== '') {
                $decoded = json_decode($raw, true);
                if (is_array($decoded)) {
                    return $decoded;
                }
            }

            return [];
        }

        // Form posts, PUT/PATCH/DELETE urlencoded bodies.
        if (in_array($this->httpMethod, ['POST', 'PUT', 'PATCH', 'DELETE'], true) && $this->postVars !== []) {
            return $this->postVars;
        }

        return $this->postVars;
    }

    public function getHttp(): string
    {
        return $this->httpMethod;
    }

    /** Raw URI including query string. */
    public function getUri(): string
    {
        return $this->uri;
    }

    /** Path without query string or fragment, urldecoded. */
    public function getPath(): string
    {
        $path = parse_url($this->uri, PHP_URL_PATH);
        return is_string($path) && $path !== '' ? $path : '/';
    }

    /** @return array<string,string> */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getHeader(string $name, string $default = ''): string
    {
        foreach ($this->headers as $key => $value) {
            if (strcasecmp($key, $name) === 0) {
                return $value;
            }
        }

        return $default;
    }

    /** @return array<string,mixed> */
    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    /** @return array<string,mixed> */
    public function getPostVars(): array
    {
        return $this->postVars;
    }

    /** @return array<string,mixed> merged body (JSON or form). */
    public function getBody(): array
    {
        return $this->body;
    }

    public function getParam(string $key, mixed $default = null): mixed
    {
        if (array_key_exists($key, $this->body)) {
            return $this->body[$key];
        }
        if (array_key_exists($key, $this->queryParams)) {
            return $this->queryParams[$key];
        }

        return $default;
    }
}
