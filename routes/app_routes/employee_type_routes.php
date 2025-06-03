<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Pages\EmployeeType\EmployeeType;

Route::prefix('employee-type')->middleware(['auth'])->group(function() {
    Route::get('/', EmployeeType::class)->lazy();
});
