<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Pages\Congregation\Congregation;

Route::prefix('congregation')->middleware(['auth'])->group(function() {
    Route::get('/', Congregation::class)->lazy();
});
