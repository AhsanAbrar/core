<?php

namespace Spanvel\Console;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Spanvel\Console\Concerns\InteractsWithFilesystem;

class PackageCommand extends Command implements PromptsForMissingInput
{
    use InteractsWithFilesystem;

    /**
     * Allowed package types.
     *
     * @var array<int, string>
     */
    protected const ALLOWED_TYPES = ['basic', 'blade', 'vue', 'app-vue'];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spanvel:package
                            {package : The Spanvel package name (kebab-case)}
                            {type? : The Spanvel package type}
                            {--namespace= : The root namespace of the package if it is different from the package name}
                            {--no-composer : Do not add the package to composer.json}
                            {--autoload : Add the package to the PSR-4 autoload in composer.json}
                            {--composer-setup : Add the package to require-dev and repositories in composer.json}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Spanvel package';

    /**
     * Prompt for missing input arguments using the returned questions.
     */
    protected function promptForMissingArgumentsUsing(): array
    {
        return [
            'package' => fn () => $this->ask(
                'Package name (kebab-case, e.g. blog-api)'
            ),

            'type' => fn () => $this->choice(
                'Select the package type:',
                self::ALLOWED_TYPES,
                'basic'
            ),
        ];
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->validateArguments();

        $packagePath = $this->packagePath();

        if ($this->filesystem->isDirectory($packagePath)) {
            $this->fail("Package already exists at [{$packagePath}].");
        }

        $this->info('Creating a new Spanvel package...');

        $this->filesystem->copyDirectory(
            $this->stubPath(),
            $packagePath
        );

        // TODO: Handle --namespace, --no-composer, --autoload, --composer-setup options.

        $this->info("Spanvel package generated successfully at [{$packagePath}].");

        return static::SUCCESS;
    }

    /**
     * Validate the arguments.
     */
    protected function validateArguments(): void
    {
        $package = (string) $this->argument('package');
        $type = (string) $this->argument('type');

        if (! $this->isKebabCase($package)) {
            $this->fail(
                'The package name must be in kebab-case (e.g. blog-api, project-manager).'
            );
        }

        if (! in_array($type, self::ALLOWED_TYPES, true)) {
            $this->fail(
                'Invalid package type. Allowed types: '.implode(', ', self::ALLOWED_TYPES).'.'
            );
        }
    }

    /**
     * Get the path to the package.
     */
    protected function packagePath(): string
    {
        return base_path('packages/'.$this->argument('package'));
    }

    /**
     * Get the path to the package stub directory for the selected type.
     */
    protected function stubPath(): string
    {
        return __DIR__.'/../../packages/'.$this->argument('type');
    }

    /**
     * Determine if the given string is in kebab-case.
     */
    protected function isKebabCase(string $string): bool
    {
        return (bool) preg_match('/^[a-z]+(-[a-z]+)*$/', $string);
    }
}
