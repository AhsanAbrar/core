<?php

namespace Spanvel\Support;

use Illuminate\Foundation\ViteException;
use Illuminate\Support\HtmlString;
use RuntimeException;

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
     * Determine if the HMR server is running.
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

        if (! isset($manifest[$entryKey])) {
            throw new RuntimeException(
                "Vite entry [{$entryKey}] is not present in the manifest at [{$manifestPath}]."
            );
        }

        $entry   = $manifest[$entryKey];
        $jsFile  = $entry['file'] ?? null;
        $cssList = $entry['css'] ?? [];

        if (! $jsFile) {
            throw new RuntimeException(
                "Vite manifest entry [{$entryKey}] is missing the compiled JavaScript file path."
            );
        }

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
