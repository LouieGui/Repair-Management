<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Repair extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'customer_id',
        'device_id',
        'technician_id',
        'reported_issue',
        'accessories',
        'status',
        'diagnosis',
        'technician_notes',
        'date_received',
        'estimated_completion_date',
        'date_completed',
        'warranty_days',
        'warranty_until_date',
        'total_amount',
        'paid_amount',
        'is_active'
    ];

    protected $casts = [
        'date_received' => 'date',
        'estimated_completion_date' => 'date',
        'date_completed' => 'date',
        'warranty_until_date' => 'date',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'warranty_days' => 'integer',
        'is_active' => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function repairParts()
    {
        return $this->hasMany(RepairPart::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function returns()
    {
        return $this->hasMany(ReturnModel::class);
    }
}
