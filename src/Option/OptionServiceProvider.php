<?php

namespace Spanvel\Option;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Spanvel\Support\Contracts\Option as OptionContract;

class OptionServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register the Option service.
     */
    public function register(): void
    {
        $this->app->singleton(OptionContract::class, fn () => new Option);
        $this->app->alias(OptionContract::class, 'option');
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [
            OptionContract::class,
            'option',
        ];
    }
}
