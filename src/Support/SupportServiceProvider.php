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
        Blade::directive('viteTags', function (string $expression) {
            return "<?php echo app(Spanvel\Support\Vite::class)($expression); ?>";
        });
    }
}
