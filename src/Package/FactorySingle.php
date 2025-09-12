<?php

namespace Spanvel\Package;

class FactorySingle
{
    protected ?string $basePath = null; // do NOT use this on the singleton

    public function boot(string $basePath): self
    {
        $clone = new self; // new, independent instance
        $clone->basePath = \dirname(\rtrim($basePath, '/\\'));

        return $clone;
    }

    public function webRoutes(string $file = 'web.php'): self
    {
        $path = $this->basePath;

        dump($path);

        return $this;
    }
}
