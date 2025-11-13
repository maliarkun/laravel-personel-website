<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasRoles; // Modernize membership system with role support.

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Keep legacy column for backward compatibility with existing data.
        'username',
        'avatar_path',
        'bio',
        'timezone',
        'email_verified_at',
        'last_login_at',
        'last_login_ip',
        'is_locked',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_login_at' => 'datetime',
        'is_locked' => 'boolean',
    ];

    public function subscriptions(): HasMany
    {
        return $this->hasMany(UserSubscription::class);
    }

    public function activeSubscription(): HasOne
    {
        return $this->hasOne(UserSubscription::class)
            ->whereIn('status', ['active', 'trial'])
            ->latestOfMany();
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(UserActivityLog::class)->latest();
    }
}
