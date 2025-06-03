<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Pages\ContractCategoryType\ContractCategoryType;

Route::prefix('contract-category-type')->middleware(['auth'])->group(function() {
    Route::get('/', ContractCategoryType::class)->lazy();
});
