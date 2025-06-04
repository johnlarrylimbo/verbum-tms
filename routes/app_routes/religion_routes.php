<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Pages\Religion\Religion;

Route::prefix('religion')->middleware(['auth'])->group(function() {
    Route::get('/', Religion::class)->lazy();
});
