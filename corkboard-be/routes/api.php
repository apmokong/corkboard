<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\BlogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::middleware('auth:api')->group(function () {
    Route::get('/blogs', [BlogController::class, 'index']);
    Route::post('/blogs', [BlogController::class, 'store']);
    Route::put('/blogs/{blog}', [BlogController::class, 'update']);
    Route::delete('/blogs/{blog}', [BlogController::class, 'destroy']);
    Route::patch('/blogs/{blog}/status', [BlogController::class, 'changeStatus']);
});

Route::get('/blogs/{blog}/view', [BlogController::class, 'show']);