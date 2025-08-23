<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ option('app_direction', 'ltr') }}" class="h-full bg-gray-100">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, interactive-widget=resizes-content">

    <link rel="shortcut icon" href="/favicon.png" type="image/x-icon">
    <link rel="icon" href="/favicon.png" type="image/x-icon">

    <title>{{ config('app.name') }}</title>

    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">

    <style type="text/css">
        html, body {
            margin: 0;
        }
        .h-full {
            height: 100%;
        }
        .bg-gray-100 {
            background-color: #f3f4f6;
        }
        .flex {
            display: flex;
        }
        .items-center {
            align-items: center;
        }
        .justify-center {
            justify-content: center;
        }
        .h-20 {
            height: 5rem;
        }
        .w-20 {
            width: 5rem;
        }
        .text-blue-500 {
            color: #3b82f6;
        }
        .-mt-16 {
            margin-top: -4rem;
        }
    </style>

    @appData
    @viteTags
</head>
<body class="h-full bg-gray-100">
    <div id="app" class="h-full">
        <div class="flex h-full items-center justify-center">
            <div class="h-20 w-20 -mt-16">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="text-blue-500">
                    <path d="M16.5 6a3 3 0 00-3-3H6a3 3 0 00-3 3v7.5a3 3 0 003 3v-6A4.5 4.5 0 0110.5 6h6z" />
                    <path d="M18 7.5a3 3 0 013 3V18a3 3 0 01-3 3h-7.5a3 3 0 01-3-3v-7.5a3 3 0 013-3H18z" />
                </svg>
            </div>
        </div>
    </div>
</body>
</html>
