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
            return 'Поки що замало записів для аналізу 📝';
        }

        $percent = $percentages[$mood] ?? 0;

        return match ($mood) {
            'happy' => $percent > 60
            ? 'Ти на хвилі позитиву 😄 Так тримати!'
            : 'В цілому все добре, але бувають різні дні 🙂',

            'sad' => $percent > 60
            ? 'Схоже, зараз непростий період 😔 Можливо варто трохи відпочити'
            : 'Іноді буває сумно, і це нормально 💙',

            'neutral' => 'Стабільний стан — без сильних коливань 😌',

            'angry' => $percent > 40
            ? 'Є накопичене напруження 😠 Спробуй дати собі розрядку'
            : 'Час від часу зʼявляється роздратування 😐',

            default => 'Цікавий мікс настроїв 🤔',
        };
    }
}