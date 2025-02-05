<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Pages\AccessPermission\WireAccessPermission;

Route::prefix('request-access')->middleware(['auth'])->group(function() {
    Route::get('/', WireAccessPermission::class)->lazy();
});
