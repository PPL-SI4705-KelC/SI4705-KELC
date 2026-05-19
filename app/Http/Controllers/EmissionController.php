<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmissionRequest;
use App\Models\Activity;
use App\Models\Emission;
use App\Services\EmissionService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmissionController extends Controller
{
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
                ->with('info', 'You have already submitted your carbon footprint for today.');
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

        // Performance status based on 7-day average
        if ($avgEmission == 0 && $recentEmissions->count() == 0) {
            $perfStatus = 'no_data';
            $perfLabel = 'Belum Ada Data';
            $perfMessage = 'Mulai catat jejak karbon Anda untuk melihat performa emisi Anda!';
        } elseif ($avgEmission <= 10) {
            $perfStatus = 'low';
            $perfLabel = 'Kinerja Emisi: SANGAT BAIK 🌟';
            $perfMessage = 'Luar biasa! Emisi karbon Anda tergolong rendah. Terus pertahankan kebiasaan ramah lingkungan ini!';
        } elseif ($avgEmission <= 25) {
            $perfStatus = 'medium';
            $perfLabel = 'Kinerja Emisi: CUKUP BAIK 👍';
            $perfMessage = 'Bagus! Emisi karbon Anda masih dalam batas wajar. Mari tingkatkan lagi pengurangan emisi Anda!';
        } else {
            $perfStatus = 'high';
            $perfLabel = 'Kinerja Emisi: PERLU PERHATIAN ⚠️';
            $perfMessage = 'Wah, emisi karbon Anda sedang cukup tinggi. Mari kurangi penggunaan energi berlebih demi bumi yang lebih sehat!';
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
