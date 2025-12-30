<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * User Resource
 *
 * Transforms user model data into API response format.
 */
class UserResource extends JsonResource
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
            'username' => $this->username,
            'email' => $this->email,
            'phone' => $this->phone,
            'role' => $this->role,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
            'deleted_at' => $this->deleted_at?->toDateTimeString(),

            // Additional computed fields
            'status' => $this->is_active ? 'active' : 'inactive',
            'account_created' => $this->created_at->diffForHumans(),
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
        $response->header('X-User-Resource', '1.0');
    }
}
