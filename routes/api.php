<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\TodoListController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);

    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

    Route::get('/todolist', [TodoListController::class, 'index']);
    Route::get('/todolist/{id}', [TodoListController::class, 'show']);
    Route::post('/todolist', [TodoListController::class, 'store']);
    Route::put('/todolist/{id}', [TodoListController::class, 'update']);
    Route::delete('/todolist/{id}', [TodoListController::class, 'destroy']);
});
