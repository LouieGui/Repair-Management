<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReturnModel extends Model
{
    use SoftDeletes;

    protected $table = 'returns';

    protected $fillable = [
        'repair_id',
        'new_repair_id',
        'return_type',
        'return_date',
        'return_reason',
        'is_same_issue',
        'is_under_warranty',
        'received_by',
        'notes',
        'is_active'
    ];

    protected $casts = [
        'return_date' => 'date',
        'is_same_issue' => 'boolean',
        'is_under_warranty' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function repair()
    {
        return $this->belongsTo(Repair::class, 'repair_id');
    }

    public function newRepair()
    {
        return $this->belongsTo(Repair::class, 'new_repair_id');
    }

    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by');
    }
}
