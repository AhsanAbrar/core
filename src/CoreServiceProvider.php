<?php

namespace Spanvel;

// use Illuminate\Contracts\Http\Kernel as HttpKernel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
// use Spanvel\Http\Middleware\RegisterPackage;
// use Spanvel\Package\PackageContext;
use Spanvel\Support\Contracts\Option as OptionContract;
use Spanvel\Support\Option;

class CoreServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // $this->app->scoped('spanvel.package', fn () => new PackageContext);

        // Option singleton
        $this->app->singleton(OptionContract::class, fn () => new Option);
        $this->app->alias(OptionContract::class, 'option');
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

        Blade::directive('appData', function () {
            return "<?php echo app(Spanvel\Directive\AppDataDirective::class)(); ?>";
        });

        Model::unguard();

        // if (! $this->app->configurationIsCached()) {
        //     $this->mergeConfigFrom(__DIR__.'/../config/packages.php', 'packages');
        // }

        // $this->app->make(HttpKernel::class)
        //     ->pushMiddleware(RegisterPackage::class);
    }
}
