<?php

namespace Spanvel\Support;

use Illuminate\Foundation\ViteException;
use Illuminate\Support\HtmlString;

class Vite
{
    /**
     * The name of the Spanvel package.
     */
    protected string $package;

    /**
     * The main JavaScript entry file processed by Vite.
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
     * Render the Vite tags for the given package and entry.
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
     * Set the dev server host and port from the hot file.
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
     * Render the tags for development mode (HMR server).
     */
    protected function renderDevTags(): HtmlString
    {
        $host  = "{$this->host}:{$this->port}";
        $entry = "resources/js/{$this->entry}";

        return new HtmlString(
            <<<HTML
<script type="module" src="//{$host}/@vite/client"></script>
<script type="module" src="//{$host}/{$entry}"></script>
HTML
        );
    }

    /**
     * Render the tags for production mode (built assets).
     */
    protected function renderProdTags(): HtmlString
    {
        $manifestPath = public_path("vendor/{$this->package}/.vite/manifest.json");

        static $manifests = [];

        if (! isset($manifests[$manifestPath])) {
            if (! is_file($manifestPath)) {
                throw new ViteException("Vite manifest not found at [{$manifestPath}].");
            }

            $manifests[$manifestPath] = json_decode(file_get_contents($manifestPath), true);
        }

        $manifest = $manifests[$manifestPath];
        $entryKey = "resources/js/{$this->entry}";

        $entry   = $manifest[$entryKey];
        $jsFile  = $entry['file'] ?? null;
        $cssList = $entry['css'] ?? [];

        $tags = [];

        foreach ($cssList as $css) {
            $tags[] = sprintf(
                '<link rel="stylesheet" type="text/css" href="/vendor/%s/%s">',
                $this->package,
                ltrim($css, '/')
            );
        }

        $tags[] = sprintf(
            '<script type="module" crossorigin src="/vendor/%s/%s"></script>',
            $this->package,
            ltrim($jsFile, '/')
        );

        return new HtmlString(implode('', $tags));
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
