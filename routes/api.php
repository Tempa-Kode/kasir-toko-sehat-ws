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

Route::resource('satuan', \App\Http\Controllers\Api\SatuanController::class)->middleware('auth:sanctum');
Route::resource('kategori-produk', \App\Http\Controllers\Api\KategoriProdukController::class)->middleware('auth:sanctum');
Route::prefix('produk')->group( function () {
    Route::patch('{id}/stock', [\App\Http\Controllers\ProdukController::class, 'updateStock']);
    Route::get('search', [\App\Http\Controllers\ProdukController::class, 'search']);
})->middleware('auth:sanctum');
Route::resource('produk', \App\Http\Controllers\ProdukController::class)->middleware('auth:sanctum');
Route::resource('transaksi', \App\Http\Controllers\TransaksiController::class)->middleware('auth:sanctum');
