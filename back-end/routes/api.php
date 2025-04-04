<?php

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
});