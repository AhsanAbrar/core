<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ option('app_direction', 'ltr') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, interactive-widget=resizes-content">

    <link rel="shortcut icon" href="/favicon.png" type="image/x-icon">
    <link rel="icon" href="/favicon.png" type="image/x-icon">

    <title>{{ config('app.name') }}</title>

    <link rel="preconnect" href="https://rsms.me/">
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">

    <style type="text/css">
        html, body {
            margin: 0;
        }

        .loader-wrapper {
            display: flex;
            height: 100vh;
            align-items: center;
            justify-content: center;
        }

        .loader-icon {
            height: 5rem;
            width: 5rem;
            margin-top: -4rem;
            color: #3b82f6;
        }

        @media (prefers-color-scheme: dark) {
            body {
                background-color: #020617;
            }
            .loader-icon {
                color: #60a5fa;
            }
        }
    </style>

    @appData([[rootNamespace]]\Support\AppData::class)
    @viteTags('[[name]]')
</head>
<body class="bg-gray-100">
    <div id="app">
        <div class="loader-wrapper" aria-busy="true" aria-label="Loading application">
            <div class="loader-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M16.5 6a3 3 0 00-3-3H6a3 3 0 00-3 3v7.5a3 3 0 003 3v-6A4.5 4.5 0 0110.5 6h6z" />
                    <path d="M18 7.5a3 3 0 013 3V18a3 3 0 01-3 3h-7.5a3 3 0 01-3-3v-7.5a3 3 0 013-3H18z" />
                </svg>
            </div>
        </div>
    </div>
</body>
</html>
