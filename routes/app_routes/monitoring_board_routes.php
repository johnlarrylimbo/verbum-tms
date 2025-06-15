<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Pages\MonitoringBoard\MonitoringBoard;

Route::prefix('monitoring-board')->middleware(['auth'])->group(function() {
    Route::get('/', MonitoringBoard::class)->lazy();
});
