<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmissionRecord;

class EmissionController extends Controller
{
    public function index()
    {
        $userId = \App\Models\User::first()?->id ?? 1;

        // Ambil data berdasarkan ID tersebut agar tabel muncul
        $records = EmissionRecord::where('user_id', $userId)
            ->orderBy('recorded_at', 'desc')
            ->get();

        // Data untuk grafik Chart.js
        $chartData = EmissionRecord::where('user_id', $userId)
            ->orderBy('recorded_at', 'asc')
            ->take(7)
            ->get();

        // 
        return view('progress', compact('records', 'chartData'));
    }
}