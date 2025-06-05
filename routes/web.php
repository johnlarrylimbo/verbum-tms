<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Livewire\Pages\Dashboard\WireDashboard;
use App\Livewire\Pages\Auth\WireAuth;

use Livewire\Volt\Volt;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware('guest')->group(function () {
    // Volt::route('register', 'pages.auth.register')
    //     ->name('register');

    Volt::route('login', 'pages.auth.login')
        ->name('login');

        Volt::route('/', 'pages.auth.login')
        ->name('login');

    Volt::route('forgot-password', 'pages.auth.forgot-password')
        ->name('password.request');

    Volt::route('reset-password/{token}', 'pages.auth.reset-password')
        ->name('password.reset');

    // Route::get('auth/{provider}/redirect', [SocialiteController::class, 'redirectSocial'])
    //     ->name('socialite.redirect');

    // Route::get('auth/{provider}/callback', [SocialiteController::class, 'callbackSocial'])
    //     ->name('socialite.callback');

});

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/dashboard', WireDashboard::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/app_routes/barangay_routes.php';

require __DIR__.'/app_routes/priest_routes.php';

require __DIR__.'/app_routes/congregation_routes.php';

require __DIR__.'/app_routes/diocese_routes.php';

require __DIR__.'/app_routes/diocese_vicariate_routes.php';

require __DIR__.'/app_routes/parish_routes.php';

require __DIR__.'/app_routes/bec_routes.php';

require __DIR__.'/app_routes/payment_type_routes.php';

require __DIR__.'/app_routes/client_category_routes.php';

require __DIR__.'/app_routes/client_type_routes.php';

require __DIR__.'/app_routes/contract_category_routes.php';

require __DIR__.'/app_routes/contract_type_routes.php';

require __DIR__.'/app_routes/contract_category_type_routes.php';

require __DIR__.'/app_routes/employee_type_routes.php';

require __DIR__.'/app_routes/religion_routes.php';

require __DIR__.'/app_routes/role_routes.php';

require __DIR__.'/app_routes/client_profiling_routes.php';

require __DIR__.'/app_routes/province_routes.php';

require __DIR__.'/auth.php';
