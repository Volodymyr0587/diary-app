<?php

namespace App\Models;

use App\Enums\Mood;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Entry extends Model
{

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'mood',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function casts(): array
    {
        return [
            'mood' => Mood::class,
        ];
    }
}
