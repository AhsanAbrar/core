<?php

namespace Spanvel\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Spanvel\Package\PackageBoot boot(string $packagePath)
 * @method static string key(string|null $key = null)
 * @method static void setKey(string $key)
 * @method static void reset()
 *
 * @see \Spanvel\Package\PackageContext
 * @see \Spanvel\Package\PackageBoot
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
