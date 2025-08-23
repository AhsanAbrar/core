<?php

namespace Spanvel\Console\Concerns;

use Illuminate\Support\Str;

trait PackageReplaceHelpers
{
    /**
     * Get the package name.
     *
     * @return string
     */
    protected function name()
    {
        return $this->argument('package');
    }

    /**
     * Get the root namespace.
     *
     * @return string
     */
    protected function rootNamespace()
    {
        return $this->option('namespace') ? str_replace('/', '\\', $this->option('namespace')) : Str::studly($this->argument('package'));
    }

    /**
     * Get the root namespace for composer.
     *
     * @return string
     */
    protected function rootNamespaceComposer()
    {
        return $this->option('namespace') ? str_replace('/', '\\\\', $this->option('namespace')) : Str::studly($this->argument('package'));
    }

    /**
     * Get the camel case.
     *
     * @return string
     */
    protected function camel()
    {
        return Str::camel($this->argument('package'));
    }

    /**
     * Get the kebab case.
     *
     * @return string
     */
    protected function kebab()
    {
        return Str::kebab($this->argument('package'));
    }

    /**
     * Get the plural kebab case.
     *
     * @return string
     */
    protected function kebabPlural()
    {
        return Str::kebab(Str::plural( $this->argument('package') ));
    }

    /**
     * Get the plural name.
     *
     * @return string
     */
    protected function plural()
    {
        return Str::plural( $this->argument('package') );
    }

    /**
     * Get the title case with space from package name.
     *
     * @return string
     */
    protected function title()
    {
        return Str::of($this->argument('package'))->replace('-', ' ')->title();
    }

    /**
     * Get the pascle case package name.
     *
     * @return string
     */
    protected function pascalName()
    {
        return Str::studly( $this->argument('package') );
    }
}
