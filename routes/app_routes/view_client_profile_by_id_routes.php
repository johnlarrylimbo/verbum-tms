<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Pages\ClientProfiling\EditClientProfile;

Route::prefix('view-client-profile-by-id')->middleware(['auth'])->group(function() {
    Route::get('/', EditClientProfile::class)->name('client.view_client_profile_by_id')->lazy();
});
