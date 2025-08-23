<?php

namespace [[rootNamespace]]\Http\Requests;

use AhsanDev\Support\Requests\FormRequest;
use AhsanDev\Support\UpdateEnvConfig;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use [[rootNamespace]]\Notifications\TestMail;

class SettingsEmailRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'mail_driver' => 'required',
            'mail_host' => 'required',
            'mail_port' => 'required',
            'mail_username' => 'required',
            'mail_password' => 'required',
            'mail_encryption' => 'nullable',
            'mail_verify_peer' => 'nullable',
            'mail_from_address' => 'required|email',
            'mail_from_name' => 'required',
        ];
    }

    /**
     * Get the laravel app configs to change.
     */
    public function configs(): array
    {
        return [
            'MAIL_MAILER' => 'mail_driver',
            'MAIL_HOST' => 'mail_host',
            'MAIL_PORT' => 'mail_port',
            'MAIL_USERNAME' => 'mail_username',
            'MAIL_PASSWORD' => 'mail_password',
            'MAIL_ENCRYPTION' => 'mail_encryption',
            'MAIL_VERIFY_PEER' => 'mail_verify_peer',
            'MAIL_FROM_ADDRESS' => 'mail_from_address',
            'MAIL_FROM_NAME' => 'mail_from_name',
        ];
    }

    /**
     * Database Transaction.
     */
    public function transaction()
    {
        config([
            'mail.default' => 'smtp',
            'mail.mailers.smtp.host' => $this->request->mail_host,
            'mail.mailers.smtp.port' => $this->request->mail_port,
            'mail.mailers.smtp.encryption' => $this->request->mail_encryption,
            'mail.mailers.smtp.username' => $this->request->mail_username,
            'mail.mailers.smtp.password' => $this->request->mail_password,
            'mail.mailers.smtp.timeout' => 30,
            'mail.mailers.smtp.verify_peer' => $this->request->mail_verify_peer,
            'mail.from.address' => $this->request->mail_from_address,
            'mail.from.name' => $this->request->mail_from_name,
        ]);

        try {
            Notification::route('mail', 'freshabout@gmail.com')
                ->notify(new TestMail($this->request->mail_from_address));
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), 1);

            return $e->getMessage();
        }

        DB::transaction(function () {
            new UpdateEnvConfig($this->request, $this->configs());

            option(
                $this->request->all()
            );

            option(['email_config' => true]);
        });
    }
}
