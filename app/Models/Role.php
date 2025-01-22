<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    protected $table = 'roles';
    public const CODE_ADM_ROLE = 'ADM';
    public const CODE_INS_ROLE = 'INS';
    public const CODE_MAN_ROLE = 'MAN';
    public const NAME_ADM_ROLE = 'ADMIN';
    public const NAME_INS_ROLE = 'INSTRUCTOR';
    public const NAME_MAN_ROLE = 'MANAGER';
    public const ROLES = [
        self::CODE_ADM_ROLE => self::NAME_ADM_ROLE,
        self::CODE_INS_ROLE => self::NAME_INS_ROLE,
        self::CODE_MAN_ROLE => self::NAME_MAN_ROLE,
    ];

    /**
     * The users that belong to the Role.
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    /**
     * Attach the role to the given user.
     * @param User $user The user to whom the role will be attached.
     * @param string $codeRole The code of the role to attach
     * @return void
     */
    public static function attachRole(User $user, string $codeRole): void
    {
        $adminRole = self::where('code', $codeRole)->first();
        if ($adminRole) {
            $user->roles()->attach($adminRole->code);
        }
    }
}
