<?php

namespace Spanvel\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class OptionCommand extends Command
{
    use Concerns\InteractsWithFilesystem;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spanvel:option';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish the Spanvel options migration.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $migrationBaseName = 'create_options_table.php';

        if ($this->optionMigrationExists($migrationBaseName)) {
            $this->info('Options migration already exists. Nothing to publish.');

            return static::SUCCESS;
        }

        $stubPath = $this->stubPath();

        if (! $this->filesystem->exists($stubPath)) {
            $this->error("Option migration stub not found at [{$stubPath}].");

            return static::FAILURE;
        }

        $targetPath = $this->makeMigrationPath($migrationBaseName);

        $this->filesystem->ensureDirectoryExists(dirname($targetPath));

        $this->filesystem->copy($stubPath, $targetPath);

        $this->info("Spanvel options migration published to [{$targetPath}].");

        return static::SUCCESS;
    }

    /**
     * Determine if the options migration already exists.
     */
    protected function optionMigrationExists(string $migrationBaseName): bool
    {
        $migrationsPath = database_path('migrations');

        if (! $this->filesystem->isDirectory($migrationsPath)) {
            return false;
        }

        foreach ($this->filesystem->files($migrationsPath) as $file) {
            if (Str::endsWith($file->getFilename(), $migrationBaseName)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the full path where the migration file should be written.
     */
    protected function makeMigrationPath(string $migrationBaseName): string
    {
        $timestamp = date('Y_m_d_His');

        return database_path('migrations/'.$timestamp.'_'.$migrationBaseName);
    }

    /**
     * Get the path to the options migration stub within the Spanvel package.
     */
    protected function stubPath(): string
    {
        // Adjust this path according to where you keep the stub in Spanvel.
        return __DIR__.'/../../database/migrations/create_options_table.php.stub';
    }
}
