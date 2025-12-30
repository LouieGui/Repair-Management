<?php

namespace App\Services;

use App\Repositories\PartRepository;
use App\Models\Part;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PartService
{
    public function __construct(
        protected PartRepository $partRepository
    ) {}

    /**
     * Create a new part record with business logic
     *
     * @param array $data Part data
     * @return Part Created part
     */
    public function createPart(array $data)
    {
        // Set default values and business logic
        $data['is_active'] = true;
        $data['stock_quantity'] = $data['stock_quantity'] ?? 0;

        // Ensure selling price is not less than purchase price
        if (isset($data['purchase_price']) && isset($data['selling_price']) &&
            $data['selling_price'] < $data['purchase_price']) {
            $data['selling_price'] = $data['purchase_price'] * 1.2; // 20% markup by default
        }

        return $this->partRepository->create($data);
    }

    /**
     * Update part record
     *
     * @param int $partId Part ID
     * @param array $data Updated data
     * @return Part Updated part
     */
    public function updatePart($partId, array $data)
    {
        $part = $this->partRepository->findById($partId);

        // Prevent selling price below purchase price
        if (isset($data['purchase_price']) && isset($data['selling_price']) &&
            $data['selling_price'] < $data['purchase_price']) {
            $data['selling_price'] = $data['purchase_price'] * 1.2;
        }

        return $this->partRepository->update($part, $data);
    }

    /**
     * Get all parts
     *
     * @return \Illuminate\Database\Eloquent\Collection Collection of parts
     */
    public function getAllParts()
    {
        return $this->partRepository->getAll();
    }

    /**
     * Get part by ID
     *
     * @param int $partId Part ID
     * @return Part Part model
     */
    public function getPartById($partId)
    {
        return $this->partRepository->findById($partId);
    }

    /**
     * Search parts by name
     *
     * @param string $searchTerm Search term
     * @return \Illuminate\Database\Eloquent\Collection Collection of parts
     */
    public function searchPartsByName($searchTerm)
    {
        return $this->partRepository->searchByName($searchTerm);
    }

    /**
     * Find part by SKU
     *
     * @param string $sku SKU to search for
     * @return Part|null Part model or null
     */
    public function findPartBySku($sku)
    {
        return $this->partRepository->findBySku($sku);
    }

    /**
     * Get parts with low stock
     *
     * @param int $threshold Stock threshold
     * @return \Illuminate\Database\Eloquent\Collection Collection of parts
     */
    public function getLowStockParts($threshold = 5)
    {
        return $this->partRepository->findLowStockParts($threshold);
    }

    /**
     * Add stock to a part
     *
     * @param int $partId Part ID
     * @param int $quantity Quantity to add
     * @return Part Updated part
     */
    public function addStock($partId, $quantity)
    {
        $part = $this->partRepository->findById($partId);
        return $this->partRepository->updateStock($part, $quantity, 'add');
    }

    /**
     * Subtract stock from a part
     *
     * @param int $partId Part ID
     * @param int $quantity Quantity to subtract
     * @return Part Updated part
     * @throws \Exception If stock would go negative
     */
    public function subtractStock($partId, $quantity)
    {
        $part = $this->partRepository->findById($partId);

        if ($part->stock_quantity < $quantity) {
            throw new \Exception('Insufficient stock for part: ' . $part->name);
        }

        return $this->partRepository->updateStock($part, $quantity, 'subtract');
    }

    /**
     * Toggle active status (soft delete/restore)
     *
     * @param int $partId Part ID
     * @return Part Updated part
     */
    public function togglePartActiveStatus($partId)
    {
        $part = $this->partRepository->findById($partId);
        return $this->partRepository->toggleActiveStatus($part);
    }

    /**
     * Permanently delete part
     *
     * @param int $partId Part ID
     * @return bool Deletion result
     */
    public function deletePart($partId)
    {
        $part = $this->partRepository->findById($partId);
        return $this->partRepository->delete($part);
    }

    /**
     * Bulk import parts from array
     *
     * @param array $partsData Array of part data
     * @return array Import results
     */
    public function bulkImportParts(array $partsData)
    {
        $results = [
            'success' => 0,
            'failed' => 0,
            'errors' => []
        ];

        DB::beginTransaction();

        try {
            foreach ($partsData as $partData) {
                try {
                    $this->createPart($partData);
                    $results['success']++;
                } catch (\Exception $e) {
                    $results['failed']++;
                    $results['errors'][] = [
                        'part' => $partData['name'] ?? 'Unknown',
                        'error' => $e->getMessage()
                    ];
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk part import failed: ' . $e->getMessage());
            throw $e;
        }

        return $results;
    }

    /**
     * Calculate profit margin for a part
     *
     * @param Part $part Part model
     * @return float Profit margin percentage
     */
    public function calculateProfitMargin(Part $part)
    {
        if ($part->purchase_price == 0) {
            return 0;
        }

        return (($part->selling_price - $part->purchase_price) / $part->purchase_price) * 100;
    }
}
