<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Pages\Barangay\Barangay;

Route::prefix('barangay')->middleware(['auth'])->group(function() {
    Route::get('/', Barangay::class)->lazy();
});
