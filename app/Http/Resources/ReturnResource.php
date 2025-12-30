<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReturnResource extends JsonResource
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
            'new_repair_id' => $this->new_repair_id,
            'return_type' => $this->return_type,
            'return_date' => $this->return_date,
            'return_reason' => $this->return_reason,
            'is_same_issue' => $this->is_same_issue,
            'is_under_warranty' => $this->is_under_warranty,
            'received_by' => $this->received_by,
            'notes' => $this->notes,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // Relationships
            'repair' => new RepairResource($this->whenLoaded('repair')),
            'new_repair' => new RepairResource($this->whenLoaded('newRepair')),
            'received_by_user' => new UserResource($this->whenLoaded('receivedBy')),

            // Computed fields
            'warranty_status' => $this->is_under_warranty ? 'Under Warranty' : 'Not Under Warranty',
            'return_type_label' => $this->getReturnTypeLabel(),
        ];
    }

    /**
     * Get the return type label
     *
     * @return string
     */
    protected function getReturnTypeLabel()
    {
        return match($this->return_type) {
            'warranty_claim' => 'Warranty Claim',
            'recheck' => 'Recheck',
            'different_issue' => 'Different Issue',
            default => 'Unknown',
        };
    }
}
