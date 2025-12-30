<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Device\StoreDeviceRequest;
use App\Http\Requests\Device\UpdateDeviceRequest;
use App\Http\Resources\DeviceResource;
use App\Services\DeviceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Device Controller
 *
 * Handles API requests for device management.
 * Follows RESTful conventions and industry standards.
 */
class DeviceController extends Controller
{
    /**
     * Create a new DeviceController instance.
     *
     * @param  DeviceService  $deviceService  Device service instance
     */
    public function __construct(protected DeviceService $deviceService)
    {
        //
    }

    /**
     * List all devices.
     *
     * GET /api/v1/devices
     *
     * @param  UpdateDeviceRequest  $request  Request with optional filters
     * @return AnonymousResourceCollection  Collection of devices
     */
    public function index(UpdateDeviceRequest $request): AnonymousResourceCollection
    {
        $filters = $request->only(['customer_id', 'is_active', 'brand', 'model', 'search']);

        $devices = $this->deviceService->listDevices($filters);

        return DeviceResource::collection($devices);
    }

    /**
     * Create a new device.
     *
     * POST /api/v1/devices
     *
     * @param  StoreDeviceRequest  $request  Validated request data
     * @return DeviceResource  Created device resource
     */
    public function store(StoreDeviceRequest $request): DeviceResource
    {
        $device = $this->deviceService->createDevice($request->validated());

        return new DeviceResource($device);
    }

    /**
     * Show a specific device.
     *
     * GET /api/v1/devices/{device}
     *
     * @param  int  $device  Device ID
     * @return DeviceResource  Device resource
     */
    public function show(int $device): DeviceResource
    {
        $device = $this->deviceService->getDevice($device);

        return new DeviceResource($device);
    }

    /**
     * Update a device.
     *
     * PUT/PATCH /api/v1/devices/{device}
     *
     * @param  UpdateDeviceRequest  $request  Validated request data
     * @param  int  $device  Device ID
     * @return DeviceResource  Updated device resource
     */
    public function update(UpdateDeviceRequest $request, int $device): DeviceResource
    {
        $updatedDevice = $this->deviceService->updateDevice($device, $request->validated());

        return new DeviceResource($updatedDevice);
    }

    /**
     * Soft delete a device (toggle active status).
     *
     * DELETE /api/v1/devices/{device}
     *
     * @param  int  $device  Device ID
     * @return JsonResponse  Success response
     */
    public function destroy(int $device): JsonResponse
    {
        $updatedDevice = $this->deviceService->toggleDeviceActiveStatus($device);

        return response()->json([
            'message' => 'Device status updated successfully',
            'device' => new DeviceResource($updatedDevice),
            'status' => $updatedDevice->is_active ? 'activated' : 'deactivated'
        ]);
    }
}
