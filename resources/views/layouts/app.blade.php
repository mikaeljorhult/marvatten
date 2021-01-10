<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Marvatten') }}</title>

        <link rel="stylesheet" href="{{ mix('css/app.css') }}">

        @livewireStyles

        <script src="{{ mix('js/app.js') }}" defer></script>
    </head>

    <body class="font-sans antialiased">
        <div class="h-screen flex overflow-hidden bg-white">
            <x-navigation />

            <div class="flex flex-col min-w-0 flex-1 overflow-hidden">
                <div class="lg:hidden">
                    <div class="flex items-center justify-between bg-gray-50 border-b border-gray-200 px-4 py-1.5">
                        <div>
                            <h1>{{ config('app.name', 'Marvatten') }}</h1>
                        </div>

                        <div x-data>
                            <button
                                type="button"
                                class="-mr-3 h-12 w-12 inline-flex items-center justify-center rounded-md text-gray-500 hover:text-gray-900"
                                x-on:click.prevent="$dispatch('open-sidebar')"
                            >
                                <span class="sr-only">Open sidebar</span>
                                @svg('heroicon-o-menu', 'h-6 w-6')
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex-1 relative z-0 flex overflow-hidden">
                    <main class="flex-1 relative z-0 overflow-y-auto focus:outline-none xl:order-last" tabindex="0">
                        {{ $slot }}
                    </main>

                    {{ $sidebar ?? '' }}
                </div>
            </div>
        </div>

        @stack('modals')

        @livewireScripts
    </body>
</html>
