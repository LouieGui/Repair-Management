<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Repair\StoreRepairRequest;
use App\Http\Requests\Repair\UpdateRepairRequest;
use App\Http\Resources\RepairResource;
use App\Services\RepairService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Repair Controller
 *
 * Handles API requests for repair management.
 * Follows RESTful conventions and industry standards.
 */
class RepairController extends Controller
{
    /**
     * Create a new RepairController instance.
     *
     * @param  RepairService  $repairService  Repair service instance
     */
    public function __construct(protected RepairService $repairService)
    {
        //
    }

    /**
     * List all repairs.
     *
     * GET /api/v1/repairs
     *
     * @param  UpdateRepairRequest  $request  Request with optional filters
     * @return AnonymousResourceCollection  Collection of repairs
     */
    public function index(UpdateRepairRequest $request): AnonymousResourceCollection
    {
        $filters = $request->only([
            'customer_id', 'device_id', 'technician_id',
            'status', 'is_active', 'search'
        ]);

        $repairs = $this->repairService->listRepairs($filters);

        return RepairResource::collection($repairs);
    }

    /**
     * Create a new repair.
     *
     * POST /api/v1/repairs
     *
     * @param  StoreRepairRequest  $request  Validated request data
     * @return RepairResource  Created repair resource
     */
    public function store(StoreRepairRequest $request): RepairResource
    {
        $repair = $this->repairService->createRepair($request->validated());

        return new RepairResource($repair);
    }

    /**
     * Show a specific repair.
     *
     * GET /api/v1/repairs/{repair}
     *
     * @param  int  $repair  Repair ID
     * @return RepairResource  Repair resource
     */
    public function show(int $repair): RepairResource
    {
        $repair = $this->repairService->getRepair($repair);

        return new RepairResource($repair);
    }

    /**
     * Update a repair.
     *
     * PUT/PATCH /api/v1/repairs/{repair}
     *
     * @param  UpdateRepairRequest  $request  Validated request data
     * @param  int  $repair  Repair ID
     * @return RepairResource  Updated repair resource
     */
    public function update(UpdateRepairRequest $request, int $repair): RepairResource
    {
        $updatedRepair = $this->repairService->updateRepair($repair, $request->validated());

        return new RepairResource($updatedRepair);
    }

    /**
     * Soft delete a repair (toggle active status).
     *
     * DELETE /api/v1/repairs/{repair}
     *
     * @param  int  $repair  Repair ID
     * @return JsonResponse  Success response
     */
    public function destroy(int $repair): JsonResponse
    {
        $updatedRepair = $this->repairService->toggleRepairActiveStatus($repair);

        return response()->json([
            'message' => 'Repair status updated successfully',
            'repair' => new RepairResource($updatedRepair),
            'status' => $updatedRepair->is_active ? 'activated' : 'deactivated'
        ]);
    }

    /**
     * Update repair status.
     *
     * PUT /api/v1/repairs/{repair}/status
     *
     * @param  UpdateRepairRequest  $request  Validated request data
     * @param  int  $repair  Repair ID
     * @return RepairResource  Updated repair resource
     */
    public function updateStatus(UpdateRepairRequest $request, int $repair): RepairResource
    {
        $updatedRepair = $this->repairService->updateRepairStatus($repair, $request->validated('status'));

        return new RepairResource($updatedRepair);
    }

    /**
     * Find repairs by customer ID.
     *
     * GET /api/v1/customers/{customer}/repairs
     *
     * @param  int  $customer  Customer ID
     * @return AnonymousResourceCollection  Collection of repairs for the customer
     */
    public function findByCustomer(int $customer): AnonymousResourceCollection
    {
        $repairs = $this->repairService->findRepairsByCustomer($customer);

        return RepairResource::collection($repairs);
    }

    /**
     * Find repairs by device ID.
     *
     * GET /api/v1/devices/{device}/repairs
     *
     * @param  int  $device  Device ID
     * @return AnonymousResourceCollection  Collection of repairs for the device
     */
    public function findByDevice(int $device): AnonymousResourceCollection
    {
        $repairs = $this->repairService->findRepairsByDevice($device);

        return RepairResource::collection($repairs);
    }

    /**
     * Find repairs by technician ID.
     *
     * GET /api/v1/technicians/{technician}/repairs
     *
     * @param  int  $technician  Technician ID
     * @return AnonymousResourceCollection  Collection of repairs assigned to the technician
     */
    public function findByTechnician(int $technician): AnonymousResourceCollection
    {
        $repairs = $this->repairService->findRepairsByTechnician($technician);

        return RepairResource::collection($repairs);
    }

    /**
     * Find repairs by status.
     *
     * GET /api/v1/repairs/status/{status}
     *
     * @param  string  $status  Status to filter by
     * @return AnonymousResourceCollection  Collection of repairs with the specified status
     */
    public function findByStatus(string $status): AnonymousResourceCollection
    {
        $repairs = $this->repairService->findRepairsByStatus($status);

        return RepairResource::collection($repairs);
    }
}
