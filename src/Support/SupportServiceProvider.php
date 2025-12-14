<?php

namespace Spanvel\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Spanvel\Support\AppDataDirective;
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
        Model::unguard();

        Blade::directive('viteTags', fn ($expression) =>
            "<?php echo app('" . Vite::class . "')($expression); ?>"
        );

        Blade::directive('appData', fn ($expression) =>
            "<?php echo app('" . AppDataDirective::class . "')($expression); ?>"
        );
    }
}
