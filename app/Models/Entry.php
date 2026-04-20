<?php

namespace App\Models;

use App\Enums\Mood;
use Illuminate\Database\Eloquent\Attributes\Scope;
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

    #[Scope]
    public function filterByDate($query, $date)
    {
        return $query->when(
            $date,
            fn($q) =>
            $q->whereDate('created_at', $date)
        );
    }

    #[Scope]
    public function search($query, ?string $search)
    {
        $search = trim($search);

        if (!$search) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
                ->orWhere('content', 'like', "%{$search}%");
        });
    }
}
