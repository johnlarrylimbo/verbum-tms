<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Pages\ClientProfiling\ClientProfiling;

Route::prefix('client-profiling')->middleware(['auth'])->group(function() {
    Route::get('/', ClientProfiling::class)->lazy();
});
