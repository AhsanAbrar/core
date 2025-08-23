<?php

use Spanvel\SpanvelServiceProvider;
use function Pest\Laravel\artisan;

beforeEach(function () {
    $this->configPath = config_path('packages.php');
    $this->app->register(SpanvelServiceProvider::class);

    if (file_exists($this->configPath)) {
        @unlink($this->configPath);
    }

    if (! is_dir(dirname($this->configPath))) {
        mkdir(dirname($this->configPath), 0777, true);
    }
});

it('publishes config when running spanvel:install', function () {
    expect(file_exists($this->configPath))->toBeFalse();

    $code = artisan('spanvel:install')
        ->expectsOutputToContain('Spanvel scaffolding installed successfully.')
        ->run();

    expect($code)->toBe(0);
    expect(file_exists($this->configPath))->toBeTrue();

    $content = file_get_contents($this->configPath);
    expect($content)->toContain("'providers'");
    expect($content)->toContain("'excluded_segments'");
});

it('does not overwrite existing config', function () {
    file_put_contents($this->configPath, "<?php\n\nreturn ['foo' => 'bar'];\n");

    $original = file_get_contents($this->configPath);

    $code = artisan('spanvel:install')->run();
    expect($code)->toBe(0);

    $after = file_get_contents($this->configPath);
    expect($after)->toBe($original);
});
