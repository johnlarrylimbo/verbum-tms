<?php

use App\Models\User;
use App\Models\UserRole;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $user = User::create($validated);

        event(new Registered($user));

        /**
         * Assign the user the default role of `user`.
         */
        UserRole::create([
            'user_id' => $user->user_id,
            'role_id' => 2, /** this role_id is `Contributor` */
            'statuscode' => 1000
        ]);

        Auth::login($user);

        $this->redirect(route('register', absolute: false), navigate: true);
    }
}; ?>

{{-- <form wire:submit="register">
    <!-- Name -->
    <div>
        <x-input-label for="name" :value="__('Name')" />
        <x-text-input wire:model="name" id="name" class="block w-full mt-1" type="text" name="name" required autofocus autocomplete="name" />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <!-- Email Address -->
    <div class="mt-4">
        <x-input-label for="email" :value="__('Email')" />
        <x-text-input wire:model="email" id="email" class="block w-full mt-1" type="email" name="email" required autocomplete="username" />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    <!-- Password -->
    <div class="mt-4">
        <x-input-label for="password" :value="__('Password')" />

        <x-text-input wire:model="password" id="password" class="block w-full mt-1"
                        type="password"
                        name="password"
                        required autocomplete="new-password" />

        <x-input-error :messages="$errors->get('password')" class="mt-2" />
    </div>

    <!-- Confirm Password -->
    <div class="mt-4">
        <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

        <x-text-input wire:model="password_confirmation" id="password_confirmation" class="block w-full mt-1"
                        type="password"
                        name="password_confirmation" required autocomplete="new-password" />

        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
    </div>

    <div class="flex items-center justify-end mt-4">
        <a class="text-sm text-gray-600 underline rounded-md hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}" wire:navigate>
            {{ __('Already registered?') }}
        </a>

        <x-primary-button class="ms-4">
            {{ __('Register') }}
        </x-primary-button>
    </div>
</form> --}}
<div>
    <div class="grid min-h-screen lg:grid-cols-2">
        <div class="flex flex-col items-center justify-center bg-pff-primary mobile-tablet:hidden">
            <div>
                <a href="/" wire:navigate>
                    <img class="w-auto h-auto"
                        src="{{ asset('images/logo/functional_foods_logo_v1.png') }}"
                        alt="Functional Foods Logo"
                    >
                </a>
            </div>
        </div>
        <div class="flex items-center justify-center bg-white mobile-tablet:flex-col">
            <div class="flex flex-col items-center justify-center mobile-tablet:visible lg:hidden">
                <a href="/" wire:navigate>
                    <img class="w-auto h-40"
                        src="{{ asset('images/logo/functional_foods_logo_v1.png') }}"
                        alt="Functional Foods Logo"
                    >
                </a>
            </div>
            <div class="w-full max-w-lg p-8">
                <div class="flex flex-col w-full border-opacity-50">
                    <div class="mb-4 text-center">
                        <h1 class="mb-4 text-4xl font-bold text-gray-900">Welcome to Functional Foods PH</h1>
                        <p class="text-gray-600">Register now to contribute</p>
                    </div>

                    <x-mary-form wire:submit="register">
                        <!-- Name -->
                        <div>
                            {{-- <x-input-label for="name" :value="__('Name')" />
                            <x-text-input wire:model="name" id="name" class="block w-full mt-1" type="text" name="name" required autofocus autocomplete="name" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" /> --}}
                            <x-mary-input label="Name"
                                type="text"
                                wire:model="name"
                                id="name"
                                name="name"
                                class="block w-full mt-1"
                                required
                                autofocus
                                autocomplete="name" />
                        </div>

                        <!-- Email Address -->
                        <div class="mt-4">
                            {{-- <x-input-label for="email" :value="__('Email')" />
                            <x-text-input wire:model="email" id="email" class="block w-full mt-1" type="email" name="email" required autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" /> --}}
                            <x-mary-input label="Email"
                                type="email"
                                wire:model="email"
                                id="email"
                                name="email"
                                class="block w-full mt-1"
                                required
                                autocomplete="username" />
                        </div>

                        <!-- Password -->
                        <div class="mt-4">
                            {{-- <x-input-label for="password" :value="__('Password')" />

                            <x-text-input wire:model="password" id="password" class="block w-full mt-1"
                                            type="password"
                                            name="password"
                                            required autocomplete="new-password" />

                            <x-input-error :messages="$errors->get('password')" class="mt-2" /> --}}
                            <x-mary-password label="Password"
                                wire:model="password"
                                class="block w-full mt-1"
                                required
                                autocomplete="new-password"
                                clearable />
                        </div>

                        <!-- Confirm Password -->
                        <div class="mt-4">
                            {{-- <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

                            <x-text-input wire:model="password_confirmation" id="password_confirmation" class="block w-full mt-1"
                                            type="password"
                                            name="password_confirmation" required autocomplete="new-password" />

                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" /> --}}
                            <x-mary-password label="Confirm Password"
                                wire:model="password_confirmation"
                                class="block w-full mt-1"
                                required
                                autocomplete="new-password"
                                clearable />
                        </div>

                        <x-slot:actions>
                            <div class="flex items-center justify-evenly">
                                <a class="text-sm text-gray-600 underline rounded-md hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}" wire:navigate>
                                    {{ __('Already registered?') }}
                                </a>
                                <div class="ms-4">
                                    <x-mary-button label="Register" type="submit" spinner="register" class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase transition duration-150 ease-in-out bg-gray-800 border border-transparent rounded-md hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"/>
                                </div>
                            </div>
                        </x-slot:actions>
                    </x-mary-form>
                </div>
            </div>
        </div>
    </div>
</div>
