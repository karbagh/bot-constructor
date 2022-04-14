<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\v1\API\Bot\BotController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can registe r API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::prefix('bot')->name('bot.')->group(function () {
    Route::prefix('webhook')->group(function () {
        Route::any('', [BotController::class, 'webhook']);
        Route::any('viber', [BotController::class, 'viberWebhook']);
        Route::any('fb', [BotController::class, 'facebookWebhook']);
        Route::any('vk', [BotController::class, 'vkWebhook']);
    });
    Route::prefix('message')->name('message.')->group(function () {
        Route::get('send', [BotController::class, 'test'])->name('test');
        Route::any('viber', [BotController::class, 'viber'])->name('viber');
        Route::any('fb', [BotController::class, 'facebook'])->name('facebook');
        Route::any('vk', [BotController::class, 'vk'])->name('vk');
    });
});
