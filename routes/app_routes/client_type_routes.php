<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Pages\ClientType\ClientType;

Route::prefix('client-type')->middleware(['auth'])->group(function() {
    Route::get('/', ClientType::class)->lazy();
});
