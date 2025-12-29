<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'email',
        'phone',
        'role',
        'password',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'role' => 'string',
        ];
    }

    /**
     * Scope a query to only include active users.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include inactive users.
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Scope a query to only include non-deleted users.
     */
    public function scopeNotDeleted($query)
    {
        return $query->whereNull('deleted_at');
    }

    /**
     * Soft delete the user.
     */
    public function softDelete()
    {
        $this->update(['deleted_at' => now()]);
    }

    /**
     * Restore a soft-deleted user.
     */
    public function restore()
    {
        $this->update(['deleted_at' => null]);
    }

    /**
     * Toggle the active status of the user.
     */
    public function toggleActive()
    {
        $this->update(['is_active' => !$this->is_active]);
    }

    /**
     * Get the repairs assigned to this technician.
     */
    public function technicianRepairs()
    {
        return $this->hasMany(Repair::class, 'technician_id');
    }

    /**
     * Get the returns received by this user.
     */
    public function receivedReturns()
    {
        return $this->hasMany(ReturnModel::class, 'received_by');
    }

    /**
     * Get the audit trails for this user.
     */
    public function auditTrails()
    {
        return $this->hasMany(AuditTrail::class, 'user_id');
    }
}
