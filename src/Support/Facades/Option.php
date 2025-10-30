<?php

namespace Spanvel\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed get(string $key, mixed $default = null)
 * @method static bool put(string|array $key, mixed $value = null)
 *
 * @see \Spanvel\Support\Option
 */
class Option extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'option';
    }
}
