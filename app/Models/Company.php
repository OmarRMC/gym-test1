<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Company extends Model
{
    use HasFactory;
    protected $table = 'companies';

    /**
     * The users that belong to the company.
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    /**
     * Create a new company and associate it with the given user.
     * @param User $user
     */
    public static function createAndAttachCompany(User $user): void
    {
        $company = self::create();
        $company->name = $user->email;
        $company->save();
        $user->companies()->attach($company->id);
    }
}
