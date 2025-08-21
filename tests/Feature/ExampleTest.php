<?php

test('laravel boots under testbench', function () {
    expect(config('app.env'))->toBe('testing');
});

test('package service provider boots', function () {
    expect(app()->providerIsLoaded(\Spanvel\CoreServiceProvider::class))->toBeTrue();
});
