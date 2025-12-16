<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id','order_id','gross_amount','status','payment_type','snap_token','payload'
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    public function user() { return $this->belongsTo(User::class); }
}
