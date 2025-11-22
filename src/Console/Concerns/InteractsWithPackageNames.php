<?php

namespace Spanvel\Console\Concerns;

use Illuminate\Support\Str;

trait InteractsWithPackageNames
{
    /**
     * Get the base package name from the command argument.
     */
    protected function name(): string
    {
        return $this->argument('package');
    }

    /**
     * Get the root namespace for the package.
     */
    protected function rootNamespace(): string
    {
        $namespace = $this->option('namespace');

        if (is_string($namespace) && $namespace !== '') {
            return str_replace('/', '\\', $namespace);
        }

        return Str::studly($this->name());
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

        return Str::studly($this->name());
    }

    /**
     * Get the camelCase variant of the package name.
     */
    protected function camelName(): string
    {
        return Str::camel($this->name());
    }

    /**
     * Get the kebab-case variant of the package name.
     */
    protected function kebabName(): string
    {
        return Str::kebab($this->name());
    }

    /**
     * Get the plural kebab-case variant of the package name.
     */
    protected function kebabPluralName(): string
    {
        return Str::kebab(Str::plural($this->name()));
    }

    /**
     * Get the plural form of the package name.
     */
    protected function pluralName(): string
    {
        return Str::plural($this->name());
    }

    /**
     * Get the title-cased variant of the package name.
     */
    protected function titleName(): string
    {
        return Str::of($this->name())->replace('-', ' ')->title();
    }

    /**
     * Get the PascalCase variant of the package name.
     */
    protected function pascalName(): string
    {
        return Str::studly($this->name());
    }

    /**
     * Determine if the given string is valid kebab-case.
     */
    protected function isKebabCase(string $value): bool
    {
        return (bool) preg_match('/^[a-z]+(-[a-z]+)*$/', $value);
    }
}
