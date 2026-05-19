<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmissionRecord;
use Carbon\Carbon;
use App\Http\Requests\StoreEmissionRequest;
use App\Models\Activity;
use App\Models\Emission;
use App\Services\EmissionService;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
    public function __construct(
        private EmissionService $calculator
    ) {}

    /**
     * Show the carbon footprint calculator form.
     */
    public function create()
    {
        $user = Auth::user();
        $today = Carbon::now('Asia/Jakarta')->toDateString();

        // Check if already submitted today
        $todayActivity = Activity::where('user_id', $user->id)
            ->whereDate('activity_date', $today)
            ->first();

        if ($todayActivity) {
            return redirect()->route('calculator.show', $todayActivity->emission)
                ->with('info', 'You have already calculated your climate impact today. You can calculate again tomorrow after 00:00.');
        }

        return view('emissions.create');
    }

    /**
     * Store daily emission calculation.
     */
    public function store(StoreEmissionRequest $request)
    {
        $user = Auth::user();
        $today = Carbon::now('Asia/Jakarta')->toDateString();

        // Enforce 1 submission per day
        $exists = Activity::where('user_id', $user->id)
            ->whereDate('activity_date', $today)
            ->exists();

        if ($exists) {
            return back()->with('error', 'You have already submitted today\'s carbon footprint.');
        }

        $transportData = $request->input('transport', []);
        $consumptionData = $request->input('consumption', []);
        $energyData = $request->input('energy', []);

        // Create activity
        $activity = Activity::create([
            'user_id' => $user->id,
            'activity_date' => $today,
            'transport_data' => $transportData,
            'consumption_data' => $consumptionData,
            'energy_data' => $energyData,
        ]);

        // Calculate emissions
        $results = $this->calculator->calculateAll($transportData, $consumptionData, $energyData);

        // Store emission
        $emission = Emission::create([
            'user_id' => $user->id,
            'activity_id' => $activity->id,
            'transport_emission' => $results['transport'],
            'consumption_emission' => $results['consumption'],
            'energy_emission' => $results['energy'],
            'total_emission' => $results['total'],
            'sdg_score' => $results['sdg_score'],
            'emission_date' => $today,
            'raw_input' => $request->all(),
        ]);

        return redirect()->route('calculator.show', $emission)
            ->with('success', 'Carbon footprint calculated successfully!');
    }

    /**
     * Show emission result.
     */
    public function show(Emission $emission)
    {
        abort_if($emission->user_id !== Auth::id(), 403, 'Unauthorized action.');

        $emission->load('activity');

        return view('emissions.show', compact('emission'));
    }

    /**
     * Show emission history (progress tracking).
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Chart date range filter (defaults to last 7 days)
        $chartStart = $request->filled('chart_start')
            ? Carbon::parse($request->chart_start)->startOfDay()
            : Carbon::now('Asia/Jakarta')->subDays(7)->startOfDay();

        $chartEnd = $request->filled('chart_end')
            ? Carbon::parse($request->chart_end)->endOfDay()
            : Carbon::now('Asia/Jakarta')->endOfDay();

        // Chart data: emissions for Carbon Trend within selected range
        $chartData = $user->emissions()
            ->whereBetween('emission_date', [$chartStart, $chartEnd])
            ->orderBy('emission_date')
            ->get(['emission_date', 'transport_emission', 'consumption_emission', 'energy_emission', 'total_emission', 'sdg_score']);

        // 7-day performance evaluation
        $sevenDayStart = Carbon::now('Asia/Jakarta')->subDays(7)->toDateString();
        $sevenDayEnd = Carbon::now('Asia/Jakarta')->toDateString();
        $recentEmissions = $user->emissions()
            ->whereBetween('emission_date', [$sevenDayStart, $sevenDayEnd])
            ->get();

        $avgEmission = $recentEmissions->count() > 0 ? round($recentEmissions->avg('total_emission'), 2) : 0;
        $avgSdg = $recentEmissions->count() > 0 ? round($recentEmissions->avg('sdg_score'), 1) : 0;

        // Performance status based on SDG Score
        if ($avgEmission == 0 && $recentEmissions->count() == 0) {
            $perfStatus = 'no_data';
            $perfLabel = 'Belum Ada Data';
            $perfMessage = 'Mulai catat jejak karbon Anda untuk melihat performa emisi Anda!';
        } elseif ($avgSdg >= 89) {
            $perfStatus = 'low'; // Green
            $perfLabel = 'Kinerja Emisi: SANGAT BAIK 🌟';
            $perfMessage = 'Luar biasa! Kontribusi SDG Anda sangat baik. Terus pertahankan kebiasaan ramah lingkungan ini!';
        } elseif ($avgSdg >= 40) {
            $perfStatus = 'medium'; // Yellow
            $perfLabel = 'Kinerja Emisi: CUKUP BAIK 👍';
            $perfMessage = 'Bagus! Kontribusi SDG Anda cukup baik. Mari tingkatkan lagi pengurangan emisi Anda!';
        } else {
            $perfStatus = 'high'; // Red
            $perfLabel = 'Kinerja Emisi: PERLU PERHATIAN ⚠️';
            $perfMessage = 'Wah, kontribusi SDG Anda tergolong rendah. Mari kurangi penggunaan energi berlebih demi bumi yang lebih sehat!';
        }

        // Emission History table — build rows from individual emission categories
        $emissionQuery = $user->emissions()->with('activity');

        // Date filter for emission history
        if ($request->filled('filter_date')) {
            $emissionQuery->whereDate('emission_date', $request->filter_date);
        }

        $allEmissions = $emissionQuery->orderBy('emission_date', 'desc')->get();

        // Flatten emissions into per-category rows
        $emissionRows = collect();
        foreach ($allEmissions as $e) {
            if ($e->transport_emission > 0) {
                $emissionRows->push([
                    'name' => 'Eco-friendly Commute',
                    'category' => 'Transportation',
                    'dot_color' => '#ef4444',
                    'category_color' => 'text-red-600',
                    'date' => $e->emission_date->format('d M Y'),
                    'sdg' => $e->sdg_score,
                    'carbon' => $e->transport_emission,
                ]);
            }
            if ($e->energy_emission > 0) {
                $emissionRows->push([
                    'name' => 'Energy Conservation',
                    'category' => 'Energy',
                    'dot_color' => '#10b981',
                    'category_color' => 'text-emerald-600',
                    'date' => $e->emission_date->format('d M Y'),
                    'sdg' => $e->sdg_score,
                    'carbon' => $e->energy_emission,
                ]);
            }
            if ($e->consumption_emission > 0) {
                $emissionRows->push([
                    'name' => 'Sustainable Diet',
                    'category' => 'Food',
                    'dot_color' => '#f97316',
                    'category_color' => 'text-orange-600',
                    'date' => $e->emission_date->format('d M Y'),
                    'sdg' => $e->sdg_score,
                    'carbon' => $e->consumption_emission,
                ]);
            }
        }

        // Manual pagination for flattened rows
        $page = $request->input('page', 1);
        $perPage = 10;
        $paginatedRows = new \Illuminate\Pagination\LengthAwarePaginator(
            $emissionRows->forPage($page, $perPage)->values(),
            $emissionRows->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('emissions.index', compact(
            'chartData', 'avgEmission', 'avgSdg', 'perfStatus', 'perfLabel', 'perfMessage',

            'sevenDayStart', 'sevenDayEnd', 'paginatedRows', 'chartStart', 'chartEnd'
        ));
    }
}

