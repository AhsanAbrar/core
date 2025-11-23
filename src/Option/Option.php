<?php

namespace Spanvel\Option;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Spanvel\Support\Contracts\Option as OptionContract;

class Option implements OptionContract
{
    /**
     * Get the value of an option by its key.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $value = Cache::rememberForever($this->getCacheKey($key), function () use ($key) {
            $option = DB::table('options')->where('key', $key)->first();

            return $option ? $this->parseValue($option->value) : null;
        });

        return $value ?? $default;
    }

    /**
     * Create or update an option's value by its key.
     */
    public function put(string|array $key, mixed $value = null): bool
    {
        if (is_array($key)) {
            if (! Arr::isAssoc($key)) {
                throw new InvalidArgumentException(
                    'When setting values in the option, you must pass an array of key / value pairs.'
                );
            }

            foreach ($key as $name => $val) {
                $this->persist($name, $val);
            }

            return true;
        }

        return $this->persist($key, $value);
    }

    /**
     * Persist an option in the database.
     */
    protected function persist(string $key, mixed $value): bool
    {
        if (! is_string($value)) {
            $value = json_encode($value, JSON_THROW_ON_ERROR);
        }

        $isPersisted = DB::table('options')->updateOrInsert(
            ['key' => $key],
            ['value' => $value]
        );

        if ($isPersisted) {
            $this->forgetCache($key);
        }

        return $isPersisted;
    }

    /**
     * Get the cache key for the given option key.
     */
    protected function getCacheKey(string $key): string
    {
        return $this->getPrefix().$key;
    }

    /**
     * Parse the option value back into its correct type.
     */
    protected function parseValue(?string $value): mixed
    {
        if ($value === null) {
            return null;
        }

        $decoded = json_decode($value, true);

        return json_last_error() === JSON_ERROR_NONE ? $decoded : $value;
    }

    /**
     * Get the prefix to be used for subdomain-based options.
     */
    protected function getPrefix(): string
    {
        return app()->runningInConsole()
            ? 'console.option.'
            : request()->getHttpHost().'.option.';
    }

    /**
     * Forget the cache for the given option key.
     */
    protected function forgetCache(string $key): bool
    {
        return Cache::forget($this->getCacheKey($key));
    }
}
