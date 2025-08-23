<?php

namespace Spanvel\Console;

use Spanvel\Console\Concerns\CommandHelpers;
use Spanvel\Console\Concerns\PackageReplaceHelpers;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

class PackageCommand extends Command implements PromptsForMissingInput
{
    use CommandHelpers, PackageReplaceHelpers;

    /**
     * Create a new instance of the command.
     */
    public function __construct(protected Filesystem $filesystem)
    {
        parent::__construct();
    }

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
                ['basic', 'blade', 'vue', 'vue-app'],
                'basic'
            ),
        ];
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->validateArguments();

        if ( is_dir($this->packagePath()) ) {
            $this->fail('Package already exists!');
        }

        $this->info('Creating a new spanvel package...');

        $this->filesystem->copyDirectory(
            __DIR__ . '/../../packages/' . $this->argument('type'),
            $this->packagePath()
        );

        $this->updateStubs();
        $this->renameStubs();
        $this->modificationsWithOptions();

        if ($this->option('no-composer')) {
            $this->info('Spanvel package generated successfully.');

            return;
        }

        if ($this->option('autoload')) {
            $this->addPackageToAutoload();
        } elseif ($this->option('composer-setup')) {
            $this->addPackageToRequireDev();
            $this->addPackageToRepositories();
        } else {
            $this->addPackageToAutoload();
        }

        // $this->composerUpdate();
        $this->addPackageToConfig();

        // Register the package...
        // if ($this->confirm('Would you like to update your composer package?', true)) {
        //     $this->addPackageToAutoload();

        //     $this->composerDump();
        // }

        $this->info('Spanvel package generated successfully.');
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
     * Rename Stubs.
     */
    protected function modificationsWithOptions()
    {
        if ($this->option('routes') && $this->argument('type') === 'basic') {
            $this->filesystem->copyDirectory(
                __DIR__ . '/../../packages-options/basic/routes',
                $this->packagePath()
            );

            $this->updateStubs();
            $this->renameStubs();
        }

        if ($this->option('views') && $this->argument('type') === 'basic') {
            $this->filesystem->copyDirectory(
                __DIR__ . '/../../packages-options/basic/views',
                $this->packagePath()
            );

            $this->updateStubs();
            $this->renameStubs();
        }
    }

    /**
     * Update Stubs.
     */
    protected function updateFiles(): void
    {
        // Package Name - name (my-admin-blog)
        // Package Name - title (My Admin Blog)
        // Package Name - pascalName (MyAdminBlog)
        // Root Namespace - rootNamespace (Laranext\Spanvel\Admin\Blog)
        // Root Namespace - rootNamespaceComposer (Laranext\\Spanvel\\Admin\\Blog)

        // composer.json replacements...
        $this->replace('{{ name }}', $this->argument('package'), $this->packagePath('composer.json'));
        $this->replace('{{ rootNamespaceComposer }}', $this->rootNamespaceComposer(), $this->packagePath('composer.json'));

        // rename service provider and replacements...
        $this->replace('{{ rootNamespace }}', $this->rootNamespace(), $this->packagePath('src/ServiceProvider.stub'));
        $this->replace('{{ pascalName }}', $this->pascalName(), $this->packagePath('src/ServiceProvider.stub'));
        $this->filesystem->move(
            $this->packagePath('src/ServiceProvider.stub'),
            $this->packagePath( 'src/' . $this->pascalName() . 'ServiceProvider.php' )
        );

        // rename .gitignore.stub to .gitignore
        $this->filesystem->move(
            $this->packagePath('.gitignore.stub'),
            $this->packagePath('.gitignore')
        );
    }

    /**
     * Add Web Routes.
     */
    protected function addWebRoutes(): void
    {
        $this->filesystem->copy(
            __DIR__ . '/../../packages/package/src/WebRoutesServiceProvider.stub',
            $this->packagePath('src/ServiceProvider.stub')
        );

        // rename service provider and replacements...
        $this->replace('{{ rootNamespace }}', $this->rootNamespace(), $this->packagePath('src/ServiceProvider.stub'));
        $this->replace('{{ pascalName }}', $this->pascalName(), $this->packagePath('src/ServiceProvider.stub'));
        $this->replace('{{ name }}', $this->argument('package'), $this->packagePath('src/ServiceProvider.stub'));
        $this->filesystem->move(
            $this->packagePath('src/ServiceProvider.stub'),
            $this->packagePath( 'src/' . $this->pascalName() . 'ServiceProvider.php' )
        );
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

    protected function addPackageToRequireDev(): void
    {
        $composer = json_decode(file_get_contents(base_path('composer.json')), true);

        $composer['require-dev']['spanvel/'.$this->name()] = 'dev-main';

        file_put_contents(
            base_path('composer.json'),
            json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );
    }

    protected function addPackageToRepositories(): void
    {
        $composer = json_decode(file_get_contents(base_path('composer.json')), true);

        $repository = [
            'type' => 'path',
            'url' => "./packages/{$this->name()}"
        ];

        if (!isset($composer['repositories'])) {
            $composer['repositories'] = [];
        }

        $composer['repositories'][] = $repository;

        file_put_contents(
            base_path('composer.json'),
            json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );
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

        // $process->setTty(Process::isTtySupported());

        $process->run(function ($type, $buffer) {
            $type;
            echo $buffer;
        });
    }

    protected function addPackageToConfigOld()
    {
        $configPath = base_path('config/packages.php');
        $config = include($configPath);

        $config['providers'][$this->name()] = $this->rootNamespace() .'\\'. $this->pascalName().'ServiceProvider::class,';

        $configContent = "<?php\n\nreturn " . var_export($config, true) . ";\n";

        file_put_contents($configPath, $configContent);
    }

    protected function addPackageToConfig()
    {
        $configPath = base_path('config/packages.php');
        $configContent = file_get_contents($configPath);

        // Add the provider entry before the closing bracket of the 'providers' array
        $newEntry = "    '{$this->name()}' => {$this->rootNamespace()}\\{$this->pascalName()}ServiceProvider::class,\n";
        $pattern = '/(\'providers\' => \[)(.*?)(\],)/s';
        $replacement = '$1$2' . $newEntry . '    $3';
        $configContent = preg_replace($pattern, $replacement, $configContent, 1);

        // Write the updated content back to the file
        file_put_contents($configPath, $configContent);
    }

    /**
     * Validate the arguments.
     */
    protected function validateArguments(): void
    {
        if (! $this->isKebabCase($this->argument('package'))) {
            $this->fail('The package name must be in kebab-case.');
        }

        // if (! in_array($this->argument('type'), ['basic', 'blade', 'vue', 'vue-app'])) {
        //     $this->fail('Invalid package type. Allowed types: basic, blade, vue, vue-app');
        // }
    }

    public function replacePlaceholders($str)
    {
        $replacements = [
            '[[pascalName]]' => $this->pascalName(),
            '[[title]]' => $this->title(),
            '[[name]]' => $this->name(),
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $str);
    }
}
