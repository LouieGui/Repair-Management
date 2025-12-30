<?php

namespace App\Services;

use App\Repositories\RepairRepository;
use App\Models\Repair;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

/**
 * Repair Service
 *
 * Handles business logic for repair operations.
 * Acts as an intermediary between controllers and repositories.
 */
class RepairService
{
    /**
     * Create a new RepairService instance.
     *
     * @param  RepairRepository  $repairRepository  Repair repository instance
     */
    public function __construct(protected RepairRepository $repairRepository)
    {
        //
    }

    /**
     * Create a new repair with business logic.
     *
     * Automatically sets default values and validates relationships.
     *
     * @param  array  $data  Repair data
     * @return Repair  Created repair
     */
    public function createRepair(array $data): Repair
    {
        // Set default values if not provided
        $data['is_active'] = $data['is_active'] ?? true;
        $data['status'] = $data['status'] ?? 'received';
        $data['warranty_days'] = $data['warranty_days'] ?? 90;
        $data['total_amount'] = $data['total_amount'] ?? 0;
        $data['paid_amount'] = $data['paid_amount'] ?? 0;

        // Set date_received to current date if not provided
        $data['date_received'] = $data['date_received'] ?? now()->toDateString();

        return $this->repairRepository->create($data);
    }

    /**
     * Update an existing repair.
     *
     * @param  int  $repairId  Repair ID to update
     * @param  array  $data  Data to update
     * @return Repair  Updated repair
     */
    public function updateRepair(int $repairId, array $data): Repair
    {
        $repair = $this->repairRepository->findById($repairId);

        // If repair is completed, don't allow status changes to non-completed states
        if ($repair->status === 'completed' && isset($data['status']) && $data['status'] !== 'completed') {
            throw ValidationException::withMessages([
                'status' => 'Cannot change status of a completed repair.'
            ]);
        }

        // If repair is cancelled, don't allow any updates
        if ($repair->status === 'cancelled') {
            throw ValidationException::withMessages([
                'status' => 'Cannot update a cancelled repair.'
            ]);
        }

        return $this->repairRepository->update($repair, $data);
    }

    /**
     * Get a single repair by ID.
     *
     * @param  int  $repairId  Repair ID to retrieve
     * @return Repair  Found repair
     */
    public function getRepair(int $repairId): Repair
    {
        return $this->repairRepository->findById($repairId);
    }

    /**
     * List repairs with optional filters.
     *
     * @param  array  $filters  Filters to apply
     * @return Collection  Collection of repairs
     */
    public function listRepairs(array $filters = []): Collection
    {
        return $this->repairRepository->list($filters);
    }

    /**
     * Toggle repair active status (soft delete/restore).
     *
     * @param  int  $repairId  Repair ID to toggle
     * @return Repair  Updated repair
     */
    public function toggleRepairActiveStatus(int $repairId): Repair
    {
        $repair = $this->repairRepository->findById($repairId);
        return $this->repairRepository->toggleActiveStatus($repair);
    }

    /**
     * Find repairs by customer ID.
     *
     * @param  int  $customerId  Customer ID to search for
     * @return Collection  Collection of repairs for the customer
     */
    public function findRepairsByCustomer(int $customerId): Collection
    {
        return $this->repairRepository->findByCustomerId($customerId);
    }

    /**
     * Find repairs by device ID.
     *
     * @param  int  $deviceId  Device ID to search for
     * @return Collection  Collection of repairs for the device
     */
    public function findRepairsByDevice(int $deviceId): Collection
    {
        return $this->repairRepository->findByDeviceId($deviceId);
    }

    /**
     * Find repairs by technician ID.
     *
     * @param  int  $technicianId  Technician ID to search for
     * @return Collection  Collection of repairs assigned to the technician
     */
    public function findRepairsByTechnician(int $technicianId): Collection
    {
        return $this->repairRepository->findByTechnicianId($technicianId);
    }

    /**
     * Find repairs by status.
     *
     * @param  string  $status  Status to search for
     * @return Collection  Collection of repairs with the specified status
     */
    public function findRepairsByStatus(string $status): Collection
    {
        return $this->repairRepository->findByStatus($status);
    }

    /**
     * Update repair status with business logic.
     *
     * Validates status transitions and updates warranty dates.
     *
     * @param  int  $repairId  Repair ID to update
     * @param  string  $status  New status
     * @return Repair  Updated repair
     */
    public function updateRepairStatus(int $repairId, string $status): Repair
    {
        $repair = $this->repairRepository->findById($repairId);

        // Validate status transition
        $this->validateStatusTransition($repair->status, $status);

        // If status is completed, set date_completed and calculate warranty_until_date
        if ($status === 'completed') {
            $data = [
                'status' => $status,
                'date_completed' => now()->toDateString(),
                'warranty_until_date' => $this->repairRepository->calculateWarrantyUntilDate(
                    now()->toDateString(),
                    $repair->warranty_days
                )
            ];
            return $this->repairRepository->update($repair, $data);
        }

        // For other status updates
        return $this->repairRepository->updateStatus($repair, $status);
    }

    /**
     * Validate status transition.
     *
     * @param  string  $currentStatus  Current status
     * @param  string  $newStatus  New status
     * @return void
     * @throws ValidationException  If transition is invalid
     */
    protected function validateStatusTransition(string $currentStatus, string $newStatus): void
    {
        $validTransitions = [
            'received' => ['diagnosing', 'cancelled'],
            'diagnosing' => ['waiting_approval', 'cancelled'],
            'waiting_approval' => ['approved', 'cancelled'],
            'approved' => ['waiting_parts', 'in_progress', 'cancelled'],
            'waiting_parts' => ['in_progress', 'cancelled'],
            'in_progress' => ['testing', 'cancelled'],
            'testing' => ['ready_for_pickup', 'in_progress', 'cancelled'],
            'ready_for_pickup' => ['completed', 'cancelled'],
            'completed' => ['completed'],
            'cancelled' => ['cancelled']
        ];

        if (!in_array($newStatus, $validTransitions[$currentStatus] ?? [])) {
            throw ValidationException::withMessages([
                'status' => "Cannot transition from {$currentStatus} to {$newStatus}."
            ]);
        }
    }

    /**
     * Calculate total repair amount.
     *
     * @param  int  $repairId  Repair ID
     * @return float  Calculated total amount
     */
    public function calculateTotalAmount(int $repairId): float
    {
        $repair = $this->repairRepository->findById($repairId);

        // In a real implementation, this would calculate from repair parts
        // For now, we'll return the stored total_amount
        return $repair->total_amount;
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
        return $this->repairRepository->calculateWarrantyUntilDate($dateCompleted, $warrantyDays);
    }
}
