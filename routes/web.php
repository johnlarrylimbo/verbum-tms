<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Livewire\Pages\Dashboard\WireDashboard;
use App\Livewire\Pages\Auth\WireAuth;
use Barryvdh\DomPDF\Facade\Pdf;

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

Route::middleware(['auth'])->group(function () {
    Route::get('/print-client-profile/{id}', function ($id) {
        // Call stored procedure (adjust the name and parameters as needed)
        $clientData = DB::select('CALL pr_datims_report_client_profile_by_id_sel_(?)', [$id]);

        // Optional: Handle case if no data is returned
        if (empty($clientData)) {
            abort(404, 'Client not found');
        }

        // Convert result to object/array as needed
        $client = $clientData[0]; // Assuming the stored procedure returns a single row

        $pdf = Pdf::loadView('pdf.client-profile', compact('client'));

        return $pdf->stream('client-profile.pdf');
    });
});

Route::middleware(['auth'])->group(function () {
    Route::get('/print-contract-by-id/{id}', function ($id) {
        // Call stored procedure (adjust the name and parameters as needed)
        $contractData = DB::select('CALL pr_datims_contract_form_payment_by_id_sel(?)', [$id]);

        // Optional: Handle case if no data is returned
        if (empty($contractData)) {
            abort(404, 'Contract not found');
        }

        // Convert result to object/array as needed
        $contract = $contractData[0]; // Assuming the stored procedure returns a single row

        $pdf = Pdf::loadView('pdf.contract', compact('contract'));

        return $pdf->stream('contract.pdf');
    });
});

Route::middleware(['auth'])->group(function () {
    Route::get('/print-payment-summary-by-id/{id}', function ($id) {
        $summary = DB::select('CALL pr_datims_contract_payment_summary_by_id_sel(?)', [$id]);

            if (empty($summary)) {
                abort(404, 'Contract not found');
            }

						$detail = $summary[0]; // Assuming the stored procedure returns a single row

            $pdf = Pdf::loadView('pdf.payment-summary', compact('summary', 'detail'));

            return $pdf->stream('payment-summary.pdf');
    });
});

Route::middleware(['auth'])->group(function () {
    Route::get('/or-by-id/{id}', function ($id) {
        // Call stored procedure (adjust the name and parameters as needed)
        $result = DB::select('CALL pr_datims_official_receipt_by_id_sel_(?)', [$id]);

        // Optional: Handle case if no data is returned
        if (empty($result)) {
            abort(404, 'Client not found');
        }

        // Convert result to object/array as needed
        $detail = $result[0]; // Assuming the stored procedure returns a single row

        $pdf = Pdf::loadView('pdf.official-receipt', compact('detail', 'result'))->setPaper('letter', 'portrait');

        return $pdf->stream('official-receipt.pdf');
    });
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

require __DIR__.'/app_routes/island_group_routes.php';

require __DIR__.'/app_routes/regional_center_routes.php';

require __DIR__.'/app_routes/region_routes.php';

require __DIR__.'/app_routes/citizenship_routes.php';

require __DIR__.'/app_routes/lgu_type_routes.php';

require __DIR__.'/app_routes/city_municipality_routes.php';

require __DIR__.'/app_routes/client_management_routes.php';

require __DIR__.'/app_routes/view_client_profile_by_id_routes.php';

require __DIR__.'/app_routes/designation_routes.php';

require __DIR__.'/app_routes/contract_routes.php';

require __DIR__.'/app_routes/create_contract_routes.php';

require __DIR__.'/app_routes/monitoring_board_routes.php';


require __DIR__.'/auth.php';
