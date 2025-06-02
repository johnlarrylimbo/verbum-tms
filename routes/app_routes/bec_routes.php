<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Pages\BasicEcclesialCommunity\BasicEcclesialCommunity;

Route::prefix('bec')->middleware(['auth'])->group(function() {
    Route::get('/', BasicEcclesialCommunity::class)->lazy();
});
