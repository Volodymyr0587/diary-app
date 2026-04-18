<?php

namespace App\Enums;

enum Mood: string
{
    case HAPPY = 'happy';
    case SAD = 'sad';
    case ANGRY = 'angry';
    case CALM = 'calm';
    case EXCITED = 'excited';

    public function label(): string
    {
        return match ($this) {
            self::HAPPY => '😊 Happy',
            self::SAD => '😢 Sad',
            self::ANGRY => '😡 Angry',
            self::CALM => '😌 Calm',
            self::EXCITED => '🤩 Excited',
        };
    }
}
