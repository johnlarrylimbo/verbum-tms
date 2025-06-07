<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Pages\LGUType\LGUType;

Route::prefix('lgu-type')->middleware(['auth'])->group(function() {
    Route::get('/', LGUType::class)->lazy();
});
