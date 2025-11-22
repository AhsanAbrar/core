<?php

declare(strict_types=1);

namespace Spanvel\Devtools\Console\Concerns;

use Illuminate\Support\Str;

/**
 * Helpers for working with package names and their common variants.
 *
 * This trait assumes the consuming command defines a `package` argument and
 * optionally a `namespace` option (similar to typical Laravel artisan commands).
 */
trait InteractsWithPackageNames
{
    /**
     * Get the base package name from the command argument.
     */
    protected function packageName(): string
    {
        /** @var string $package */
        $package = $this->argument('package');

        return $package;
    }

    /**
     * Get the root namespace for the package.
     *
     * When the --namespace option is provided, it is used as-is (with forward
     * slashes converted to namespace separators). Otherwise, the StudlyCase
     * version of the package name is used.
     */
    protected function rootNamespace(): string
    {
        $namespace = $this->option('namespace');

        if (is_string($namespace) && $namespace !== '') {
            return str_replace('/', '\\', $namespace);
        }

        return Str::studly($this->packageName());
    }

    /**
     * Get the root namespace formatted for composer.json (escaped backslashes).
     */
    protected function rootNamespaceComposer(): string
    {
        $namespace = $this->option('namespace');

        if (is_string($namespace) && $namespace !== '') {
            return str_replace('/', '\\\\', $namespace);
        }

        return Str::studly($this->packageName());
    }

    /**
     * Get the camelCase variant of the package name.
     */
    protected function camelName(): string
    {
        return Str::camel($this->packageName());
    }

    /**
     * Get the kebab-case variant of the package name.
     */
    protected function kebabName(): string
    {
        return Str::kebab($this->packageName());
    }

    /**
     * Get the plural kebab-case variant of the package name.
     */
    protected function kebabPluralName(): string
    {
        return Str::kebab(Str::plural($this->packageName()));
    }

    /**
     * Get the plural form of the package name.
     */
    protected function pluralName(): string
    {
        return Str::plural($this->packageName());
    }

    /**
     * Get the title-cased variant of the package name.
     */
    protected function titleName(): string
    {
        return Str::of($this->packageName())->replace('-', ' ')->title();
    }

    /**
     * Get the PascalCase variant of the package name.
     */
    protected function pascalName(): string
    {
        return Str::studly($this->packageName());
    }

    /**
     * Determine if the given string is valid kebab-case.
     */
    protected function isKebabCase(string $value): bool
    {
        return (bool) preg_match('/^[a-z]+(-[a-z]+)*$/', $value);
    }
}
