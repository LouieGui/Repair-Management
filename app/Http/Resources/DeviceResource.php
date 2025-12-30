<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Device Resource
 *
 * Transforms device model data into API response format.
 */
class DeviceResource extends JsonResource
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
            'brand' => $this->brand,
            'model' => $this->model,
            'serial_number' => $this->serial_number,
            'imei' => $this->imei,
            'device_warranty_status' => $this->device_warranty_status,
            'device_password' => $this->device_password ? '*****' : null,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
            'deleted_at' => $this->deleted_at?->toDateTimeString(),

            // Additional computed fields
            'status' => $this->is_active ? 'active' : 'inactive',
            'device_created' => $this->created_at->diffForHumans(),
            'full_device_name' => $this->brand . ' ' . $this->model,
            'has_warranty' => $this->device_warranty_status === 'active',

            // Customer information
            'customer' => $this->whenLoaded('customer', function () {
                return [
                    'id' => $this->customer->id,
                    'fullname' => $this->customer->fullname,
                    'contact' => $this->customer->contact,
                    'unique_id' => $this->customer->unique_id,
                ];
            })
        ];
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
        $response->header('X-Device-Resource', '1.0');
    }
}
