<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',

        // owner business
        'business_name',
        'phone',
        'business_category',

        // subscription
        'subscription_type',
        'subscription_expires_at',

        // google login
        'google_id',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'subscription_expires_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if user has active Pro subscription
     */
    public function isPro(): bool
    {
        if ($this->subscription_type !== 'pro') {
            return false;
        }

        if (!$this->subscription_expires_at) {
            return true;
        }

        return $this->subscription_expires_at->isFuture();
    }

    public function branches()
{
    return $this->hasMany(Branch::class);
}

}


