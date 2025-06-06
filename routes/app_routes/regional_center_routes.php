<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Pages\RegionalCenter\RegionalCenter;

Route::prefix('regional-centers')->middleware(['auth'])->group(function() {
    Route::get('/', RegionalCenter::class)->lazy();
});
