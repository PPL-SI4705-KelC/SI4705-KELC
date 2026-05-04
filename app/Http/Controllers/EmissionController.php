<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmissionRecord;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class EmissionController extends Controller
{
    public function index(Request $request)
    {
        // Pastikan menggunakan user yang sedang login (jika ada Auth, atau fallback untuk demo)
        $userId = Auth::id() ?? (\App\Models\User::first()?->id ?? 1);

        // Filter Query Builder
        $query = EmissionRecord::where('user_id', $userId);

        if ($request->filled('filter_date')) {
            $query->whereDate('recorded_at', $request->filter_date);
        }

        if ($request->filled('filter_activity') && $request->filter_activity !== '') {
            $query->where('activity_type', $request->filter_activity);
        }

        // 1. Query Riwayat: Pagination 10 data & urutkan dari terbaru
        $carbon_footprints = $query->orderBy('recorded_at', 'desc')->paginate(10)->withQueryString();

        // Ambil tanggal target untuk referensi Chart dan SDG Score (default hari ini)
        $targetDateStr = $request->filled('filter_date') ? $request->filter_date : Carbon::today()->toDateString();
        $targetDateObj = Carbon::parse($targetDateStr);

        // Ambil range grafik dari request (opsi: 1 hari atau 7 hari), default: 7
        $chartRange = $request->input('chart_range', 7);
        $daysToSub = max(0, $chartRange - 1); // Jika range 1, maka mundur 0 hari. Jika 7, mundur 6 hari.

        // 2. Data Visualisasi (Chart.js): N hari ke belakang dari tanggal target
        $rangeStart = (clone $targetDateObj)->subDays($daysToSub)->toDateString();

        $chartQuery = EmissionRecord::where('user_id', $userId)
            ->whereBetween('recorded_at', [$rangeStart, $targetDateStr]);
            
        if ($request->filled('filter_activity') && $request->filter_activity !== '') {
            $chartQuery->where('activity_type', $request->filter_activity);
        }

        $chartDataRaw = $chartQuery->selectRaw('DATE(recorded_at) as date, SUM(carbon_impact) as total_emission')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $chartLabels = [];
        $chartData = [];

        // Looping N hari terakhir (berakhir di tanggal target) agar data tidak "bolong" di chart
        for ($i = $daysToSub; $i >= 0; $i--) {
            $dateStr = (clone $targetDateObj)->subDays($i)->toDateString();
            $chartLabels[] = $dateStr;
            
            $dataForDate = $chartDataRaw->firstWhere('date', $dateStr);
            $chartData[] = $dataForDate ? (float) $dataForDate->total_emission : 0;
        }

        // 3. SDG Impact Score (Skala 0-100)
        
        if ($chartRange == 1) {
            // Evaluasi 1 Hari
            $e_total = EmissionRecord::where('user_id', $userId)
                ->whereDate('recorded_at', $targetDateStr)
                ->sum('carbon_impact');
            $evalText = "Evaluasi Harian: " . $targetDateObj->format('d M Y');
        } else {
            // Evaluasi Tren 7 Hari (Gunakan Rata-Rata Harian)
            $total7Days = EmissionRecord::where('user_id', $userId)
                ->whereBetween('recorded_at', [$rangeStart, $targetDateStr])
                ->sum('carbon_impact');
            
            $e_total = $total7Days / 7; // E_total dalam rumus proposal adalah basis harian
            $startDateObj = (clone $targetDateObj)->subDays(6);
            $evalText = "Evaluasi 7 Hari: " . $startDateObj->format('d M') . " - " . $targetDateObj->format('d M Y');
        }

        // Rumus Proposal: (1 - ((E_total - 5) / (5 - (-5)))) * 100
        // Penyebut: (5 - (-5)) = 10
        $raw_sdg_score = (1 - (($e_total - 5) / 10)) * 100;

        // Batasi (clamp) skor agar tidak tembus batas 0 - 100
        $sdg_score = max(0, min(100, round($raw_sdg_score)));

        // Kategorisasi SDG Score (Humanized)
        if ($sdg_score >= 70) {
            $sdg_category = 'Bagus Sekali! 🌿';
            $sdg_message  = 'Emisi Anda sangat terkendali dan ramah lingkungan. Terus pertahankan gaya hidup hijau ini!';
            $sdg_color    = 'success'; 
        } elseif ($sdg_score >= 40) {
            $sdg_category = 'Cukup Baik 🌤️';
            $sdg_message  = 'Jejak karbon Anda tergolong normal, namun masih ada ruang untuk menguranginya. Yuk, perhatikan lagi penggunaan energi harianmu!';
            $sdg_color    = 'warning'; 
        } else {
            $sdg_category = 'Perlu Perhatian ⚠️';
            $sdg_message  = 'Wah, emisi karbon Anda sedang cukup tinggi. Mari kurangi penggunaan energi berlebih demi bumi yang lebih sehat!';
            $sdg_color    = 'danger'; 
        }

        return view('progress', compact('carbon_footprints', 'chartLabels', 'chartData', 'sdg_score', 'sdg_category', 'sdg_message', 'sdg_color', 'evalText'));
    }

    public function store(Request $request)
    {
        // 4. Optimalisasi Store
        $validatedData = $request->validate([
            'activity_type' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $userId = Auth::id() ?? (\App\Models\User::first()?->id ?? 1);

        // Contoh sederhana konversi ke carbon_impact (kg CO2)
        $factor = ($validatedData['activity_type'] === 'Transportasi') ? 2.3 : 0.8;
        $carbonImpact = $validatedData['amount'] * $factor;

        EmissionRecord::create([
            'user_id' => $userId,
            'activity_type' => $validatedData['activity_type'],
            'amount_value' => $validatedData['amount'],
            'carbon_impact' => $carbonImpact,
            'recorded_at' => Carbon::today()->toDateString(),
        ]);

        return redirect()->back()->with('success', 'Data jejak karbon berhasil disimpan!');
    }
}