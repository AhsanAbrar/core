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
    protected string $host;

    /**
     * The development server port.
     */
    protected int $port;

    /**
     * Handle the invocation of the class.
     */
    public function __invoke(string $package, string $entry = 'main.ts'): HtmlString
    {
        $this->package = $package;
        $this->entry = $entry;

        if ($this->isRunningHot()) {
            return $this->renderDevTags();
        }

        return $this->renderProdTags();
    }

    /**
     * Determine if the HMR server is running.
     */
    protected function renderDevTags(): HtmlString
    {
        return new HtmlString(
            sprintf('<script type="module" src="//%s:%s/@vite/client"></script>', $this->ip, $this->port).
            sprintf('<script type="module" src="//%s:%s/resources/js/%s"></script>', $this->ip, $this->port, $this->file)
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
     * Determine if the HMR server is running.
     */
    protected function isRunningHot(): bool
    {
        return file_exists(base_path("packages/{$this->package}/hot"));
    }
}
