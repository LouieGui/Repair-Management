<?php

namespace App\Services;

use App\Repositories\DeviceRepository;
use App\Models\Device;
use Illuminate\Database\Eloquent\Collection;

/**
 * Device Service
 *
 * Handles business logic for device operations.
 * Acts as an intermediary between controllers and repositories.
 */
class DeviceService
{
    /**
     * Create a new DeviceService instance.
     *
     * @param  DeviceRepository  $deviceRepository  Device repository instance
     */
    public function __construct(protected DeviceRepository $deviceRepository)
    {
        //
    }

    /**
     * Create a new device with business logic.
     *
     * Automatically sets default values and validates customer existence.
     *
     * @param  array  $data  Device data
     * @return Device  Created device
     */
    public function createDevice(array $data): Device
    {
        // Set default values if not provided
        $data['is_active'] = $data['is_active'] ?? true;
        $data['device_warranty_status'] = $data['device_warranty_status'] ?? 'none';

        return $this->deviceRepository->create($data);
    }

    /**
     * Update an existing device.
     *
     * @param  int  $deviceId  Device ID to update
     * @param  array  $data  Data to update
     * @return Device  Updated device
     */
    public function updateDevice(int $deviceId, array $data): Device
    {
        $device = $this->deviceRepository->findById($deviceId);
        return $this->deviceRepository->update($device, $data);
    }

    /**
     * Get a single device by ID.
     *
     * @param  int  $deviceId  Device ID to retrieve
     * @return Device  Found device
     */
    public function getDevice(int $deviceId): Device
    {
        return $this->deviceRepository->findById($deviceId);
    }

    /**
     * List devices with optional filters.
     *
     * @param  array  $filters  Filters to apply
     * @return Collection  Collection of devices
     */
    public function listDevices(array $filters = []): Collection
    {
        return $this->deviceRepository->list($filters);
    }

    /**
     * Toggle device active status (soft delete/restore).
     *
     * @param  int  $deviceId  Device ID to toggle
     * @return Device  Updated device
     */
    public function toggleDeviceActiveStatus(int $deviceId): Device
    {
        $device = $this->deviceRepository->findById($deviceId);
        return $this->deviceRepository->toggleActiveStatus($device);
    }

    /**
     * Find devices by customer ID.
     *
     * @param  int  $customerId  Customer ID to search for
     * @return Collection  Collection of devices for the customer
     */
    public function findDevicesByCustomer(int $customerId): Collection
    {
        return $this->deviceRepository->findByCustomerId($customerId);
    }

    /**
     * Find device by serial number.
     *
     * @param  string  $serialNumber  Serial number to search for
     * @return Device|null  Found device or null
     */
    public function findDeviceBySerialNumber(string $serialNumber): ?Device
    {
        return $this->deviceRepository->findBySerialNumber($serialNumber);
    }

    /**
     * Find device by IMEI.
     *
     * @param  string  $imei  IMEI to search for
     * @return Device|null  Found device or null
     */
    public function findDeviceByImei(string $imei): ?Device
    {
        return $this->deviceRepository->findByImei($imei);
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
        return $this->deviceRepository->isSerialNumberAvailable($serialNumber, $excludeDeviceId);
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
        return $this->deviceRepository->isImeiAvailable($imei, $excludeDeviceId);
    }
}
