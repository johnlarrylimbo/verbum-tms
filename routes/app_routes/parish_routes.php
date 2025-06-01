<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Pages\Parish\Parish;

Route::prefix('parish')->middleware(['auth'])->group(function() {
    Route::get('/', Parish::class)->lazy();
});
