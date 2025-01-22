<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Log;


class UserPolicy
{
    public function isAdmin(User $user): bool
    {
        return $user->roles()->where('code', Role::CODE_ADM_ROLE)->exists();
    }        
}
