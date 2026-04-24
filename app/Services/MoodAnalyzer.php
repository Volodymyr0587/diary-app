<?php

namespace App\Services;

class MoodAnalyzer
{
    public function summary(?string $mood, $percentages): string
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
