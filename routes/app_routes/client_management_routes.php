<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Pages\ClientManagement\ClientManagement;

Route::prefix('client-management')->middleware(['auth'])->group(function() {
    Route::get('/', ClientManagement::class)->lazy();
});
