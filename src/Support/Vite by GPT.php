<?php

namespace Spanvel\Support;

use Illuminate\Foundation\ViteManifestNotFoundException;
use Illuminate\Support\HtmlString;
use RuntimeException;

class Vite
{
    /**
     * Package directory name under /packages and /public/vendor.
     *
     * Example: "auth" => /packages/auth, /public/vendor/auth
     */
    protected string $package;

    /**
     * Vite entry file name (without path).
     *
     * Example: "main.ts" => resources/js/main.ts
     */
    protected string $entry = 'main.ts';

    /**
     * Dev server host taken from the hot file (fallback: localhost).
     */
    protected string $devHost = 'localhost';

    /**
     * Dev server port taken from the hot file (fallback: 5173).
     */
    protected int $devPort = 5173;

    /**
     * Render Vite tags for a Spanvel package.
     *
     * Usage:
     *  @viteTags('auth')
     *  @viteTags('auth', 'admin.ts')
     */
    public function __invoke(string $package, string $entry = 'main.ts'): HtmlString
    {
        $this->package = trim($package);
        $this->entry   = ltrim($entry, '/');

        if ($this->isHot()) {
            $this->loadHotConfig();

            return $this->renderDev();
        }

        return $this->renderProd();
    }

    /**
     * Determine if the package is running in "hot" (HMR) mode.
     */
    protected function isHot(): bool
    {
        return is_file($this->hotFilePath());
    }

    /**
     * Get the path to the hot file for this package.
     */
    protected function hotFilePath(): string
    {
        return base_path("packages/{$this->package}/hot");
    }

    /**
     * Load dev host and port from the hot file (if any).
     *
     * Hot file example:
     *  port: 5173
     *  ip: 192.168.100.96
     */
    protected function loadHotConfig(): void
    {
        $contents = @file_get_contents($this->hotFilePath());

        if ($contents === false) {
            // If for some reason the file disappeared, keep defaults.
            return;
        }

        foreach (preg_split('/\r\n|\r|\n/', $contents) as $line) {
            if (! str_contains($line, ':')) {
                continue;
            }

            [$key, $value] = array_map('trim', explode(':', $line, 2));

            if ($key === 'ip' && $value !== '') {
                $this->devHost = $value;
            }

            if ($key === 'port' && is_numeric($value)) {
                $this->devPort = (int) $value;
            }
        }
    }

    /**
     * Render the tags for development (HMR) mode.
     *
     * Uses network IP from the hot file, so mobile devices on the same
     * network can access it.
     */
    protected function renderDev(): HtmlString
    {
        $host  = "{$this->devHost}:{$this->devPort}";
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
    protected function renderProd(): HtmlString
    {
        $manifestPath = public_path("vendor/{$this->package}/.vite/manifest.json");

        static $manifests = [];

        if (! isset($manifests[$manifestPath])) {
            if (! is_file($manifestPath)) {
                throw new ViteManifestNotFoundException(
                    "Vite manifest not found at [{$manifestPath}]."
                );
            }

            $decoded = json_decode(file_get_contents($manifestPath), true);

            if (! is_array($decoded)) {
                throw new RuntimeException(
                    "Vite manifest at [{$manifestPath}] is not valid JSON."
                );
            }

            $manifests[$manifestPath] = $decoded;
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
}
