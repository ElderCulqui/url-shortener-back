<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ShortenedUrlController;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return 'Hello World';
});

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout']) ->name('logout');

Route::middleware('auth:sanctum')->group(function () {
    Route::resource('shortener-url', ShortenedUrlController::class, ['only' => ['store', 'index']]);
});
