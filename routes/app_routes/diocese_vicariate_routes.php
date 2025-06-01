<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Pages\DioceseVicariate\DioceseVicariate;

Route::prefix('vicariate')->middleware(['auth'])->group(function() {
    Route::get('/', DioceseVicariate::class)->lazy();
});
