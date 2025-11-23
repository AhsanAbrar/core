<?php

namespace Spanvel\Console;

use Illuminate\Console\Command;

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
    protected $description = 'Publish the Spanvel Options migration.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $fileName = '0002_02_02_000000_create_options_table.php';

        $targetPath = database_path('migrations/' . $fileName);

        if ($this->filesystem->exists($targetPath)) {
            $this->info('Options migration already exists. Nothing to publish.');

            return static::SUCCESS;
        }

        $this->filesystem->copy($this->stubPath(), $targetPath);

        $this->info("Options migration published to: {$targetPath}");

        return static::SUCCESS;
    }

    /**
     * Path to the packaged migration stub.
     */
    protected function stubPath(): string
    {
        return __DIR__ . '/../../database/migrations/0002_02_02_000000_create_options_table.php';
    }
}
