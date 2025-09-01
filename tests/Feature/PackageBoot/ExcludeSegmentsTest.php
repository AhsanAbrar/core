<?php

use Tests\Fixtures\Site\Src\SiteServiceProviderExcludeSegments;

use function Pest\Laravel\get;

it('can exclude route segments globally', function () {
    config()->set('packages.excluded_segments', ['login']);

    $this->app->register(SiteServiceProviderExcludeSegments::class);

    get('/hello');

    expect(config('packages.excluded_segments'))
        ->toBe(['login', 'register']);
});
