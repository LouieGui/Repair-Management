<?php

namespace App\Repositories;

use App\Models\Device;
use Illuminate\Database\Eloquent\Collection;

/**
 * Device Repository
 *
 * Handles database operations for Device model.
 * Provides a clean interface for device data access.
 */
class DeviceRepository
{
    /**
     * Create a new device record.
     *
     * @param  array  $data  Device data to create
     * @return Device  Created device instance
     */
    public function create(array $data): Device
    {
        return Device::create($data);
    }

    /**
     * Find a device by ID.
     *
     * @param  int  $id  Device ID to find
     * @return Device  Found device instance
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException  If device not found
     */
    public function findById(int $id): Device
    {
        return Device::findOrFail($id);
    }

    /**
     * Update a device record.
     *
     * @param  Device  $device  Device instance to update
     * @param  array  $data  Data to update
     * @return Device  Updated device instance
     */
    public function update(Device $device, array $data): Device
    {
        $device->update($data);
        return $device;
    }

    /**
     * List devices with optional filters.
     *
     * @param  array  $filters  Filters to apply (e.g., ['customer_id' => 1, 'is_active' => true, 'search' => 'iPhone'])
     * @return Collection  Collection of Device instances
     */
    public function list(array $filters = []): Collection
    {
        $query = Device::query();

        // Apply filters
        if (isset($filters['customer_id'])) {
            $query->where('customer_id', $filters['customer_id']);
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        if (isset($filters['brand'])) {
            $query->where('brand', 'like', '%' . $filters['brand'] . '%');
        }

        if (isset($filters['model'])) {
            $query->where('model', 'like', '%' . $filters['model'] . '%');
        }

        if (isset($filters['search'])) {
            $query->where(function($q) use ($filters) {
                $q->where('brand', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('model', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('serial_number', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('imei', 'like', '%' . $filters['search'] . '%');
            });
        }

        return $query->with('customer')->orderBy('created_at', 'desc')->get();
    }

    /**
     * Toggle device active status (soft delete/restore).
     *
     * @param  Device  $device  Device instance to toggle
     * @return Device  Updated device instance
     */
    public function toggleActiveStatus(Device $device): Device
    {
        $device->is_active = !$device->is_active;
        $device->save();
        return $device;
    }

    /**
     * Find devices by customer ID.
     *
     * @param  int  $customerId  Customer ID to search for
     * @return Collection  Collection of devices for the customer
     */
    public function findByCustomerId(int $customerId): Collection
    {
        return Device::where('customer_id', $customerId)
                   ->orderBy('created_at', 'desc')
                   ->get();
    }

    /**
     * Find device by serial number.
     *
     * @param  string  $serialNumber  Serial number to search for
     * @return Device|null  Found device or null if not found
     */
    public function findBySerialNumber(string $serialNumber): ?Device
    {
        return Device::where('serial_number', $serialNumber)->first();
    }

    /**
     * Find device by IMEI.
     *
     * @param  string  $imei  IMEI to search for
     * @return Device|null  Found device or null if not found
     */
    public function findByImei(string $imei): ?Device
    {
        return Device::where('imei', $imei)->first();
    }

    /**
     * Check if serial number is already registered for a different device.
     *
     * @param  string  $serialNumber  Serial number to check
     * @param  int|null  $excludeDeviceId  Device ID to exclude from check
     * @return bool  True if serial number is available, false if taken
     */
    public function isSerialNumberAvailable(string $serialNumber, ?int $excludeDeviceId = null): bool
    {
        $query = Device::where('serial_number', $serialNumber);

        if ($excludeDeviceId) {
            $query->where('id', '!=', $excludeDeviceId);
        }

        return !$query->exists();
    }

    /**
     * Check if IMEI is already registered for a different device.
     *
     * @param  string  $imei  IMEI to check
     * @param  int|null  $excludeDeviceId  Device ID to exclude from check
     * @return bool  True if IMEI is available, false if taken
     */
    public function isImeiAvailable(string $imei, ?int $excludeDeviceId = null): bool
    {
        $query = Device::where('imei', $imei);

        if ($excludeDeviceId) {
            $query->where('id', '!=', $excludeDeviceId);
        }

        return !$query->exists();
    }
}
