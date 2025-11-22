<?php

namespace Spanvel\Console;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Spanvel\Console\Concerns\InteractsWithFilesystem;
use Symfony\Component\Process\Process;

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

        $this->updateStubs();
        $this->renameStubs();
        $this->addPackageToAutoload();
        $this->composerDump();

        $this->info("Spanvel package generated successfully at [{$packagePath}].");

        return static::SUCCESS;
    }

    /**
     * Update Stubs.
     */
    protected function updateStubs(): void
    {
        $files = $this->filesystem->allFiles($this->packagePath());

        foreach ($files as $file) {
            $stub = $this->filesystem->get($file);

            $replacements = [
                '[[name]]' => $this->name(),
                '[[rootNamespace]]' => $this->rootNamespace(),
                '[[rootNamespaceComposer]]' => $this->rootNamespaceComposer(),
                '[[pascalName]]' => $this->pascalName(),
                '[[title]]' => $this->title(),
            ];

            $content = str_replace(array_keys($replacements), array_values($replacements), $stub);

            $this->filesystem->put($file->getPathname(), $content);
        }
    }

    /**
     * Rename Stubs.
     */
    protected function renameStubs()
    {
        $renames = [
            'src\ServiceProvider.stub' => 'src/[[pascalName]]ServiceProvider.php',
            '.gitignore.stub' => '.gitignore',
        ];

        $files = $this->filesystem->allFiles($this->packagePath(), true);

        foreach ($files as $file) {
            if ($file->getExtension() !== 'stub') {
                continue;
            }

            $fileName = $renames[$file->getRelativePathname()] ?? null;
            $newFileName = $this->replacePlaceholders($fileName);

            $newFilePath = $this->packagePath($newFileName);
            $this->filesystem->move($file->getPathname(), $newFilePath);
        }
    }

    /**
     * Add a package entry for the package to the application's composer.json file.
     */
    protected function addPackageToAutoload(): void
    {
        $composer = json_decode(file_get_contents(base_path('composer.json')), true);
        $namespace = $this->rootNamespace().'\\';

        $composer['autoload']['psr-4'][(string) $namespace] = "packages/{$this->name()}/src/";

        $composer['autoload']['psr-4'] = collect($composer['autoload']['psr-4'])->sortKeysUsing('strcasecmp')->toArray();

        file_put_contents(
            base_path('composer.json'),
            json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );
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

    /**
     * Update the project's composer dependencies.
     */
    protected function composerDump(): void
    {
        $this->executeCommand(['composer', 'dump']);
    }

    /**
     * Update the project's composer dependencies.
     */
    protected function composerUpdate(): void
    {
        $this->executeCommand(['composer', 'update']);
    }

    /**
     * Run the given command as a process.
     */
    protected function executeCommand(array $command): void
    {
        $process = (new Process($command))->setTimeout(null);

        $process->run(function ($type, $buffer) {
            echo $buffer;
        });
    }
}
