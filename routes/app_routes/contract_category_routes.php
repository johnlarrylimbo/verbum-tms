<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Pages\ContractCategory\ContractCategory;

Route::prefix('contract-category')->middleware(['auth'])->group(function() {
    Route::get('/', ContractCategory::class)->lazy();
});
