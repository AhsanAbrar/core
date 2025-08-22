<?php

namespace Spanvel\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string key(?string $key = null)
 * @method static void reset()
 * @method static \Spanvel\PackageBoot boot(string $packagePath)
 *
 * @see \Spanvel\Package\PackageContext
 */
class Package extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'spanvel.package';
    }
}
