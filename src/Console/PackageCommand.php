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
    protected $signature = 'spanvel:package';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new spanvel package';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Spanvel package generated successfully.');

        return static::SUCCESS;
    }
}
