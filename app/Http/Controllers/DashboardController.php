<?php

namespace App\Http\Controllers;

use App\Enums\Mood;
use App\Services\MoodAnalyzer;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private MoodAnalyzer $moodAnalyzer)
    {
    }

    public function index(): View
    {
        $user = Auth::user();

        $rawStats = $user->entries()
            ->selectRaw('mood, COUNT(*) as count')
            ->groupBy('mood')
            ->pluck('count', 'mood');

        $moodStats = collect(Mood::cases())
            ->mapWithKeys(fn($mood) => [
                $mood->value => $rawStats[$mood->value] ?? 0
            ]);

        $totalEntries = $moodStats->sum();

        $dominantMood = $totalEntries > 0
            ? $moodStats->sortDesc()->keys()->first()
            : null;

        $moodPercentages = $totalEntries > 0
            ? $moodStats->map(
                fn($count) =>
                round(($count / $totalEntries) * 100)
            )
            : collect();

        $summary = $this->moodAnalyzer->summary($dominantMood, $moodPercentages);

        return view('dashboard', [
            'totalEntries' => $totalEntries,
            'moodStats' => $moodStats,
            'moodPercentages' => $moodPercentages,
            'dominantMood' => $dominantMood,
            'summary' => $summary,
        ]);
    }
}