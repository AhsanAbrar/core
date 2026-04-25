<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    dir="{{ option('app_direction', 'ltr') }}"
    class="{{ option('app_theme', 'light') === 'dark' ? 'dark' : '' }}"
    data-shell
>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, interactive-widget=resizes-content">

    <link rel="icon" href="/favicon.png">

    <title>{{ config('app.name') }}</title>

    <link rel="preconnect" href="https://rsms.me/">
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">

    <!-- App shell: runs only before Vue mounts (html[data-shell] exists) -->
    <style>
        html,
        body {
            margin: 0;
        }

        html[data-shell] {
            --shell-bg: #f3f4f6;
            --shell-accent: #7c3aed;

            background: var(--shell-bg);
        }

        html.dark[data-shell] {
            --shell-bg: #020617;
            --shell-accent: #a78bfa;
        }

        html[data-shell] body {
            background: transparent;
        }

        html[data-shell] .shell-loader {
            display: flex;
            height: 100vh;
            height: 100dvh;
            align-items: center;
            justify-content: center;
        }

        html[data-shell] .shell-loader__icon {
            width: 5rem;
            height: 5rem;
            margin-top: -4rem;
            color: var(--shell-accent);
        }
    </style>

    @appData([[rootNamespace]]\Support\AppData::class)
    @viteTags('[[name]]')
</head>

<body>
    <div id="app">
        <div
            class="shell-loader"
            role="status"
            aria-live="polite"
            aria-busy="true"
            aria-label="Loading application"
        >
            <div class="shell-loader__icon">
                <svg
                    aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    fill="currentColor"
                >
                    <path d="M16.5 6a3 3 0 00-3-3H6a3 3 0 00-3 3v7.5a3 3 0 003 3v-6A4.5 4.5 0 0110.5 6h6z" />
                    <path d="M18 7.5a3 3 0 013 3V18a3 3 0 01-3 3h-7.5a3 3 0 01-3-3v-7.5a3 3 0 013-3H18z" />
                </svg>
            </div>
        </div>
    </div>
</body>
</html>
