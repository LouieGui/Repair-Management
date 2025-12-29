<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'repair_id',
        'amount',
        'payment_method',
        'status',
        'transaction_id',
        'notes',
        'is_active'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function repair()
    {
        return $this->belongsTo(Repair::class);
    }
}
