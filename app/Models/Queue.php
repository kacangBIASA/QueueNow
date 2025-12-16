<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    protected $fillable = [
        'branch_id','queue_date','number','source','status',
        'taken_at','called_at','finished_at'
    ];

    protected $casts = [
        'queue_date' => 'date',
        'taken_at' => 'datetime',
        'called_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function branch() { return $this->belongsTo(Branch::class); }

}
