<?php

namespace App\Policies;

use App\Models\Emission;
use App\Models\User;

class EmissionPolicy
{
    public function view(User $user, Emission $emission): bool
    {
        return $user->id === $emission->user_id || $user->isAdmin();
    }

    public function update(User $user, Emission $emission): bool
    {
        return $user->id === $emission->user_id;
    }

    public function delete(User $user, Emission $emission): bool
    {
        return $user->id === $emission->user_id || $user->isAdmin();
    }
}
