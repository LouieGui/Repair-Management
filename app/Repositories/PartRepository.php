<?php

namespace App\Repositories;

use App\Models\Part;

class PartRepository
{
    /**
     * Create a new part record
     *
     * @param array $data Part data
     * @return Part Created part
     */
    public function create(array $data)
    {
        return Part::create($data);
    }

    /**
     * Find part by ID
     *
     * @param int $id Part ID
     * @return Part Part model
     */
    public function findById($id)
    {
        return Part::findOrFail($id);
    }

    /**
     * Update part record
     *
     * @param Part $part Part model
     * @param array $data Updated data
     * @return Part Updated part
     */
    public function update(Part $part, array $data)
    {
        $part->update($data);
        return $part;
    }

    /**
     * Get all parts with relationships
     *
     * @return \Illuminate\Database\Eloquent\Collection Collection of parts
     */
    public function getAll()
    {
        return Part::with(['repairParts'])
            ->where('is_active', true)
            ->orderBy('name', 'asc')
            ->get();
    }

    /**
     * Find parts by name (search)
     *
     * @param string $searchTerm Search term
     * @return \Illuminate\Database\Eloquent\Collection Collection of parts
     */
    public function searchByName($searchTerm)
    {
        return Part::where('name', 'like', '%' . $searchTerm . '%')
            ->where('is_active', true)
            ->orderBy('name', 'asc')
            ->get();
    }

    /**
     * Find parts by SKU
     *
     * @param string $sku SKU to search for
     * @return \Illuminate\Database\Eloquent\Collection Collection of parts
     */
    public function findBySku($sku)
    {
        return Part::where('sku', $sku)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Find parts with low stock
     *
     * @param int $threshold Stock threshold
     * @return \Illuminate\Database\Eloquent\Collection Collection of parts
     */
    public function findLowStockParts($threshold = 5)
    {
        return Part::where('stock_quantity', '<=', $threshold)
            ->where('is_active', true)
            ->orderBy('stock_quantity', 'asc')
            ->get();
    }

    /**
     * Update stock quantity
     *
     * @param Part $part Part model
     * @param int $quantity Quantity to adjust
     * @param string $operation 'add' or 'subtract'
     * @return Part Updated part
     */
    public function updateStock(Part $part, $quantity, $operation = 'add')
    {
        if ($operation === 'add') {
            $part->stock_quantity += $quantity;
        } else {
            $part->stock_quantity -= $quantity;
        }

        $part->save();
        return $part;
    }

    /**
     * Toggle active status (soft delete/restore)
     *
     * @param Part $part Part model
     * @return Part Updated part
     */
    public function toggleActiveStatus(Part $part)
    {
        $part->is_active = !$part->is_active;
        $part->save();
        return $part;
    }

    /**
     * Permanently delete part
     *
     * @param Part $part Part model
     * @return bool Deletion result
     */
    public function delete(Part $part)
    {
        return $part->delete();
    }
}
