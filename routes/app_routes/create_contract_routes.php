<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Pages\Contract\CreateContract;

Route::prefix('create-contract')->middleware(['auth'])->group(function() {
    Route::get('/', CreateContract::class)->name('contract.create_new_contract')->lazy();
});
