<?php

namespace App\Repositories;

use App\Models\ReturnModel;

class ReturnRepository
{
    /**
     * Create a new return record
     *
     * @param array $data Return data
     * @return ReturnModel Created return
     */
    public function create(array $data)
    {
        return ReturnModel::create($data);
    }

    /**
     * Find return by ID
     *
     * @param int $id Return ID
     * @return ReturnModel Return model
     */
    public function findById($id)
    {
        return ReturnModel::with(['repair', 'newRepair', 'receivedBy'])->findOrFail($id);
    }

    /**
     * Update return record
     *
     * @param ReturnModel $return Return model
     * @param array $data Updated data
     * @return ReturnModel Updated return
     */
    public function update(ReturnModel $return, array $data)
    {
        $return->update($data);
        return $return;
    }

    /**
     * Get all returns with relationships
     *
     * @return \Illuminate\Database\Eloquent\Collection Collection of returns
     */
    public function getAll()
    {
        return ReturnModel::with(['repair', 'newRepair', 'receivedBy'])
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Find returns by repair ID
     *
     * @param int $repairId Repair ID
     * @return \Illuminate\Database\Eloquent\Collection Collection of returns
     */
    public function findByRepair($repairId)
    {
        return ReturnModel::with(['repair', 'newRepair', 'receivedBy'])
            ->where('repair_id', $repairId)
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Find returns by return type
     *
     * @param string $returnType Return type
     * @return \Illuminate\Database\Eloquent\Collection Collection of returns
     */
    public function findByReturnType($returnType)
    {
        return ReturnModel::with(['repair', 'newRepair', 'receivedBy'])
            ->where('return_type', $returnType)
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Find returns by warranty status
     *
     * @param bool $isUnderWarranty Warranty status
     * @return \Illuminate\Database\Eloquent\Collection Collection of returns
     */
    public function findByWarrantyStatus($isUnderWarranty)
    {
        return ReturnModel::with(['repair', 'newRepair', 'receivedBy'])
            ->where('is_under_warranty', $isUnderWarranty)
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Toggle active status (soft delete/restore)
     *
     * @param ReturnModel $return Return model
     * @return ReturnModel Updated return
     */
    public function toggleActiveStatus(ReturnModel $return)
    {
        $return->is_active = !$return->is_active;
        $return->save();
        return $return;
    }

    /**
     * Permanently delete return
     *
     * @param ReturnModel $return Return model
     * @return bool Deletion result
     */
    public function delete(ReturnModel $return)
    {
        return $return->delete();
    }
}
