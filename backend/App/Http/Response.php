<?php

declare(strict_types=1);

namespace App\Http;

/**
 * HTTP response with JSON / HTML factories.
 */
class Response
{
    private int $httpCode;
    /** @var array<string,string> */
    private array $headers = [];
    private string $contentType;
    private mixed $content;

    public function __construct(int $httpCode, mixed $content, string $contentType = 'text/html')
    {
        $this->httpCode = $httpCode;
        $this->content  = $content;
        $this->setContentType($contentType);
    }

    public static function json(mixed $data, int $code = 200): self
    {
        return new self($code, $data, 'application/json');
    }

    public static function html(string $html, int $code = 200): self
    {
        return new self($code, $html, 'text/html');
    }

    public static function error(string $message, int $code = 500): self
    {
        $code = ($code >= 400 && $code < 600) ? $code : 500;

        return new self($code, ['error' => $message], 'application/json');
    }

    public function setContentType(string $contentType): void
    {
        $this->contentType = $contentType;
        $this->addHeader('Content-Type', $contentType . (str_contains($contentType, 'charset') ? '' : '; charset=utf-8'));
    }

    public function addHeader(string $key, string $value): void
    {
        $this->headers[$key] = $value;
    }

    public function getCode(): int
    {
        return $this->httpCode;
    }

    private function sendHeaders(): void
    {
        if (headers_sent()) {
            return;
        }

        http_response_code($this->httpCode);
        foreach ($this->headers as $key => $value) {
            header($key . ': ' . $value);
        }
    }

    public function sendResponse(): void
    {
        $this->sendHeaders();

        if ($this->contentType === 'application/json' || str_starts_with($this->contentType, 'application/json')) {
            echo json_encode($this->content, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            exit;
        }

        if ($this->contentType === 'text/html' || str_starts_with($this->contentType, 'text/html')) {
            echo (string) $this->content;
            exit;
        }

        echo 'Something went wrong!';
        exit;
    }
}
