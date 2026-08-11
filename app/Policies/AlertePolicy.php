<?php

namespace App\Policies;

use App\Models\Alerte;
use App\Models\User;

class AlertePolicy
{
    public function modify(User $user, Alerte $alerte): bool
    {
        return $user->id === $alerte->user_id || $user->isAdmin();
    }
}
