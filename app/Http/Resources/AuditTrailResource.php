<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuditTrailResource extends JsonResource
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
            'user_id' => $this->user_id,
            'user_type' => $this->user_type,
            'audit_table' => $this->audit_table,
            'event' => $this->event,
            'old_values' => $this->old_values ? json_decode($this->old_values, true) : null,
            'new_values' => $this->new_values ? json_decode($this->new_values, true) : null,
            'audited_at' => $this->audited_at,

            // Relationships
            'user' => new UserResource($this->whenLoaded('user')),

            // Computed fields
            'event_label' => $this->getEventLabel(),
            'formatted_date' => $this->audited_at->format('Y-m-d H:i:s'),
            'formatted_time' => $this->audited_at->format('H:i:s'),
            'formatted_date_time' => $this->audited_at->format('M d, Y H:i:s'),
        ];
    }

    /**
     * Get event label
     *
     * @return string
     */
    protected function getEventLabel()
    {
        return match($this->event) {
            'created' => 'Created',
            'updated' => 'Updated',
            'deleted' => 'Deleted',
            'restored' => 'Restored',
            'status_changed' => 'Status Changed',
            default => ucfirst(str_replace('_', ' ', $this->event)),
        };
    }
}
