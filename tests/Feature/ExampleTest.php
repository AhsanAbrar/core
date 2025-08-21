<?php

test('laravel boots under testbench', function () {
    expect(config('app.env'))->toBe('testing');
});
