<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Spanvel\Support\Facades\Option;

beforeEach(function () {
    DB::table('options')->truncate();
    Cache::flush();
});

it('stores and retrieves an option', function () {
    Option::put('site_name', 'Codedot');
    expect(Option::get('site_name'))->toBe('Codedot');
});

it('returns default when option not found', function () {
    expect(Option::get('missing', 'default'))->toBe('default');
});

it('stores and retrieves array values as json', function () {
    $data = ['title' => 'Spanvel', 'env' => 'local'];
    Option::put('app', $data);

    $stored = DB::table('options')->where('key', 'app')->first();
    expect(json_decode($stored->value, true))->toBe($data);
    expect(Option::get('app'))->toBe($data);
});

it('stores multiple options at once', function () {
    Option::put([
        'site_name' => 'Codedot',
        'tagline' => 'We build for developers',
    ]);

    expect(Option::get('site_name'))->toBe('Codedot');
    expect(Option::get('tagline'))->toBe('We build for developers');
});

it('throws exception when non-associative array passed', function () {
    $this->expectException(InvalidArgumentException::class);
    Option::put(['a', 'b']);
});

it('caches retrieved values', function () {
    DB::table('options')->insert(['key' => 'theme', 'value' => json_encode('light')]);

    // First call stores in cache
    $value1 = Option::get('theme');
    DB::table('options')->delete(); // delete from DB

    // Second call should still return cached value
    $value2 = Option::get('theme');
    expect($value1)->toBe($value2);
});

it('clears cache after update', function () {
    Option::put('theme', 'light');
    expect(Option::get('theme'))->toBe('light');

    Option::put('theme', 'dark');
    expect(Option::get('theme'))->toBe('dark');
});
