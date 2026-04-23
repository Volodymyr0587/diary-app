<?php

namespace App\Policies;

use App\Models\Entry;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EntryPolicy
{
    /**
     * Determine whether the user can work with Entry model.
     */
    public function workWith(?User $user, Entry $entry): Response
    {
        return $user?->id === $entry->user_id
            ? Response::allow()
            : Response::denyWithStatus(404);
    }

    public function forceDeleteAll(User $user): Response
    {
        return Response::allow();
    }
}
