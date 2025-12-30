<?php

namespace App\Services;

use App\Repositories\ReturnRepository;
use App\Models\ReturnModel;
use App\Models\Repair;
use App\Enums\RepairStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReturnService
{
    public function __construct(
        protected ReturnRepository $returnRepository
    ) {}

    /**
     * Create a new return record with business logic
     *
     * @param array $data Return data
     * @return ReturnModel Created return
     */
    public function createReturn(array $data)
    {
        // Set default values and business logic
        $data['is_active'] = true;

        // Check if this is a warranty claim
        if ($data['return_type'] === 'warranty_claim') {
            $originalRepair = Repair::find($data['repair_id']);
            if ($originalRepair) {
                $data['is_under_warranty'] = $this->checkWarrantyStatus($originalRepair);
            }
        }

        return $this->returnRepository->create($data);
    }

    /**
     * Update return record
     *
     * @param int $returnId Return ID
     * @param array $data Updated data
     * @return ReturnModel Updated return
     */
    public function updateReturn($returnId, array $data)
    {
        $return = $this->returnRepository->findById($returnId);
        return $this->returnRepository->update($return, $data);
    }

    /**
     * Get all returns
     *
     * @return \Illuminate\Database\Eloquent\Collection Collection of returns
     */
    public function getAllReturns()
    {
        return $this->returnRepository->getAll();
    }

    /**
     * Get return by ID
     *
     * @param int $returnId Return ID
     * @return ReturnModel Return model
     */
    public function getReturnById($returnId)
    {
        return $this->returnRepository->findById($returnId);
    }

    /**
     * Get returns by repair ID
     *
     * @param int $repairId Repair ID
     * @return \Illuminate\Database\Eloquent\Collection Collection of returns
     */
    public function getReturnsByRepair($repairId)
    {
        return $this->returnRepository->findByRepair($repairId);
    }

    /**
     * Get returns by return type
     *
     * @param string $returnType Return type
     * @return \Illuminate\Database\Eloquent\Collection Collection of returns
     */
    public function getReturnsByType($returnType)
    {
        return $this->returnRepository->findByReturnType($returnType);
    }

    /**
     * Get returns by warranty status
     *
     * @param bool $isUnderWarranty Warranty status
     * @return \Illuminate\Database\Eloquent\Collection Collection of returns
     */
    public function getReturnsByWarrantyStatus($isUnderWarranty)
    {
        return $this->returnRepository->findByWarrantyStatus($isUnderWarranty);
    }

    /**
     * Toggle active status (soft delete/restore)
     *
     * @param int $returnId Return ID
     * @return ReturnModel Updated return
     */
    public function toggleReturnActiveStatus($returnId)
    {
        $return = $this->returnRepository->findById($returnId);
        return $this->returnRepository->toggleActiveStatus($return);
    }

    /**
     * Permanently delete return
     *
     * @param int $returnId Return ID
     * @return bool Deletion result
     */
    public function deleteReturn($returnId)
    {
        $return = $this->returnRepository->findById($returnId);
        return $this->returnRepository->delete($return);
    }

    /**
     * Check if a repair is still under warranty
     *
     * @param Repair $repair Repair model
     * @return bool Warranty status
     */
    protected function checkWarrantyStatus(Repair $repair)
    {
        if (!$repair->date_completed || !$repair->warranty_days) {
            return false;
        }

        $warrantyEndDate = date('Y-m-d', strtotime($repair->date_completed . ' + ' . $repair->warranty_days . ' days'));
        $currentDate = date('Y-m-d');

        return $currentDate <= $warrantyEndDate;
    }

    /**
     * Process a warranty claim return
     *
     * @param array $data Return data
     * @return array Result with return and new repair
     */
    public function processWarrantyClaim(array $data)
    {
        DB::beginTransaction();

        try {
            // Create the return record
            $data['return_type'] = 'warranty_claim';
            $return = $this->createReturn($data);

            // Create a new repair record for the warranty claim
            $originalRepair = Repair::find($data['repair_id']);
            $newRepairData = [
                'customer_id' => $originalRepair->customer_id,
                'device_id' => $originalRepair->device_id,
                'technician_id' => $originalRepair->technician_id,
                'reported_issue' => $data['return_reason'],
                'status' => RepairStatus::RECEIVED,
                'date_received' => date('Y-m-d'),
                'total_amount' => 0, // Warranty claims are free
                'paid_amount' => 0,
                'is_active' => true
            ];

            $newRepair = Repair::create($newRepairData);

            // Update the return with the new repair ID
            $return->new_repair_id = $newRepair->id;
            $return->save();

            DB::commit();

            return [
                'return' => $return,
                'new_repair' => $newRepair
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Warranty claim processing failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
