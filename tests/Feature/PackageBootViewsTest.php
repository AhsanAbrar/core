<?php

use Spanvel\Support\Facades\Package;
use Tests\Fixtures\Site\src\SiteServiceProvider;

use function Pest\Laravel\get;

describe('PackageBoot views', function () {
    beforeEach(function () {
        Package::setKey('site');
        $this->app->register(SiteServiceProvider::class);
    });

    it('prepends the package resources/views path', function () {
        get('/site/hello')->assertOk()->assertSee('From Package View', false);
    });
});
