<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Pages\ContractType\ContractType;

Route::prefix('contract-type')->middleware(['auth'])->group(function() {
    Route::get('/', ContractType::class)->lazy();
});
