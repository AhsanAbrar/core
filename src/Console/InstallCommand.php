<?php

namespace Spanvel\Console;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spanvel:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish Spanvel config (config/packages.php) into the application';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->comment('Publishing Spanvel Config...');

        $this->callSilent('vendor:publish', [
            '--tag' => 'spanvel-config',
        ]);

        $this->comment('Publishing Spanvel Migrations...');

        $this->callSilent('vendor:publish', [
            '--tag' => 'spanvel-migrations',
        ]);

        $this->info('Spanvel scaffolding installed successfully.');

        return self::SUCCESS;
    }
}
