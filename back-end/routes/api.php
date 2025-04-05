<?php

use App\Http\Controllers\MusicController;
use App\Http\Controllers\RecomendationsController;
use App\Http\Controllers\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix("/users")->group(function () {
    Route::get('/', [UsersController::class, "getAllUsers"]);
    Route::get('/{id}', [UsersController::class, "getUserById"]);
    Route::post('/', [UsersController::class, "createUser"]);
    Route::delete('/{id}', [UsersController::class, "deleteUser"]);
});

Route::prefix("/recomendations")->group(function () {
    Route::get("/",[RecomendationsController::class, "getAllRecomendations"]);
    Route::post('/',[RecomendationsController::class, "createRecomendation"]);
});

Route::prefix("/songs")->group(function () {
    Route::get("/",[MusicController::class, "getAllSongs"]);
    Route::post('/',[MusicController::class, "approveOrCreateMusic"]);
});