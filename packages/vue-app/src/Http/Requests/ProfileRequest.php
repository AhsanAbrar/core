<?php

namespace [[rootNamespace]]\Http\Requests;

use AhsanDev\Support\Requests\FormRequest;
use Illuminate\Support\Facades\DB;

class ProfileRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
        ];
    }

    /**
     * Database Transaction.
     */
    public function transaction(): void
    {
        DB::transaction(function () {
            if ($this->request->password) {
                $this->attributes['password'] = bcrypt($this->request->password);
            }

            $this->model->forceFill($this->attributes);

            $this->model->save();
        });
    }
}
