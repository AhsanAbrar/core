<?php

namespace [[rootNamespace]]\Http\Requests;

use AhsanDev\Support\Requests\FormRequest;
use AhsanDev\Support\UpdateEnvConfig;
use Illuminate\Support\Facades\DB;

class SettingsGeneralRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'app_name' => 'required',
            'app_url' => 'required',
            'app_locale' => 'required',
            'app_timezone' => 'required',
            'app_direction' => 'required',
        ];
    }

    /**
     * Get the laravel app configs to change.
     */
    public function configs(): array
    {
        return [
            'APP_NAME' => 'app_name',
            'APP_URL' => 'app_url',
            'APP_TIMEZONE' => 'app_timezone',
        ];
    }

    /**
     * Database Transaction.
     */
    public function transaction(): void
    {
        DB::transaction(function () {
            new UpdateEnvConfig($this->request, $this->configs());

            option(
                $this->request->all()
            );
        });
    }
}
