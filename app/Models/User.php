<?php

namespace App\Models;

use App\Mail\EmailVerificationMail;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmailContract
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, MustVerifyEmail;
    const TOKEN_SCOPE_NAME = 'auth_token';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    /*
    * Send the email verification notification.
    * @return void
    */
    public function sendEmailValidation(): void
    {
        $token = Str::random(40);
        $this->setToken($token);
        Mail::to($this->email)->send(new EmailVerificationMail($this, $token));
    }
    /*
    * Set the email verification token for the user.
    * @param string $token
    * @return void
    */
    public function setToken($token): void
    {
        $this->email_verification_token = $token;
        $this->save();
    }

    /*
    * Get the authentication token for the user.
    * @return string
    */
    public function getAuthToken(): string
    {
        return $this->createToken(self::TOKEN_SCOPE_NAME)->plainTextToken;
    }

    /**
     * The companies that belong to the user.
     * @return BelongsToMany
     */
    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class)->withTimestamps();
    }

    /**
     * The roles that belong to the user.
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id')->withTimestamps();
    }
}
