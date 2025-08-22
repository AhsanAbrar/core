<?php

use Illuminate\Support\Facades\Route;
use Spanvel\Support\Facades\Package;
use Tests\Fixtures\BaseServiceProvider;

use function Pest\Laravel\get;

class SiteServiceProvider extends BaseServiceProvider {}
class AdminServiceProvider extends BaseServiceProvider {}

beforeEach(function () {
    config([
        'packages.providers' => [
            '' => SiteServiceProvider::class,
            'admin' => AdminServiceProvider::class,
        ],
        'packages.excluded_segments' => [],
    ]);
});

describe('Package Register', function () {
    it('skips registration when the segment is excluded', function () {
        config(['packages.excluded_segments' => ['login']]);

        Route::get('login', fn () => 'login page');

        get('/login')->assertOk();

        expect(app()->providerIsLoaded(SiteServiceProvider::class))->toBeFalse()
            ->and(app()->providerIsLoaded(AdminServiceProvider::class))->toBeFalse()
            ->and(Package::key())->toBe('');
    });

    it('registers the matching provider and sets key', function () {
        get('/admin/ping');

        expect(app()->providerIsLoaded(AdminServiceProvider::class))->toBeTrue()
            ->and(app()->providerIsLoaded(SiteServiceProvider::class))->toBeFalse()
            ->and(Package::key())->toBe('admin');
    });

    it('registers the root provider when no segment is present', function () {
        get('/');

        expect(app()->providerIsLoaded(SiteServiceProvider::class))->toBeTrue()
            ->and(app()->providerIsLoaded(AdminServiceProvider::class))->toBeFalse()
            ->and(Package::key())->toBe('');
    });

    it('registers the root provider when the segment is unknown', function () {
        get('/unknown');

        expect(app()->providerIsLoaded(SiteServiceProvider::class))->toBeTrue()
            ->and(app()->providerIsLoaded(AdminServiceProvider::class))->toBeFalse()
            ->and(Package::key())->toBe('');
    });
});
