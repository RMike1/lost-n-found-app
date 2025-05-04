<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\web\ItemController;

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::prefix('items')->controller(ItemController::class)->middleware(['auth', 'verified'])->group(function(){
    Route::get('lost','getLostItems')->name('items.lost');
    Route::get('found','getFoundItems')->name('items.found');
});
