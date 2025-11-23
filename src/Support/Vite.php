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
     * The main entry file to be processed by Vite.
     */
    protected string $entry = 'main.ts';

    /**
     * Handle the invocation of the class.
     */
    public function __invoke(string $package, string $entry = 'main.ts'): HtmlString
    {
        if ($this->isRunningHot()) {
            return $this->renderDev();
        }

        return $this->renderProd();
    }
}
