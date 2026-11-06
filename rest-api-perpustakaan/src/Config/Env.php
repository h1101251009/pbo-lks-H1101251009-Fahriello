<?php

namespace App\Config;

class Env
{
    public static function load(string $path): void
    {
        if (!file_exists($path)) {
            throw new \RuntimeException(".env tidak ditemukan: $path");
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            $line = trim($line);

            if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
                continue;
            }

            [$key, $value] = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value, " \t\n\r\0\x0B\"'");

            $_ENV[$key] = $value;
            putenv("$key=$value");
        }
    }

    public static function get(string $key, ?string $default = null): ?string
    {
        return $_ENV[$key] ?? $default;
    }
}