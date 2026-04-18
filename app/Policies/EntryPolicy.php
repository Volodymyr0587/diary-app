<?php

namespace App\Policies;

use App\Models\Entry;
use App\Models\User;

class EntryPolicy
{
    /**
     * Determine whether the user can work with Entry model.
     */
    public function workWith(?User $user, Entry $entry): bool
    {
        return $user?->id === $entry->user_id;
    }
}
