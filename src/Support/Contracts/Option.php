<?php

namespace Spanvel\Support\Contracts;

interface Option
{
    /**
     * Get the value of an option by its key.
     */
    public function get(string $key, mixed $default = null): mixed;

    /**
     * Create or update an option's value by its key.
     */
    public function put(string|array $key, mixed $value = null): bool;
}
