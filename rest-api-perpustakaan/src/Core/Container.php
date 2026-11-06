<?php

namespace App\Core;

class Container
{
    private array $bindings = [];

    public function bind(string $abstract, callable $factory): void
    {
        $this->bindings[$abstract] = $factory;
    }

    public function make(string $abstract): mixed
    {
        if (isset($this->bindings[$abstract])) {
            return ($this->bindings[$abstract])($this);
        }

        if (class_exists($abstract)) {
            return new $abstract();
        }

        throw new \RuntimeException("Tidak dapat membuat: $abstract");
    }
}

