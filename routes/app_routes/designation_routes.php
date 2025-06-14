<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Pages\Designation\Designation;

Route::prefix('designation')->middleware(['auth'])->group(function() {
    Route::get('/', Designation::class)->lazy();
});
