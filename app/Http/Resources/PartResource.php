<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'sku' => $this->sku,
            'description' => $this->description,
            'purchase_price' => $this->purchase_price,
            'selling_price' => $this->selling_price,
            'stock_quantity' => $this->stock_quantity,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // Computed fields
            'profit_margin' => $this->calculateProfitMargin(),
            'profit_margin_percentage' => $this->calculateProfitMargin() . '%',
            'stock_status' => $this->getStockStatus(),
            'stock_value' => $this->purchase_price * $this->stock_quantity,

            // Relationships
            'repair_parts' => RepairPartResource::collection($this->whenLoaded('repairParts')),
        ];
    }

    /**
     * Calculate profit margin
     *
     * @return float
     */
    protected function calculateProfitMargin()
    {
        if ($this->purchase_price == 0) {
            return 0;
        }

        return round((($this->selling_price - $this->purchase_price) / $this->purchase_price) * 100, 2);
    }

    /**
     * Get stock status
     *
     * @return string
     */
    protected function getStockStatus()
    {
        if ($this->stock_quantity == 0) {
            return 'Out of Stock';
        } elseif ($this->stock_quantity <= 5) {
            return 'Low Stock';
        } else {
            return 'In Stock';
        }
    }
}
