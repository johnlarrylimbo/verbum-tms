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

    <div class="min-h-screen flex items-center justify-center bg-white px-4 py-12">
        <div class="w-full max-w-md">

            <!-- Logo Centered at Top -->
            <div class="flex justify-center mb-6">
                {{-- <img 
                    class="w-24 md:w-36 lg:w-40 h-auto" 
                    src="{{ asset('images/eclessia_flow_logo.png') }}" 
                    alt="Ecclesia Flow" 
                /> --}}
                <img 
                    class="w-29 md:w-39 lg:w-45 h-auto" 
                    src="{{ asset('images/eclessia_flow_logo3.png') }}" 
                    alt="Ecclesia Flow" 
                />
            </div>

            <!-- Welcome Text -->
            <div class="mb-6 text-center">
                <h5 class="font-bold text-gray-900" style="margin-bottom: 15px !important;">EcclesiaFlow [ Beta Version 2025 ]</h5>
                <p class="text-gray-600">To help protect your personal information, please log in to your account.</p>
            </div>

            <!-- Login Form -->
            <x-mary-form wire:submit="login_process">

                <!-- Username -->
                <x-mary-input label="Username"
                    wire:model="form.username"
                    id="username"
                    name="username"
                    type="text"
                    required
                    autofocus
                    autocomplete="username"
                />

                <!-- Password -->
                <x-mary-input label="Password"
                    wire:model="form.password"
                    id="password"
                    name="password"
                    type="password"
                    required
                    autocomplete="current-password"
                />

                <!-- Remember Me -->
                <div class="mt-4">
                    <x-mary-checkbox
                        id="remember"
                        name="remember"
                        label="Remember me"
                        wire:model="form.remember"
                        class="text-indigo-600 border-gray-300 rounded shadow-sm focus:ring-indigo-500"
                    />
                     <!-- Submit Button -->
                {{-- <x-slot:actions> --}}
                    <div class="mt-6 text-center">
                        <x-mary-button label="LOG IN"
                            type="submit"
                            spinner="login"
                            class="w-full py-3 text-sm font-semibold tracking-widest text-white uppercase bg-gray-800 rounded-md hover:bg-gray-700 focus:bg-gray-700"
                        />
                    </div>
                {{-- </x-slot:actions> --}}
                </div>

               

            </x-mary-form>
        </div>
    </div>
</div>
