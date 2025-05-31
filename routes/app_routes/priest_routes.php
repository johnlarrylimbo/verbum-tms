<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Pages\Priest\Priest;

Route::prefix('priest')->middleware(['auth'])->group(function() {
    Route::get('/', Priest::class)->lazy();
});
