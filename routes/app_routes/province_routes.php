<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Pages\Province\Province;

Route::prefix('provinces')->middleware(['auth'])->group(function() {
    Route::get('/', Province::class)->lazy();
});
