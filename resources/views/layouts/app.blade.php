<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'EcclesiaFlow v.1') }}</title>

        <!-- For PNG -->
        <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

				<!-- Custom CSS -->
        <link rel="stylesheet" href="{{asset('/assets/css/custom.css')}}">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen font-sans antialiased bg-base-200">

			{{-- NAVBAR mobile only --}}
			<x-mary-nav sticky class="lg:hidden">
        <x-slot:brand>
            <div class="ml-5 pt-5">
							<p class="text-xl"><img 
                    class="w-24 md:w-36 lg:w-40 h-auto" 
                    src="{{ asset('images/eclessia_flow_logo4.png') }}" 
                    alt="Ecclesia Flow" 
                /></p>
						</div>
        </x-slot:brand>
        <x-slot:actions>
            <label for="main-drawer" class="lg:hidden mr-3" style="list-style: none;">
								<x-mary-menu-item  icon="o-queue-list" class="py-4" />
            </label>
        </x-slot:actions>
    	</x-mary-nav>

        <x-mary-toast />

        <x-mary-main class="w-full mx-auto">
        

            {{-- SIDEBAR --}}
            <livewire:layouts.navigation.main.sidebar />

            <!-- Page Content -->
            <x-slot:content>
                {{ $slot }}
            </x-slot:content>

        </x-mary-main>

    </body>
</html>
