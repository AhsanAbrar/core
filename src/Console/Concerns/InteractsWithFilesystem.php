<?php

namespace Spanvel\Console\Concerns;

use Illuminate\Filesystem\Filesystem;

trait InteractsWithFilesystem
{
    /**
     * The Filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected Filesystem $filesystemInstance;

    /**
     * Dynamically access the Filesystem instance.
     */
    public function __get(string $name): mixed
    {
        if ($name === 'filesystem') {
            return $this->filesystemInstance ??= app(Filesystem::class);
        }

        return null;
    }
}
