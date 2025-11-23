<?php

namespace Spanvel\Support;

// use Exception;
// use Illuminate\Foundation\ViteManifestNotFoundException;
use Illuminate\Support\HtmlString;

class Vite
{
    /**
     * The name of the spanvel package.
     */
    protected string $package;

    /**
     * The main file processed by Vite
     */
    protected string $entry;

    /**
     * The development server hostname.
     */
    protected string $host = 'localhost';

    /**
     * The development server port.
     */
    protected int $port = 5173;

    /**
     * Handle the invocation of the class.
     */
    public function __invoke(string $package, string $entry = 'main.ts'): HtmlString
    {
        $this->package = $package;
        $this->entry = $entry;

        if ($this->isRunningHot()) {
            $this->setHostAndPort();

            return $this->renderDevTags();
        }

        return $this->renderProdTags();
    }

    /**
     * Determine if the HMR server is running.
     */
    protected function setHostAndPort(): void
    {
        $contents = @file_get_contents($this->hotFilePath());

        if ($contents === false) {
            return;
        }

        foreach (preg_split('/\r\n|\r|\n/', $contents) as $line) {
            if (! str_contains($line, ':')) {
                continue;
            }

            [$key, $value] = array_map('trim', explode(':', $line, 2));

            if ($key === 'ip' && $value !== '') {
                $this->host = $value;
            }

            if ($key === 'port' && is_numeric($value)) {
                $this->port = (int) $value;
            }
        }
    }

    /**
     * Determine if the HMR server is running.
     */
    protected function renderDevTags(): HtmlString
    {
        return new HtmlString(
            sprintf('<script type="module" src="//%s:%s/@vite/client"></script>', $this->host, $this->port).
            sprintf('<script type="module" src="//%s:%s/resources/js/%s"></script>', $this->host, $this->port, $this->entry)
        );
    }

    /**
     * Determine if the HMR server is running.
     */
    protected function renderProdTags(): HtmlString
    {
        return new HtmlString(
            sprintf('<script type="module" src="//%s:%s/@vite/client"></script>', $this->ip, $this->port).
            sprintf('<script type="module" src="//%s:%s/resources/js/%s"></script>', $this->ip, $this->port, $this->file)
        );
    }

    /**
     * Get the path to the hot file for this package.
     */
    protected function hotFilePath(): string
    {
        return base_path("packages/{$this->package}/hot");
    }

    /**
     * Determine if the HMR server is running.
     */
    protected function isRunningHot(): bool
    {
        return is_file($this->hotFilePath());
    }
}
