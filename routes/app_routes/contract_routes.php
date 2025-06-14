<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Pages\Contract\Contract;

Route::prefix('contracts')->middleware(['auth'])->group(function() {
    Route::get('/', Contract::class)->lazy();
});
