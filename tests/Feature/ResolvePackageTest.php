<?php

use Spanvel\Package;
use Spanvel\Tests\Fixtures\Admin\AdminServiceProvider;
use Spanvel\Tests\Fixtures\Login\LoginServiceProvider;
use Spanvel\Tests\Fixtures\Site\SiteServiceProvider;

it('registers the root provider on / and sets Package::key("")', function () {
    // Ensure root configured
    config()->set('packages.providers', [
        '' => SiteServiceProvider::class,
    ]);

    $res = $this->get('/ping');

    $res->assertOk()->assertSee('site-ping');
    expect(app()->bound('site.registered'))->toBeTrue();
    expect(Package::key())->toBe('');
});

it('registers root and matching segment provider; key set to segment', function () {
    config()->set('packages.providers', [
        ''      => SiteServiceProvider::class,
        'admin' => AdminServiceProvider::class,
    ]);

    $res = $this->get('/admin/ping');

    $res->assertOk()->assertSee('admin:admin');
    // Root should also be available for the request lifecycle
    expect(app()->bound('site.registered'))->toBeTrue();
    // Key should be the matched segment
    expect(Package::key())->toBe('admin');
});

it('does not register any provider for excluded segment', function () {
    config()->set('packages.providers', [
        ''      => SiteServiceProvider::class,
        'login' => LoginServiceProvider::class, // even if defined, excluded should block
    ]);

    config()->set('packages.excluded_segments', ['login']);

    // /login is excluded; no provider registration should happen
    $res = $this->get('/login');

    // Likely 404 since we did not define a /login route; key assertion still valid
    $res->assertStatus(404);
    expect(app()->bound('site.registered'))->toBeFalse();
    expect(app()->bound('login.registered'))->toBeFalse();
});

it('falls back to root provider when segment does not match any key', function () {
    config()->set('packages.providers', [
        '' => SiteServiceProvider::class,
        // no mapping for "unknown"
    ]);

    // Hitting /unknown will not match; root provider still registers
    $res = $this->get('/unknown');

    $res->assertStatus(404); // no route defined at /unknown
    expect(app()->bound('site.registered'))->toBeTrue();
    expect(Package::key())->toBe(''); // middleware sets key("") on no match
});

it('root package can serve its own routes beyond / (e.g., /foo)', function () {
    config()->set('packages.providers', [
        '' => SiteServiceProvider::class,
    ]);

    // /foo is defined by SiteServiceProvider
    $res = $this->get('/foo');

    $res->assertOk()->assertSee('site-foo');
    expect(app()->bound('site.registered'))->toBeTrue();
    expect(Package::key())->toBe(''); // no matching segment key
});
