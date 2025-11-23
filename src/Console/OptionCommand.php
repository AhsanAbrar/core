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
    protected $description = 'Generate option migration';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if ($this->filesystem->isDirectory($packagePath)) {
            // $this->fail("Package already exists at [{$packagePath}].");
        }

        $this->info('Creating a new Spanvel package...');

        $this->filesystem->copyDirectory(
            $this->stubPath(),
            $packagePath
        );

        $this->info('Spanvel option migration published successfully');

        return static::SUCCESS;
    }
}
