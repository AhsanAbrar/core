<?php

namespace Spanvel;

use Illuminate\Contracts\Http\Kernel as HttpKernel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Spanvel\Http\Middleware\RegisterPackage;
use Spanvel\Package\PackageContext;
use Spanvel\Package\PackageRegistrar;

class CoreServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->scoped('spanvel.package', fn () => new PackageContext);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // This is temporary, we will move this to support.
        Blade::directive('viteTags', function (string $expression) {
            return "<?php echo app(AhsanDev\Support\ViteNew::class)($expression); ?>";
        });

        Blade::directive('appData', function () {
            return "<?php echo app(AhsanDev\Support\AppDataDirective::class)(); ?>";
        });

        Model::unguard();

        if ($this->app->runningInConsole() && ! $this->app->runningUnitTests()) {
            PackageRegistrar::registerAllProviders();
        }

        $this->app->make(HttpKernel::class)
            ->pushMiddleware(RegisterPackage::class);
    }
}
