<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmissionController;

// 1. Ubah halaman utama agar langsung menampilkan fitur Progress Tracking kamu
Route::get('/', [EmissionController::class, 'index']);

// 2. Tetap sediakan rute /progress sebagai cadangan
Route::get('/progress', [EmissionController::class, 'index'])->name('progress');