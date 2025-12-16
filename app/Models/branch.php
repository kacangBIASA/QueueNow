<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Branch extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::creating(function ($branch) {
            $branch->public_code = 'QN-' . strtoupper(\Str::random(6));
        });
    }

    protected $fillable = [
        'user_id',
        'nama_cabang',
        'alamat',
        'nomor_antrean_awal',
        'jadwal_operasional',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function queues()
    {
        return $this->hasMany(Queue::class);
    }
}
