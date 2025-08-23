<?php

namespace [[rootNamespace]]\Http\Controllers\Api;

use AhsanDev\Support\Field;
use Illuminate\Http\Request;
use [[rootNamespace]]\Http\Requests\ProfileRequest;

class ProfileController
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();

        return Field::make()
            ->field('avatar', $user->avatar)
            ->field('name', $user->name)
            ->field('email', $user->email);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return new ProfileRequest($request, auth()->user());
    }
}
