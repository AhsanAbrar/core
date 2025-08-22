<?php

namespace Spanvel\Package;

class PackageContext
{
    /**
     * The current package key for this request lifecycle.
     */
    protected string $key = '';

    /**
     * Get or set the current package key.
     *
     * When $key is provided, it will be set and returned.
     * When omitted, the current key will be returned.
     */
    public function key(?string $key = null): string
    {
        if ($key !== null) {
            $this->key = $key;
        }

        return $this->key;
    }

    /**
     * Explicit setter for the package key.
     */
    public function setKey(string $key): void
    {
        $this->key = $key;
    }

    /**
     * Reset the package key to the default.
     */
    public function reset(): void
    {
        $this->key = '';
    }
}
