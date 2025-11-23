<?php

namespace Spanvel\Support;

use Exception;
use Illuminate\Foundation\ViteManifestNotFoundException;
use Illuminate\Support\HtmlString;

class Vite
{
    /**
     * The name of the package.
     */
    protected string $package;

    /**
     * Handle the invocation of the class.
     */
    public function __invoke(string $package, string $entry = 'main.ts'): HtmlString
    {
        $this->package = $package;

        if ($this->isRunningHot()) {
            return $this->renderDevTags();
        }

        return $this->renderProdTags();
    }

    /**
     * Determine if the HMR server is running.
     */
    protected function renderDevTags()
    {
        //
    }

    /**
     * Determine if the HMR server is running.
     */
    protected function renderProdTags()
    {
        //
    }

    /**
     * Determine if the HMR server is running.
     */
    protected function isRunningHot(): bool
    {
        return file_exists(base_path("packages/{$this->package}/hot"));
    }
}
