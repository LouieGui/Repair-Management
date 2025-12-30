<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Repair Resource
 *
 * Transforms repair model data into API response format.
 */
class RepairResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'customer_id' => $this->customer_id,
            'device_id' => $this->device_id,
            'technician_id' => $this->technician_id,
            'reported_issue' => $this->reported_issue,
            'accessories' => $this->accessories,
            'status' => $this->status,
            'diagnosis' => $this->diagnosis,
            'technician_notes' => $this->technician_notes,
            'date_received' => $this->date_received,
            'estimated_completion_date' => $this->estimated_completion_date,
            'date_completed' => $this->date_completed,
            'warranty_days' => $this->warranty_days,
            'warranty_until_date' => $this->warranty_until_date,
            'total_amount' => $this->total_amount,
            'paid_amount' => $this->paid_amount,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
            'deleted_at' => $this->deleted_at?->toDateTimeString(),

            // Additional computed fields
            'status_label' => $this->getStatusLabel(),
            'repair_created' => $this->created_at->diffForHumans(),
            'amount_remaining' => $this->total_amount - $this->paid_amount,
            'payment_status' => $this->paid_amount >= $this->total_amount ? 'paid' : 'unpaid',
            'warranty_active' => $this->warranty_until_date ? now()->lte($this->warranty_until_date) : false,

            // Relationships
            'customer' => $this->whenLoaded('customer', function () {
                return [
                    'id' => $this->customer->id,
                    'fullname' => $this->customer->fullname,
                    'contact' => $this->customer->contact,
                    'unique_id' => $this->customer->unique_id,
                ];
            }),

            'device' => $this->whenLoaded('device', function () {
                return [
                    'id' => $this->device->id,
                    'brand' => $this->device->brand,
                    'model' => $this->device->model,
                    'serial_number' => $this->device->serial_number,
                    'full_device_name' => $this->device->brand . ' ' . $this->device->model,
                ];
            }),

            'technician' => $this->whenLoaded('technician', function () {
                return $this->technician ? [
                    'id' => $this->technician->id,
                    'username' => $this->technician->username,
                    'email' => $this->technician->email,
                    'role' => $this->technician->role,
                ] : null;
            })
        ];
    }

    /**
     * Get status label for display.
     *
     * @return string
     */
    protected function getStatusLabel(): string
    {
        $statusLabels = [
            'received' => 'Received',
            'diagnosing' => 'Diagnosing',
            'waiting_approval' => 'Waiting Approval',
            'approved' => 'Approved',
            'waiting_parts' => 'Waiting Parts',
            'in_progress' => 'In Progress',
            'testing' => 'Testing',
            'ready_for_pickup' => 'Ready for Pickup',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled'
        ];

        return $statusLabels[$this->status] ?? $this->status;
    }

    /**
     * Get additional data that should be returned with the resource.
     *
     * @param  Request  $request
     * @return array<string, mixed>
     */
    public function with(Request $request): array
    {
        return [
            'meta' => [
                'version' => '1.0',
                'timestamp' => now()->toDateTimeString(),
            ]
        ];
    }

    /**
     * Customize the response for different scenarios.
     *
     * @param  Request  $request
     * @return array<string, mixed>
     */
    public function withResponse(Request $request, $response): void
    {
        $response->header('X-Repair-Resource', '1.0');
    }
}
