<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmissionRecord;

class EmissionController extends Controller
{
    public function index()
    {
        // KARENA BELUM ADA LOGIN: 
        // Kita ambil ID pertama dari tabel User (User yang kamu buat di Tinker tadi)
        $userId = \App\Models\User::first()?->id ?? 1;

        // Ambil data berdasarkan ID tersebut agar tabel muncul
        $records = EmissionRecord::where('user_id', $userId)
            ->orderBy('recorded_at', 'desc')
            ->get();

        // Data untuk grafik Chart.js (Ambil 7 data terakhir)
        $chartData = EmissionRecord::where('user_id', $userId)
            ->orderBy('recorded_at', 'asc')
            ->take(7)
            ->get();

        // SESUAI STRUKTUR KAMU: Hapus 'dashboard.' karena file ada di folder views langsung
        return view('progress', compact('records', 'chartData'));
    }
}