<?php

use App\Http\Controllers\Api\NotificationApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Powers the navbar bell's live badge, authenticated via the browser's
// existing session cookie (Sanctum stateful mode) rather than a token.
Route::middleware('auth:sanctum')->prefix('notifications')->group(function () {
    Route::get('/unread-count', [NotificationApiController::class, 'unreadCount']);
    Route::get('/recent', [NotificationApiController::class, 'recent']);
});
