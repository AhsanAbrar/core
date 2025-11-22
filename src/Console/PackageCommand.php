<?php

namespace Spanvel\Console;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Spanvel\Console\Concerns\InteractsWithFilesystem;

class PackageCommand extends Command implements PromptsForMissingInput
{
    use InteractsWithFilesystem;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spanvel:package {package : The spanvel package name} {type : The spanvel package type}
                            {--namespace= : The root namespace of the package if it is different from package name}
                            {--no-composer : Do not add the package to composer.json}
                            {--autoload : Add package to the PSR-4 autoload in composer.json}
                            {--composer-setup : Add package to require-dev and repositories in composer.json}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new spanvel package';

    /**
     * Prompt for missing input arguments using the returned questions.
     */
    protected function promptForMissingArgumentsUsing(): array
    {
        return [
            'type' => fn () => $this->choice(
                'Select the package type:',
                ['basic', 'blade', 'vue', 'app-vue'],
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

        if ($this->filesystem->isDirectory($this->packagePath())) {
            $this->fail('Package already exists!');
        }

        $this->info('Creating a new spanvel package...');

        $this->filesystem->copyDirectory(
            __DIR__.'/../../packages/'.$this->argument('type'),
            $this->packagePath()
        );

        $this->info('Spanvel package generated successfully.');

        return static::SUCCESS;
    }

    /**
     * Validate the arguments.
     */
    protected function validateArguments(): void
    {
        if (! $this->isKebabCase($this->argument('package'))) {
            $this->fail('The package name must be in kebab-case.');
        }

        if (! in_array($this->argument('type'), ['basic', 'blade', 'vue', 'app-vue'])) {
            $this->fail('Invalid package type. Allowed types: basic, blade, vue, app-vue');
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
     * Determine if the given string is in kebab-case.
     */
    protected function isKebabCase(string $string): bool
    {
        return (bool) preg_match('/^[a-z]+(-[a-z]+)*$/', $string);
    }
}
