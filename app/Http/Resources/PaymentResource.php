<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
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
            'amount' => $this->amount,
            'payment_method' => $this->payment_method,
            'status' => $this->status,
            'transaction_id' => $this->transaction_id,
            'notes' => $this->notes,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // Relationships
            'repair' => new RepairResource($this->whenLoaded('repair')),

            // Computed fields
            'status_label' => $this->getStatusLabel(),
            'payment_method_label' => $this->getPaymentMethodLabel(),
            'formatted_amount' => number_format($this->amount, 2),
            'is_successful' => $this->status === 'completed',
            'is_refunded' => $this->status === 'refunded',
        ];
    }

    /**
     * Get status label
     *
     * @return string
     */
    protected function getStatusLabel()
    {
        return match($this->status) {
            'pending' => 'Pending',
            'completed' => 'Completed',
            'failed' => 'Failed',
            'refunded' => 'Refunded',
            default => 'Unknown',
        };
    }

    /**
     * Get payment method label
     *
     * @return string
     */
    protected function getPaymentMethodLabel()
    {
        return match($this->payment_method) {
            'cash' => 'Cash',
            'card' => 'Credit/Debit Card',
            'online' => 'Online Payment',
            default => 'Unknown',
        };
    }
}
