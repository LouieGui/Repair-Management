<?php

namespace App\Services;

use App\Repositories\RepairPartRepository;
use App\Repositories\PartRepository;
use App\Repositories\RepairRepository;
use App\Models\RepairPart;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RepairPartService
{
    public function __construct(
        protected RepairPartRepository $repairPartRepository,
        protected PartRepository $partRepository,
        protected RepairRepository $repairRepository
    ) {}

    /**
     * Create a new repair part record with business logic
     *
     * @param array $data Repair part data
     * @return RepairPart Created repair part
     */
    public function createRepairPart(array $data)
    {
        // Set default values and business logic
        $data['is_active'] = true;
        $data['is_approved'] = $data['is_approved'] ?? false;

        // Calculate total price if not provided
        if (!isset($data['total_price']) && isset($data['unit_price']) && isset($data['quantity'])) {
            $data['total_price'] = $data['unit_price'] * $data['quantity'];
        }

        // If using a part from inventory, validate stock
        if (isset($data['part_id'])) {
            $part = $this->partRepository->findById($data['part_id']);
            if ($part && $part->stock_quantity < ($data['quantity'] ?? 1)) {
                throw new \Exception('Insufficient stock for part: ' . $part->name);
            }
        }

        return $this->repairPartRepository->create($data);
    }

    /**
     * Update repair part record
     *
     * @param int $repairPartId Repair part ID
     * @param array $data Updated data
     * @return RepairPart Updated repair part
     */
    public function updateRepairPart($repairPartId, array $data)
    {
        $repairPart = $this->repairPartRepository->findById($repairPartId);

        // Recalculate total price if unit price or quantity changes
        if (isset($data['unit_price']) || isset($data['quantity'])) {
            $unitPrice = $data['unit_price'] ?? $repairPart->unit_price;
            $quantity = $data['quantity'] ?? $repairPart->quantity;
            $data['total_price'] = $unitPrice * $quantity;
        }

        return $this->repairPartRepository->update($repairPart, $data);
    }

    /**
     * Get all repair parts
     *
     * @return \Illuminate\Database\Eloquent\Collection Collection of repair parts
     */
    public function getAllRepairParts()
    {
        return $this->repairPartRepository->getAll();
    }

    /**
     * Get repair part by ID
     *
     * @param int $repairPartId Repair part ID
     * @return RepairPart Repair part model
     */
    public function getRepairPartById($repairPartId)
    {
        return $this->repairPartRepository->findById($repairPartId);
    }

    /**
     * Get repair parts by repair ID
     *
     * @param int $repairId Repair ID
     * @return \Illuminate\Database\Eloquent\Collection Collection of repair parts
     */
    public function getRepairPartsByRepair($repairId)
    {
        return $this->repairPartRepository->findByRepair($repairId);
    }

    /**
     * Get repair parts by part ID
     *
     * @param int $partId Part ID
     * @return \Illuminate\Database\Eloquent\Collection Collection of repair parts
     */
    public function getRepairPartsByPart($partId)
    {
        return $this->repairPartRepository->findByPart($partId);
    }

    /**
     * Get approved repair parts
     *
     * @return \Illuminate\Database\Eloquent\Collection Collection of approved repair parts
     */
    public function getApprovedRepairParts()
    {
        return $this->repairPartRepository->findApproved();
    }

    /**
     * Get pending repair parts
     *
     * @return \Illuminate\Database\Eloquent\Collection Collection of pending repair parts
     */
    public function getPendingRepairParts()
    {
        return $this->repairPartRepository->findPending();
    }

    /**
     * Approve repair parts
     *
     * @param array $repairPartIds Array of repair part IDs
     * @return int Number of updated records
     */
    public function approveRepairParts(array $repairPartIds)
    {
        return $this->repairPartRepository->bulkApprove($repairPartIds, true);
    }

    /**
     * Reject repair parts
     *
     * @param array $repairPartIds Array of repair part IDs
     * @return int Number of updated records
     */
    public function rejectRepairParts(array $repairPartIds)
    {
        return $this->repairPartRepository->bulkApprove($repairPartIds, false);
    }

    /**
     * Calculate total price for repair parts
     *
     * @param array $repairPartIds Array of repair part IDs
     * @return float Total price
     */
    public function calculateTotalPrice(array $repairPartIds)
    {
        return $this->repairPartRepository->calculateTotalPrice($repairPartIds);
    }

    /**
     * Update repair total amount based on approved parts
     *
     * @param int $repairId Repair ID
     * @return float Updated total amount
     */
    public function updateRepairTotalAmount($repairId)
    {
        $repairParts = $this->repairPartRepository->findByRepair($repairId);
        $approvedParts = $repairParts->where('is_approved', true);

        $totalAmount = $approvedParts->sum('total_price');

        $repair = $this->repairRepository->findById($repairId);
        $repair->total_amount = $totalAmount;
        $repair->save();

        return $totalAmount;
    }

    /**
     * Toggle active status (soft delete/restore)
     *
     * @param int $repairPartId Repair part ID
     * @return RepairPart Updated repair part
     */
    public function toggleRepairPartActiveStatus($repairPartId)
    {
        $repairPart = $this->repairPartRepository->findById($repairPartId);
        return $this->repairPartRepository->toggleActiveStatus($repairPart);
    }

    /**
     * Permanently delete repair part
     *
     * @param int $repairPartId Repair part ID
     * @return bool Deletion result
     */
    public function deleteRepairPart($repairPartId)
    {
        $repairPart = $this->repairPartRepository->findById($repairPartId);
        return $this->repairPartRepository->delete($repairPart);
    }

    /**
     * Create multiple repair parts for a repair (quotation)
     *
     * @param int $repairId Repair ID
     * @param array $partsData Array of part data
     * @return array Created repair parts
     */
    public function createQuotationParts($repairId, array $partsData)
    {
        $createdParts = [];

        DB::beginTransaction();

        try {
            foreach ($partsData as $partData) {
                $partData['repair_id'] = $repairId;
                $createdParts[] = $this->createRepairPart($partData);
            }

            // Update repair total amount
            $this->updateRepairTotalAmount($repairId);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Quotation parts creation failed: ' . $e->getMessage());
            throw $e;
        }

        return $createdParts;
    }

    /**
     * Approve quotation and update repair status
     *
     * @param int $repairId Repair ID
     * @param array $repairPartIds Array of repair part IDs to approve
     * @return array Approval results
     */
    public function approveQuotation($repairId, array $repairPartIds)
    {
        DB::beginTransaction();

        try {
            // Approve the parts
            $this->approveRepairParts($repairPartIds);

            // Update repair total amount
            $totalAmount = $this->updateRepairTotalAmount($repairId);

            // Update repair status to approved
            $repair = $this->repairRepository->findById($repairId);
            $repair->status = 'approved';
            $repair->save();

            DB::commit();

            return [
                'success' => true,
                'total_amount' => $totalAmount,
                'repair_status' => 'approved'
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Quotation approval failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
