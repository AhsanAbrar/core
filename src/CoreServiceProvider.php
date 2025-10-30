<?php

namespace Spanvel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Spanvel\Option\OptionServiceProvider;
use Spanvel\Package\PackageServiceProvider;

class CoreServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->registerProviders();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // This is temporary, we will move this to support.
        Blade::directive('viteTags', function (string $expression) {
            return "<?php echo app(Spanvel\Support\Vite::class)($expression); ?>";
        });

        Blade::directive('appData', function ($expression) {
            return "<?php echo app(Spanvel\Directive\AppDataDirective::class)($expression); ?>";
        });

        Model::unguard();

        if ($this->app->runningInConsole()) {
            $this->app->register(SpanvelServiceProvider::class);
        }
    }

    /**
     * Register the required service providers.
     */
    protected function registerProviders(): void
    {
        $providers = [
            PackageServiceProvider::class,
            OptionServiceProvider::class,
        ];

        foreach ($providers as $provider) {
            $this->app->register($provider);
        }
    }
}
