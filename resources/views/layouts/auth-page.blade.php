<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        {{-- <style>
            /* Hide scrollbar for WebKit browsers */
            ::-webkit-scrollbar {
                width: 0;
                height: 0;
            }

            /* Hide scrollbar for IE, Edge, and Firefox */
            /* html {
                -ms-overflow-style: none;
                scrollbar-width: none;
            } */
        </style> --}}
    </head>
    <body class="min-h-screen font-sans antialiased">
        {{-- <div class="bg-gray-100">
            <livewire:layouts.navigation.landing-page.nav />

            <x-mary-main with-nav fullWidth>

                <livewire:layouts.navigation.landing-page.sidebar />


                <x-slot:content>
                    {{ $slot }}
                </x-slot:content>
            </x-mary-main>
        </div> --}}
        {{-- <livewire:layouts.navigation.main.nav /> --}}

        {{ $slot }}
    </body>
</html>
