<?php

use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\web\ItemController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');



Route::middleware(['auth','verified','admin'])->group(function(){
    Route::get('dashboard', DashboardController::class)->name('dashboard');
    
    Route::prefix('items')->controller(ItemController::class)->group(function () {
        Route::get('items', 'getItems')->name('items.found');
        // Route::get('lost', 'getLostItems')->name('items.lost');
        // Route::get('found', 'getFoundItems')->name('items.found');
    });
});

