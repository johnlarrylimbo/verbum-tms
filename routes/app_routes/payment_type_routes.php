<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Pages\PaymentType\PaymentType;

Route::prefix('payment-type')->middleware(['auth'])->group(function() {
    Route::get('/', PaymentType::class)->lazy();
});
