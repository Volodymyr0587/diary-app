<?php

namespace App\Http\Controllers;

use App\Enums\Mood;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
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

        $summary = $this->getMoodSummary($dominantMood, $moodPercentages);

        return view('dashboard', [
            'totalEntries' => $totalEntries,
            'moodStats' => $moodStats,
            'moodPercentages' => $moodPercentages,
            'dominantMood' => $dominantMood,
            'summary' => $summary,
        ]);
    }

    private function getMoodSummary(?string $mood, $percentages): string
    {
        if (!$mood) {
            return 'There are not enough records to analyze yet 📝';
        }

        $percent = $percentages[$mood] ?? 0;

        return match ($mood) {
            'happy' => $percent > 60
            ? 'You\'re consistently feeling good 😄 That\'s a great sign!'
            : 'Overall things look positive, with some natural ups and downs 🙂',

            'sad' => $percent > 60
            ? 'This looks like a tough period 😔 Try to give yourself some care and rest'
            : 'There are some sad moments, which is completely normal 💙',

            'angry' => $percent > 40
            ? 'There might be built-up tension 😠 Maybe it\'s time to release it somehow'
            : 'Occasional frustration shows up, but it\'s under control 😐',

            'calm' => $percent > 60
            ? 'You\'re in a very balanced and peaceful state 😌'
            : 'There\'s a good level of calmness in your days 🌿',

            'excited' => $percent > 50
            ? 'Lots of energy and excitement ⚡ Things must be interesting!'
            : 'Moments of excitement are bringing some spark ✨',

            default => 'You have a diverse emotional landscape 🤔',
        };
    }
}