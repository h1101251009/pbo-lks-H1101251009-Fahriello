<?php

namespace App\Core;

class Request
{
    private string $method;
    private string $path;
    private array $query;
    private array $body;

    public function __construct()
    {
        $this->method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        $this->path   = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
        $this->query  = $_GET;

        $raw  = file_get_contents('php://input');
        $json = json_decode($raw ?: '', true);
        $this->body = is_array($json) ? $json : $_POST;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getQuery(): array
    {
        return $this->query;
    }

    public function getBody(): array
    {
        return $this->body;
    }
}

