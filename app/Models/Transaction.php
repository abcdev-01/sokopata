<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'payment_id',
        'type',
        'status',
        'notes',
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}