<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Pages\CityMunicipality\CityMunicipality;

Route::prefix('city-municipality')->middleware(['auth'])->group(function() {
    Route::get('/', CityMunicipality::class)->lazy();
});
