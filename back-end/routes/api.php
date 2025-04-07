<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\MusicController;
use App\Http\Controllers\RecomendationsController;
use App\Http\Controllers\UsersController;
use App\Http\Middleware\AdminVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post("/login", [LoginController::class, "authLogin"]);
Route::post('/users', [UsersController::class, "createUser"]);
Route::get("/songs",[MusicController::class, "getAllSongs"]);

Route::middleware('auth:sanctum')->group(function () {
   
    Route::prefix("/users")->group(function () {
        Route::get('/', [UsersController::class, "getAllUsers"]);
        Route::get('/{id}', [UsersController::class, "getUserById"]);
        Route::delete('/{id}', [UsersController::class, "deleteUser"]);
    });

    Route::prefix("/recomendations")->group(function () {
        Route::get("/",[RecomendationsController::class, "getAllRecomendations"]);
        Route::post('/',[RecomendationsController::class, "createRecomendation"]);
    });

    Route::prefix("/songs")->middleware(['auth:sanctum', 'is_admin'])->group(function () {
        Route::post('/',[MusicController::class, "approveOrCreateMusic"]);
    });
    
});