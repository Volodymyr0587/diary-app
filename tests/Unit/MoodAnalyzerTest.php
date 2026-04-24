<?php

use App\Services\MoodAnalyzer;

it('returns positive summary for high happy percentage', function () {
    $service = new MoodAnalyzer();

    $result = $service->summary('happy', [
        'happy' => 70,
    ]);

    expect($result)->toContain('consistently feeling good');
});
