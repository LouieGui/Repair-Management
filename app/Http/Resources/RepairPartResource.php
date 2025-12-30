<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RepairPartResource extends JsonResource
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
            'repair_id' => $this->repair_id,
            'part_id' => $this->part_id,
            'custom_part_name' => $this->custom_part_name,
            'quantity' => $this->quantity,
            'unit_price' => $this->unit_price,
            'total_price' => $this->total_price,
            'is_approved' => $this->is_approved,
            'notes' => $this->notes,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // Relationships
            'repair' => new RepairResource($this->whenLoaded('repair')),
            'part' => new PartResource($this->whenLoaded('part')),

            // Computed fields
            'part_type' => $this->part_id ? 'Inventory Part' : 'Custom Part',
            'approval_status' => $this->is_approved ? 'Approved' : 'Pending',
            'part_name' => $this->part ? $this->part->name : $this->custom_part_name,
        ];
    }
}
