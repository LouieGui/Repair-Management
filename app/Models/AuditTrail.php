<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditTrail extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'user_type',
        'audit_table',
        'event',
        'old_values',
        'new_values',
        'audited_at'
    ];

    protected $casts = [
        'audited_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
