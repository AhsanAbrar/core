<?php

namespace VueExample\Support;

// use Illuminate\Support\Facades\Auth;
use JsonSerializable;

class AppData implements JsonSerializable
{
    protected $data = [];

    /**
     * Create a new instance.
     */
    public function __construct()
    {
        $this->handle();
    }

    /**
     * Prepare data.
     */
    protected function handle(): void
    {
        $this->data = [
            'app_name' => config('app.name'),
            'csrf_token' => csrf_token(),
            'debug' => config('app.debug'),
            'header_logo' => option('app_logo'),
            'prefix' => null,
            'is_super_admin' => true,
            'permissions' => [],
            'translations' => json_decode(file_get_contents(base_path('packages/vue-example/lang/'.option('app_locale', 'en').'.json')), true),
            // 'user' => Auth::user()->only(['id', 'name', 'email', 'avatar']),
        ];
    }

    /**
     * Prepare the field for JSON serialization.
     */
    public function jsonSerialize(): array
    {
        return $this->data;
    }
}
