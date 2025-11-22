<?php

namespace Spanvel\Console;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Symfony\Component\Process\Process;

class PackageCommand extends Command implements PromptsForMissingInput
{
    use Concerns\InteractsWithFilesystem,
        Concerns\InteractsWithPackageNames;

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
                            {type : The Spanvel package type}
                            {--namespace= : The root namespace of the package if it is different from the package name}
                            {--no-composer : Do not change composer.json}
                            {--autoload : Add the package to the PSR-4 autoload in composer.json}
                            {--composer-setup : Add the package to require-dev and repositories in composer.json}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Spanvel package';

    /**
     * Prompt for missing input arguments.
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

        $this->updateStubs($packagePath);
        $this->renameStubs($packagePath);

        $this->addPackageToAutoload();
        $this->composerDump();

        $this->info("Spanvel package generated successfully at [{$packagePath}].");

        return static::SUCCESS;
    }

    /**
     * Replace placeholders inside stub files.
     */
    protected function updateStubs(string $packagePath): void
    {
        $files = $this->filesystem->allFiles($packagePath);

        foreach ($files as $file) {
            $stub = $this->filesystem->get($file);

            $replacements = [
                '[[name]]' => $this->name(),
                '[[rootNamespace]]' => $this->rootNamespace(),
                '[[rootNamespaceComposer]]' => $this->rootNamespaceComposer(),
                '[[pascalName]]' => $this->pascalName(),
                '[[title]]' => $this->title(),
            ];

            $content = str_replace(
                array_keys($replacements),
                array_values($replacements),
                $stub
            );

            $this->filesystem->put($file->getPathname(), $content);
        }
    }

    /**
     * Rename stub files to their final filenames.
     */
    protected function renameStubs(string $packagePath): void
    {
        $renames = [
            'src/ServiceProvider.stub' => 'src/[[pascalName]]ServiceProvider.php',
            '.gitignore.stub' => '.gitignore',
        ];

        $files = $this->filesystem->allFiles($packagePath, true);

        foreach ($files as $file) {
            if ($file->getExtension() !== 'stub') {
                continue;
            }

            $relativePath = $file->getRelativePathname();

            // If file is not in the renames list, skip it.
            if (! array_key_exists($relativePath, $renames)) {
                continue;
            }

            $fileName = $renames[$relativePath];

            // Assuming you have this helper in another trait / on the class.
            $newFileName = $this->replacePlaceholders($fileName);

            $newFilePath = $packagePath.DIRECTORY_SEPARATOR.$newFileName;

            $this->filesystem->move($file->getPathname(), $newFilePath);
        }
    }

    /**
     * Add the package to the application's PSR-4 autoload.
     */
    protected function addPackageToAutoload(): void
    {
        $composerPath = base_path('composer.json');

        if (! $this->filesystem->exists($composerPath)) {
            $this->fail('composer.json not found.');
        }

        $contents = $this->filesystem->get($composerPath);

        $composer = json_decode($contents, true);

        if (! is_array($composer)) {
            $this->fail('Unable to decode composer.json.');
        }

        $composer['autoload'] ??= [];
        $composer['autoload']['psr-4'] ??= [];

        $namespace = $this->rootNamespaceComposer().'\\';

        $composer['autoload']['psr-4'][$namespace] = "packages/{$this->name()}/src/";

        ksort($composer['autoload']['psr-4'], SORT_STRING | SORT_FLAG_CASE);

        $this->filesystem->put(
            $composerPath,
            json_encode(
                $composer,
                JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
            ).PHP_EOL
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
     * Run composer dump-autoload.
     */
    protected function composerDump(): void
    {
        $this->executeCommand(['composer', 'dump-autoload']);
    }

    /**
     * Run composer update (currently unused).
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
        $process = (new Process($command, base_path()))->setTimeout(null);

        $process->run(function (...$args) {
            $this->output->write($args[1]);
        });
    }
}
