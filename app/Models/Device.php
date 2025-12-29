<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Device extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'customer_id',
        'brand',
        'model',
        'serial_number',
        'imei',
        'device_warranty_status',
        'device_password',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function repairs()
    {
        return $this->hasMany(Repair::class);
    }
}
