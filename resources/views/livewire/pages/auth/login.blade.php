<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login_process(): void
    {
        // $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(route('dashboard', absolute: false), navigate: true);
        // return redirect()->intended(RouteServiceProvider::HOME);
    }
}; ?>

<div>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="grid min-h-screen lg:grid-cols-2">
        <div class="flex flex-col items-center justify-center bg-pff-primary mobile-tablet:hidden">
            <div>
                {{-- <a href="/" wire:navigate> --}}
                    <img class="login-logo"
                        src="{{ asset('images/logo/uic-logo.png') }}"
                        alt="Functional Foods Logo"
                    >
                {{-- </a> --}}
            </div>
        </div>
        <div class="flex items-center justify-center bg-white mobile-tablet:flex-col">
            {{-- <div class="flex flex-col items-center justify-center mobile-tablet:visible lg:hidden">
                <a href="/" wire:navigate>
                    <img class="w-auto h-40"
                        src="{{ asset('images/logo/functional_foods_logo_v1.png') }}"
                        alt="Functional Foods Logo"
                    >
                </a>
            </div> --}}
            <div class="w-full max-w-md">
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />


                <div class="flex flex-col w-full border-opacity-50">
                    <div class="mb-4 text-center">
                        <h1 class="text-4xl font-bold text-gray-900">Welcome back!</h1>
                        <p class="text-gray-600">Log in to your account</p>
                    </div>
                    {{-- <livewire:components.socialite-links /> --}}

                    {{-- <div class="divider">OR</div> --}}

                    <x-mary-form wire:submit="login_process">
                        <!-- Email Address -->
                        {{-- <div>
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input wire:model="form.email" id="email" class="block w-full mt-1" type="email" name="email" required autofocus autocomplete="username" />
                            <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
                        </div> --}}
                        <x-mary-input label="username"
                            wire:model="form.username"
                            id="username"
                            name="username"
                            type="username"
                            required autofocus
                            autocomplete="username"
                        />

                        <!-- Password -->
                        {{-- <div class="mt-4">
                            <x-input-label for="password" :value="__('Password')" />
                            <x-text-input wire:model="form.password" id="password" class="block w-full mt-1"
                                            type="password"
                                            name="password"
                                            required autocomplete="current-password" />
                            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
                        </div> --}}
                        <x-mary-input
                            label="Password"
                            wire:model="form.password"
                            id="password"
                            name="password"
                            type="password"
                            required autofocus
                            autocomplete="current-password"
                        />

                        <!-- Remember Me -->
                        <div class="block mt-4">
                            <div class="inline-flex items-center text-sm text-gray-600">
                                <x-mary-checkbox id="remember" name="remember" label="Remember me" wire:model="form.remember" class="text-indigo-600 border-gray-300 rounded shadow-sm focus:ring-indigo-500"/>
                            </div>
                        </div>

                        <x-slot:actions>
                            <div class="flex items-center justify-evenly">
                                @if (Route::has('password.request'))
                                    <a class="text-sm text-gray-600 underline rounded-md hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}" wire:navigate>
                                        {{ __('Forgot your password?') }}
                                    </a>
                                @endif
                                <div class="ms-3">
                                    <x-mary-button label="LOG IN" type="submit" spinner="login" class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase transition duration-150 ease-in-out bg-gray-800 border border-transparent rounded-md hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"/>
                                </div>
                            </div>
                        </x-slot:actions>
                        {{-- <div class="flex items-center justify-center">
                            <a class="text-sm text-gray-600 underline rounded-md hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('register') }}" wire:navigate>
                                {{ __("Want to contribute? Register now!") }}
                            </a>
                        </div> --}}
                    </x-mary-form>
                </div>
            </div>
        </div>
    </div>
</div>
