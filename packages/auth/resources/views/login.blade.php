<x-auth-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full"
                type="email"
                name="email"
                :value="old('email')"
                required
                autofocus
                autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                type="password"
                name="password"
                required
                autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <div class="inline-flex items-center">
                <div class="group grid size-4 grid-cols-1">
                    <input id="remember_me" type="checkbox" name="remember"
                        class="col-start-1 row-start-1 appearance-none rounded-sm border border-gray-300 bg-white
                        checked:border-indigo-600 checked:bg-indigo-600 indeterminate:border-indigo-600 indeterminate:bg-indigo-600
                        focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600
                        disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100
                        dark:border-white/10 dark:bg-white/5 dark:checked:border-indigo-500 dark:checked:bg-indigo-500
                        dark:indeterminate:border-indigo-500 dark:indeterminate:bg-indigo-500
                        dark:focus-visible:outline-indigo-500 dark:disabled:border-white/5 dark:disabled:bg-white/10
                        dark:disabled:checked:bg-white/10 forced-colors:appearance-auto" />

                    <svg viewBox="0 0 14 14" fill="none"
                        class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white
                        group-has-disabled:stroke-gray-950/25 dark:group-has-disabled:stroke-white/25">
                        <path d="M3 8L6 11L11 3.5"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="opacity-0 group-has-checked:opacity-100" />
                        <path d="M3 7H11"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="opacity-0 group-has-indeterminate:opacity-100" />
                    </svg>
                </div>

                <label for="remember_me" class="ms-2 text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Remember me') }}
                </label>
            </div>
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100
                rounded-md focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500
                dark:focus:ring-offset-gray-800"
                href="/forgot-password">
                {{ __('Forgot your password?') }}
            </a>

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-auth-layout>
