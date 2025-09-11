<?php

namespace Spanvel\Package;

class Bootstrapper
{
    public function __construct(protected string $basePath)
    {
        $this->basePath = \dirname(\rtrim($basePath, '/\\'));
    }

    public function webRoutes(string $filename = 'web.php', array $group = []): static
    {
        return $this;
    }

    public function apiRoutes(string $filename = 'api.php', array $group = []): static
    {
        return $this;
    }
}
