<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Pages\Region\Region;

Route::prefix('regions')->middleware(['auth'])->group(function() {
    Route::get('/', Region::class)->lazy();
});
