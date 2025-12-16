<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

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

        // google login
        'google_id',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isPro(): bool
    {
        return ($this->subscription_type ?? 'free') === 'pro';
    }

    public function branches()
    {
        return $this->hasMany(Branch::class);
    }
}
