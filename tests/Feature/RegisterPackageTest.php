<?php

use Illuminate\Support\Facades\Route;
use Spanvel\Package;
use Tests\Fixtures\BaseServiceProvider;

use function Pest\Laravel\get;

class SiteServiceProvider extends BaseServiceProvider {}
class AdminServiceProvider extends BaseServiceProvider {}

describe('Package Register', function () {

    it('skips registration when the segment is excluded', function () {
        config([
            'packages.providers' => [
                '' => SiteServiceProvider::class,
                'admin' => AdminServiceProvider::class,
            ],
            'packages.excluded_segments' => ['login'],
        ]);

        Route::get('login', fn () => 'login page');

        get('/login')->assertOk();

        expect(app()->providerIsLoaded(SiteServiceProvider::class))->toBeFalse()
            ->and(app()->providerIsLoaded(AdminServiceProvider::class))->toBeFalse()
            ->and(Package::key())->toBe('');
    });

    it('registers the matching provider and sets key', function () {
        // Given
        config([
            'packages.providers' => [
                '' => SiteServiceProvider::class,
                'admin' => AdminServiceProvider::class,
            ],
        ]);

        // When
        get('/admin/ping');

        // Then
        expect(app()->providerIsLoaded(AdminServiceProvider::class))->toBeTrue()
            ->and(Package::key())->toBe('admin');
    });

    // it('registers the root provider when no segment is present', function () {
    //     // Given
    //     config()->set('packages.providers', [
    //         '' => SiteServiceProvider::class,
    //     ]);

    //     // When
    //     $res = $this->get('/ping'); // defined by root provider

    //     // Then
    //     $res->assertOk()->assertSee('site-ping');
    //     expect(app()->bound('site.registered'))->toBeTrue();
    //     expect(Package::key())->toBe(''); // empty key for root
    // });

    // it('registers the root provider when the segment is unknown', function () {
    //     // Given
    //     config()->set('packages.providers', [
    //         '' => SiteServiceProvider::class,
    //         // no mapping for "unknown"
    //     ]);
    //     config()->set('packages.excluded_segments', ['login', 'register', 'logout']);

    //     // When
    //     $res = $this->get('/unknown');

    //     // Then
    //     $res->assertStatus(404);
    //     expect(app()->bound('site.registered'))->toBeTrue();
    //     expect(Package::key())->toBe(''); // empty key for root
    // });

});
