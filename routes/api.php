<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post(
    '/register',
     \App\Http\Controllers\Api\Auth\RegisterController::class
)->middleware('auth:sanctum');

Route::post(
    '/login',
     \App\Http\Controllers\Api\Auth\LoginController::class
);
