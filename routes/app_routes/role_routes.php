<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Pages\Role\Role;

Route::prefix('roles')->middleware(['auth'])->group(function() {
    Route::get('/', Role::class)->lazy();
});
