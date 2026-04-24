<?php

namespace App\Models;

use App\Enums\Mood;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Entry extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
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

    #[Scope]
    public function filterByDeletedAt($query, $date)
    {
        return $query->when(
            $date,
            fn($q) =>
            $q->whereDate('deleted_at', $date)
        );
    }

    #[Scope]
    public function searchDeleted($query, ?string $search)
    {
        $search = trim($search);

        if (!$search) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('deleted_at', true)
                ->where('title', 'like', "%{$search}%")
                ->orWhere('content', 'like', "%{$search}%");
        });
    }

    protected static function booted(): void
    {
        static::creating(function (Entry $entry) {
            $entry->slug = $entry->generateUniqueSlug();
        });

        static::updating(function (Entry $entry) {
            if ($entry->isDirty('title') || $entry->isDirty('content')) {
                $entry->slug = $entry->generateUniqueSlug();
            }
        });
    }

    protected function generateUniqueSlug(): string
    {
        // if title - make slug from it, else take first words from content
        $base = $this->title
            ? $this->title
            : Str::words(strip_tags($this->content), 8, '');

        $slug = Str::slug($base);

        if (empty($slug)) {
            $slug = 'entry-' . uniqid();
        }

        return $this->makeSlugUnique($slug);
    }

    protected function makeSlugUnique(string $slug): string
    {
        $original = $slug;
        $count = 1;

        while (
            static::where('slug', $slug)
                ->when($this->exists, fn($q) => $q->where('id', '!=', $this->id))
                ->exists()
        ) {
            $slug = "{$original}-{$count}";
            $count++;
        }

        return $slug;
    }
}
