<?php

namespace Spanvel\Support;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Spanvel\Support\Vite;

class SupportServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::directive('viteTags', fn ($expression) =>
            "<?php echo app('" . Vite::class . "')($expression); ?>"
        );

        Blade::directive('appData', fn ($expression) =>
            "<?php echo app('" . AppDataDirective::class . "')($expression); ?>"
        );

        Blade::directive('appData', function ($expression) {
            return "<?php echo app(Spanvel\Directive\AppDataDirective::class)($expression); ?>";
        });
    }
}
