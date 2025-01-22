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

     /**
     * Determine if the authenticated user shares a company with the given user.
     *
     * @param  \App\Models\User  $authenticatedUser
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function belongsToSameCompany(User $authenticatedUser, User $user): bool
    {
        $authenticatedUserCompanyIds = $authenticatedUser->companies()->pluck('companies.id')->toArray();
        $userCompanyIds = $user->companies()->pluck('companies.id')->toArray();
        Log::info("authenticatedUserCompanyIds");
        Log::info($authenticatedUser); 
        log::info("userCompanyIds");
        Log::info($user); 
        return !empty(array_intersect($authenticatedUserCompanyIds, $userCompanyIds));
    }
}
