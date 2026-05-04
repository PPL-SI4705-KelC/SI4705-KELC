<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmissionController;

// Rute Utama: Redirect ke dashboard progress emisi
Route::get('/', function () {
    return redirect()->route('emissions.index');
});

// RESTful API / Resource Routing untuk Emission (Progress Tracking & Data Mocking)
Route::resource('emissions', EmissionController::class)->only(['index', 'store']);