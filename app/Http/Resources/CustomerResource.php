<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Customer Resource
 *
 * Transforms customer model data into API response format.
 */
class CustomerResource extends JsonResource
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
            'fullname' => $this->fullname,
            'unique_id' => $this->unique_id,
            'contact' => $this->contact,
            'email' => $this->email,
            'address' => $this->address,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
            'deleted_at' => $this->deleted_at?->toDateTimeString(),

            // Additional computed fields
            'status' => $this->is_active ? 'active' : 'inactive',
            'account_created' => $this->created_at->diffForHumans(),
            'contact_formatted' => $this->formatContactNumber(),
        ];
    }

    /**
     * Format contact number for display.
     *
     * @return string|null
     */
    protected function formatContactNumber(): ?string
    {
        if (!$this->contact) {
            return null;
        }

        // Simple formatting - add spaces for better readability
        return preg_replace('/(\d{4})(\d{3})(\d{4})/', '$1 $2 $3', $this->contact);
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
        $response->header('X-Customer-Resource', '1.0');
    }
}
