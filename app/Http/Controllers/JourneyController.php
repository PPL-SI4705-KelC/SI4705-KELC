<?php

namespace App\Http\Controllers;

use App\Services\GamificationService;
use Illuminate\Support\Facades\Auth;

class JourneyController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $journeyMap = GamificationService::getJourneyMap();
        $gamification = new GamificationService();
        $levelProgress = $gamification->levelProgress($user);
        $xpToNext = $gamification->xpToNextLevel($user);

        // Total CO2 saved
        $totalCo2Saved = $user->emissions()->sum('total_emission');

        // XP history (recent achievements)
        $xpHistory = $user->xpLogs()
            ->latest()
            ->limit(20)
            ->get();

        return view('journey.index', compact(
            'user',
            'journeyMap',
            'levelProgress',
            'xpToNext',
            'xpHistory',
            'totalCo2Saved'
        ));
    }
}
