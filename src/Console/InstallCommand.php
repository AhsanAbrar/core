<?php

namespace Spanvel\Console;

use Illuminate\Console\Command;
use Spanvel\CoreServiceProvider;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spanvel:install {--force : Overwrite existing files}';

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
        $arguments = [
            '--provider' => CoreServiceProvider::class,
            '--tag'      => 'spanvel-config',
        ];

        if ($this->option('force')) {
            $arguments['--force'] = true;
        }

        $this->call('vendor:publish', $arguments);

        return self::SUCCESS;
    }
}
