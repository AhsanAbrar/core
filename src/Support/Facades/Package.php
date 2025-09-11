<?php

namespace Spanvel\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Spanvel\Package\Bootstrapper boot(string $packagePath, array $options = [])
 *
 * @see \Spanvel\Package\Factory
 */
class Package extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'spanvel.package';
    }
}
