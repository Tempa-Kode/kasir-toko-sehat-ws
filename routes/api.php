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
    Route::get('history/{productId}', [\App\Http\Controllers\ProdukController::class, 'stockHistory']);
})->middleware('auth:sanctum');
Route::resource('produk', \App\Http\Controllers\ProdukController::class)->middleware('auth:sanctum');
Route::resource('transaksi', \App\Http\Controllers\TransaksiController::class)->middleware('auth:sanctum');

Route::prefix('users')->group(function () {
    Route::post('search', [\App\Http\Controllers\UserController::class, 'search']);
    Route::patch('{id}/change-password', [\App\Http\Controllers\UserController::class, 'changePassword']);
})->middleware('auth:sanctum');
Route::apiResource('users', \App\Http\Controllers\UserController::class)->except(['store'])->middleware('auth:sanctum');

// Laporan Transaksi
Route::prefix('laporan')->group(function () {
    Route::post('periode', [\App\Http\Controllers\LaporanController::class, 'laporanPeriode']);
    Route::post('periode/pdf', [\App\Http\Controllers\LaporanController::class, 'exportPeriodePdf']);
    Route::post('bulanan', [\App\Http\Controllers\LaporanController::class, 'laporanBulanan']);
    Route::post('bulanan/pdf', [\App\Http\Controllers\LaporanController::class, 'exportBulananPdf']);
    Route::post('produk-terlaris', [\App\Http\Controllers\LaporanController::class, 'produkTerlaris']);
    Route::post('produk-terlaris/pdf', [\App\Http\Controllers\LaporanController::class, 'exportProdukTerlarisPdf']);
})->middleware('auth:sanctum');

// Statistik untuk Chart.js
Route::prefix('statistik')->group(function () {
    Route::get('dashboard', [\App\Http\Controllers\LaporanController::class, 'dashboardStatistik']);
    Route::get('comparison', [\App\Http\Controllers\LaporanController::class, 'statistikComparison']);
    Route::get('tahunan', [\App\Http\Controllers\LaporanController::class, 'statistikTahunan']);
    Route::get('bulanan', [\App\Http\Controllers\LaporanController::class, 'statistikBulanan']);
    Route::get('mingguan', [\App\Http\Controllers\LaporanController::class, 'statistikMingguan']);
})->middleware('auth:sanctum');
