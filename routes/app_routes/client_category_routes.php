<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Pages\ClientCategory\ClientCategory;

Route::prefix('client-category')->middleware(['auth'])->group(function() {
    Route::get('/', ClientCategory::class)->lazy();
});
