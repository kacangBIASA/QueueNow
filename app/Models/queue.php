<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    protected $fillable = [
        'branch_id',
        'nomor_antrean',
        'waktu_diambil',
        'waktu_dipanggil',
        'status',
    ];

    protected $casts = [
        'waktu_diambil' => 'datetime',
        'waktu_dipanggil' => 'datetime',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
