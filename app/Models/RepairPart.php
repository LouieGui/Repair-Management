<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RepairPart extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'repair_id',
        'part_id',
        'custom_part_name',
        'quantity',
        'unit_price',
        'total_price',
        'is_approved',
        'notes',
        'is_active'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'quantity' => 'integer',
        'is_approved' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function repair()
    {
        return $this->belongsTo(Repair::class);
    }

    public function part()
    {
        return $this->belongsTo(Part::class);
    }
}
