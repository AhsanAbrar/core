<?php

namespace [[rootNamespace]]\Http\Controllers\Api;

use AhsanDev\Support\Field;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use [[rootNamespace]]\Http\Requests\SettingsEmailRequest;

class SettingsEmailController implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return ['can:setting'];
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Field::make()
            ->field('mail_driver', option('mail_driver', 'smtp'), [['label' => 'smtp', 'value' => 'smtp']])
            ->field('mail_host', option('mail_host'))
            ->field('mail_port', option('mail_port'))
            ->field('mail_username', option('mail_username'))
            ->field('mail_password', option('mail_password'))
            ->field('mail_encryption', option('mail_encryption', 'NULL'), [['label' => 'null', 'value' => 'NULL'], ['label' => 'tls', 'value' => 'tls'], ['label' => 'ssl', 'value' => 'ssl']])
            ->field('mail_verify_peer', option('mail_verify_peer', false), [['label' => 'Verify Peer', 'value' => true]])
            ->field('mail_from_address', option('mail_from_address'))
            ->field('mail_from_name', option('mail_from_name'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return new SettingsEmailRequest($request);
    }
}
