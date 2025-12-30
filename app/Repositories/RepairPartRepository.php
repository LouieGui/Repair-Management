<?php

namespace App\Repositories;

use App\Models\RepairPart;

class RepairPartRepository
{
    /**
     * Create a new repair part record
     *
     * @param array $data Repair part data
     * @return RepairPart Created repair part
     */
    public function create(array $data)
    {
        return RepairPart::create($data);
    }

    /**
     * Find repair part by ID
     *
     * @param int $id Repair part ID
     * @return RepairPart Repair part model
     */
    public function findById($id)
    {
        return RepairPart::with(['repair', 'part'])->findOrFail($id);
    }

    /**
     * Update repair part record
     *
     * @param RepairPart $repairPart Repair part model
     * @param array $data Updated data
     * @return RepairPart Updated repair part
     */
    public function update(RepairPart $repairPart, array $data)
    {
        $repairPart->update($data);
        return $repairPart;
    }

    /**
     * Get all repair parts with relationships
     *
     * @return \Illuminate\Database\Eloquent\Collection Collection of repair parts
     */
    public function getAll()
    {
        return RepairPart::with(['repair', 'part'])
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Find repair parts by repair ID
     *
     * @param int $repairId Repair ID
     * @return \Illuminate\Database\Eloquent\Collection Collection of repair parts
     */
    public function findByRepair($repairId)
    {
        return RepairPart::with(['repair', 'part'])
            ->where('repair_id', $repairId)
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Find repair parts by part ID
     *
     * @param int $partId Part ID
     * @return \Illuminate\Database\Eloquent\Collection Collection of repair parts
     */
    public function findByPart($partId)
    {
        return RepairPart::with(['repair', 'part'])
            ->where('part_id', $partId)
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Find approved repair parts
     *
     * @return \Illuminate\Database\Eloquent\Collection Collection of approved repair parts
     */
    public function findApproved()
    {
        return RepairPart::with(['repair', 'part'])
            ->where('is_approved', true)
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Find pending repair parts
     *
     * @return \Illuminate\Database\Eloquent\Collection Collection of pending repair parts
     */
    public function findPending()
    {
        return RepairPart::with(['repair', 'part'])
            ->where('is_approved', false)
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Approve or reject repair parts
     *
     * @param array $repairPartIds Array of repair part IDs
     * @param bool $isApproved Approval status
     * @return int Number of updated records
     */
    public function bulkApprove(array $repairPartIds, $isApproved = true)
    {
        return RepairPart::whereIn('id', $repairPartIds)
            ->update(['is_approved' => $isApproved]);
    }

    /**
     * Calculate total price for repair parts
     *
     * @param array $repairPartIds Array of repair part IDs
     * @return float Total price
     */
    public function calculateTotalPrice(array $repairPartIds)
    {
        return RepairPart::whereIn('id', $repairPartIds)
            ->where('is_approved', true)
            ->sum('total_price');
    }

    /**
     * Toggle active status (soft delete/restore)
     *
     * @param RepairPart $repairPart Repair part model
     * @return RepairPart Updated repair part
     */
    public function toggleActiveStatus(RepairPart $repairPart)
    {
        $repairPart->is_active = !$repairPart->is_active;
        $repairPart->save();
        return $repairPart;
    }

    /**
     * Permanently delete repair part
     *
     * @param RepairPart $repairPart Repair part model
     * @return bool Deletion result
     */
    public function delete(RepairPart $repairPart)
    {
        return $repairPart->delete();
    }
}
