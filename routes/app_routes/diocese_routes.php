<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Pages\Diocese\Diocese;

Route::prefix('diocese')->middleware(['auth'])->group(function() {
    Route::get('/', Diocese::class)->lazy();
});
