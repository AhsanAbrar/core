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
                            {--routes : Add web routes to the basic package}
                            {--views : Add web routes and views to the basic package}
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
        if ($this->filesystem->isDirectory($destination)) {
            $this->fail('Spanvel Auth is already installed.');
        }

        $this->info('Spanvel package generated successfully.');

        return static::SUCCESS;
    }
}
