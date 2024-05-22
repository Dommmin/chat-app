<?php

declare(strict_types=1);

use App\Http\Controllers\ChatController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function (): void {
    Route::get('/', HomeController::class)->name('home');
    Route::resource('/friends', FriendController::class);
    Route::resource('/chats', ChatController::class);
    Route::resource('/chats/{chat:id}/messages', MessageController::class);
});
