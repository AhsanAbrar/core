<?php

use Tests\Fixtures\Site\Src\SiteServiceProvider;

use function Pest\Laravel\get;

describe('PackageBoot views', function () {
    beforeEach(function () {
        app()->register(SiteServiceProvider::class);
    });

    it('prepends the package resources/views path', function () {
        get('/hello')
            ->assertOk()
            ->assertSee('From Package View', false);
    });
});
