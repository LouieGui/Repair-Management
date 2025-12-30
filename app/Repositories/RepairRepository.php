<?php

namespace App\Repositories;

use App\Models\Repair;
use Illuminate\Database\Eloquent\Collection;

/**
 * Repair Repository
 *
 * Handles database operations for Repair model.
 * Provides a clean interface for repair data access.
 */
class RepairRepository
{
    /**
     * Create a new repair record.
     *
     * @param  array  $data  Repair data to create
     * @return Repair  Created repair instance
     */
    public function create(array $data): Repair
    {
        return Repair::create($data);
    }

    /**
     * Find a repair by ID.
     *
     * @param  int  $id  Repair ID to find
     * @return Repair  Found repair instance
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException  If repair not found
     */
    public function findById(int $id): Repair
    {
        return Repair::findOrFail($id);
    }

    /**
     * Update a repair record.
     *
     * @param  Repair  $repair  Repair instance to update
     * @param  array  $data  Data to update
     * @return Repair  Updated repair instance
     */
    public function update(Repair $repair, array $data): Repair
    {
        $repair->update($data);
        return $repair;
    }

    /**
     * List repairs with optional filters.
     *
     * @param  array  $filters  Filters to apply (e.g., ['customer_id' => 1, 'status' => 'received', 'search' => 'iPhone'])
     * @return Collection  Collection of Repair instances
     */
    public function list(array $filters = []): Collection
    {
        $query = Repair::query();

        // Apply filters
        if (isset($filters['customer_id'])) {
            $query->where('customer_id', $filters['customer_id']);
        }

        if (isset($filters['device_id'])) {
            $query->where('device_id', $filters['device_id']);
        }

        if (isset($filters['technician_id'])) {
            $query->where('technician_id', $filters['technician_id']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        if (isset($filters['search'])) {
            $query->where(function($q) use ($filters) {
                $q->where('reported_issue', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('diagnosis', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('technician_notes', 'like', '%' . $filters['search'] . '%');
            });
        }

        return $query->with(['customer', 'device', 'technician'])->orderBy('created_at', 'desc')->get();
    }

    /**
     * Toggle repair active status (soft delete/restore).
     *
     * @param  Repair  $repair  Repair instance to toggle
     * @return Repair  Updated repair instance
     */
    public function toggleActiveStatus(Repair $repair): Repair
    {
        $repair->is_active = !$repair->is_active;
        $repair->save();
        return $repair;
    }

    /**
     * Find repairs by customer ID.
     *
     * @param  int  $customerId  Customer ID to search for
     * @return Collection  Collection of repairs for the customer
     */
    public function findByCustomerId(int $customerId): Collection
    {
        return Repair::where('customer_id', $customerId)
                   ->with(['device', 'technician'])
                   ->orderBy('created_at', 'desc')
                   ->get();
    }

    /**
     * Find repairs by device ID.
     *
     * @param  int  $deviceId  Device ID to search for
     * @return Collection  Collection of repairs for the device
     */
    public function findByDeviceId(int $deviceId): Collection
    {
        return Repair::where('device_id', $deviceId)
                   ->with(['customer', 'technician'])
                   ->orderBy('created_at', 'desc')
                   ->get();
    }

    /**
     * Find repairs by technician ID.
     *
     * @param  int  $technicianId  Technician ID to search for
     * @return Collection  Collection of repairs assigned to the technician
     */
    public function findByTechnicianId(int $technicianId): Collection
    {
        return Repair::where('technician_id', $technicianId)
                   ->with(['customer', 'device'])
                   ->orderBy('created_at', 'desc')
                   ->get();
    }

    /**
     * Find repairs by status.
     *
     * @param  string  $status  Status to search for
     * @return Collection  Collection of repairs with the specified status
     */
    public function findByStatus(string $status): Collection
    {
        return Repair::where('status', $status)
                   ->with(['customer', 'device', 'technician'])
                   ->orderBy('created_at', 'desc')
                   ->get();
    }

    /**
     * Update repair status.
     *
     * @param  Repair  $repair  Repair instance to update
     * @param  string  $status  New status
     * @return Repair  Updated repair instance
     */
    public function updateStatus(Repair $repair, string $status): Repair
    {
        $repair->status = $status;
        $repair->save();
        return $repair;
    }

    /**
     * Calculate warranty until date.
     *
     * @param  string  $dateCompleted  Date when repair was completed
     * @param  int  $warrantyDays  Warranty period in days
     * @return string  Calculated warranty until date
     */
    public function calculateWarrantyUntilDate(string $dateCompleted, int $warrantyDays): string
    {
        return date('Y-m-d', strtotime($dateCompleted . ' + ' . $warrantyDays . ' days'));
    }
}
