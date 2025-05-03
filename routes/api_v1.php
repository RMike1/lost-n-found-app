<?php

use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Home\CategoryController;
use App\Http\Controllers\Api\V1\Home\ChatController;
use App\Http\Controllers\Api\V1\Home\ItemController;
use App\Http\Controllers\Api\V1\Home\LocationController;
use Illuminate\Support\Facades\Route;

// =========================================Auth Routes=============================================

Route::controller(AuthController::class)->group(function () {
    Route::post('/login', 'login')->name('login');
    Route::post('/register', 'register')->name('register');
    Route::post('/forgot-password', 'forgotPassword')->name('password.email');
    Route::post('/reset-password', 'resetPassword')->name('password.reset');
    Route::post('/logout', 'logout')->middleware('auth:sanctum')->name('logout');
});

// ========================================User Locotions Routes========================================

Route::controller(LocationController::class)->group(function () {
    Route::get('/districts', 'getDistricts')->name('districts');
    Route::get('/sectors/{districtId}', 'getSectors')->name('sectors');
    Route::get('/cells/{sectorId}', 'getCells')->name('cells');
    Route::get('/village/{cellId}', 'getVillages')->name('villages');
    Route::get('/villages', 'getAllVillages')->name('allvillages');
});

Route::middleware(['auth:sanctum'])->group(function () {

    // ==========================================user details============================================

    Route::get('user/details', [AuthController::class, 'getUserDetails'])->name('user.details');

    // ========================================Items Routes==============================================

    Route::apiResource('/items', ItemController::class)->only(['index', 'show', 'store'])->names('items');

    Route::controller(ItemController::class)->group(function () {
        Route::post('/items/{item}/favorite', 'toggleFavorite')->name('items.favorite');
        Route::get('/favorites', 'getFavorites')->name('favorites');
        Route::get('/user/items', 'getUserItems')->name('items.user');
    });

    // ========================================Category Routes============================================

    Route::get('/categories', CategoryController::class)->name('categories');

    // ========================================Chats Routes================================================

    Route::controller(ChatController::class)->group(function () {
        Route::post('/message', 'sendMessage')->name('message.send');
        Route::get('conversation/{receiver}/{item}', 'getConversation')->name('conversation');
        Route::get('/conversations', 'conversationsList')->name('conversations');
        Route::get('/messages/unread-count', 'unreadCount')->name('messages.unreadcount');
    });

    // ========================================User Locotions Routes==========================================

    Route::prefix('user')->controller(LocationController::class)->group(function () {
        Route::get('near-by-locations', 'getNearByLocations')->name('user.near-by-locations');
        Route::get('location/{user}', 'getUserLocation')->name('user.location');
    });
});
