<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Pages\IslandGroup\IslandGroup;

Route::prefix('island-groups')->middleware(['auth'])->group(function() {
    Route::get('/', IslandGroup::class)->lazy();
});
