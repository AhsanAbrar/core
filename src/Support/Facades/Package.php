<?php

namespace Spanvel\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Spanvel\Package\Bootstrapper boot(string $basePath, ?string $key = null)
 *
 * @see \Spanvel\Package\Factory
 * @see \Spanvel\Package\Bootstrapper
 */
class Package extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'spanvel.package';
    }
}
