<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Pages\Citizenship\Citizenship;

Route::prefix('citizenship')->middleware(['auth'])->group(function() {
    Route::get('/', Citizenship::class)->lazy();
});
