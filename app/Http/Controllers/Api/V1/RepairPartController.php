<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\RepairPart\StoreRepairPartRequest;
use App\Http\Requests\RepairPart\UpdateRepairPartRequest;
use App\Http\Resources\RepairPartResource;
use App\Services\RepairPartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Repair Part Controller
 *
 * Handles API requests for repair parts (quotation items) management.
 * Follows RESTful conventions and industry standards.
 */
class RepairPartController extends Controller
{
    /**
     * Create a new RepairPartController instance.
     *
     * @param  RepairPartService  $repairPartService  Repair part service instance
     */
    public function __construct(protected RepairPartService $repairPartService)
    {
        //
    }

    /**
     * List all repair parts.
     *
     * GET /api/v1/repair-parts
     *
     * @return AnonymousResourceCollection  Collection of repair parts
     */
    public function index(): AnonymousResourceCollection
    {
        $repairParts = $this->repairPartService->getAllRepairParts();
        return RepairPartResource::collection($repairParts);
    }

    /**
     * Create a new repair part.
     *
     * POST /api/v1/repair-parts
     *
     * @param  StoreRepairPartRequest  $request  Validated request data
     * @return RepairPartResource  Created repair part resource
     */
    public function store(StoreRepairPartRequest $request): RepairPartResource
    {
        $repairPart = $this->repairPartService->createRepairPart($request->validated());
        return new RepairPartResource($repairPart);
    }

    /**
     * Show a specific repair part.
     *
     * GET /api/v1/repair-parts/{repairPart}
     *
     * @param  int  $id  Repair part ID
     * @return RepairPartResource  Repair part resource
     */
    public function show(int $id): RepairPartResource
    {
        $repairPart = $this->repairPartService->getRepairPartById($id);
        return new RepairPartResource($repairPart);
    }

    /**
     * Update a repair part.
     *
     * PUT/PATCH /api/v1/repair-parts/{repairPart}
     *
     * @param  UpdateRepairPartRequest  $request  Validated request data
     * @param  int  $id  Repair part ID
     * @return RepairPartResource  Updated repair part resource
     */
    public function update(UpdateRepairPartRequest $request, int $id): RepairPartResource
    {
        $repairPart = $this->repairPartService->updateRepairPart($id, $request->validated());
        return new RepairPartResource($repairPart);
    }

    /**
     * Toggle active status of a repair part.
     *
     * PUT /api/v1/repair-parts/{repairPart}/active
     *
     * @param  int  $id  Repair part ID
     * @return RepairPartResource  Updated repair part resource
     */
    public function toggleActive(int $id): RepairPartResource
    {
        $repairPart = $this->repairPartService->toggleRepairPartActiveStatus($id);
        return new RepairPartResource($repairPart);
    }

    /**
     * Soft delete a repair part (toggle active status).
     *
     * DELETE /api/v1/repair-parts/{repairPart}
     *
     * @param  int  $id  Repair part ID
     * @return JsonResponse  Success response
     */
    public function destroy(int $id): JsonResponse
    {
        $result = $this->repairPartService->deleteRepairPart($id);
        return response()->json(['success' => $result], JsonResponse::HTTP_OK);
    }

    /**
     * Get repair parts by repair ID.
     *
     * GET /api/v1/repairs/{repair}/parts
     *
     * @param  int  $repairId  Repair ID
     * @return AnonymousResourceCollection  Collection of repair parts for the repair
     */
    public function getByRepair(int $repairId): AnonymousResourceCollection
    {
        $repairParts = $this->repairPartService->getRepairPartsByRepair($repairId);
        return RepairPartResource::collection($repairParts);
    }

    /**
     * Get repair parts by part ID.
     *
     * GET /api/v1/parts/{part}/repairs
     *
     * @param  int  $partId  Part ID
     * @return AnonymousResourceCollection  Collection of repair parts using the part
     */
    public function getByPart(int $partId): AnonymousResourceCollection
    {
        $repairParts = $this->repairPartService->getRepairPartsByPart($partId);
        return RepairPartResource::collection($repairParts);
    }

    /**
     * Get approved repair parts.
     *
     * GET /api/v1/repair-parts/approved
     *
     * @return AnonymousResourceCollection  Collection of approved repair parts
     */
    public function getApproved(): AnonymousResourceCollection
    {
        $repairParts = $this->repairPartService->getApprovedRepairParts();
        return RepairPartResource::collection($repairParts);
    }

    /**
     * Get pending repair parts.
     *
     * GET /api/v1/repair-parts/pending
     *
     * @return AnonymousResourceCollection  Collection of pending repair parts
     */
    public function getPending(): AnonymousResourceCollection
    {
        $repairParts = $this->repairPartService->getPendingRepairParts();
        return RepairPartResource::collection($repairParts);
    }

    /**
     * Approve repair parts.
     *
     * PUT /api/v1/repair-parts/approve
     *
     * @param  \Illuminate\Http\Request  $request  Request with repair part IDs
     * @return JsonResponse  Approval results
     */
    public function approve(\Illuminate\Http\Request $request): JsonResponse
    {
        $repairPartIds = $request->input('repair_part_ids', []);
        $updatedCount = $this->repairPartService->approveRepairParts($repairPartIds);

        return response()->json([
            'message' => 'Repair parts approved successfully',
            'updated_count' => $updatedCount
        ], JsonResponse::HTTP_OK);
    }

    /**
     * Reject repair parts.
     *
     * PUT /api/v1/repair-parts/reject
     *
     * @param  \Illuminate\Http\Request  $request  Request with repair part IDs
     * @return JsonResponse  Rejection results
     */
    public function reject(\Illuminate\Http\Request $request): JsonResponse
    {
        $repairPartIds = $request->input('repair_part_ids', []);
        $updatedCount = $this->repairPartService->rejectRepairParts($repairPartIds);

        return response()->json([
            'message' => 'Repair parts rejected successfully',
            'updated_count' => $updatedCount
        ], JsonResponse::HTTP_OK);
    }

    /**
     * Calculate total price for repair parts.
     *
     * POST /api/v1/repair-parts/calculate-total
     *
     * @param  \Illuminate\Http\Request  $request  Request with repair part IDs
     * @return JsonResponse  Total price calculation
     */
    public function calculateTotal(\Illuminate\Http\Request $request): JsonResponse
    {
        $repairPartIds = $request->input('repair_part_ids', []);
        $totalPrice = $this->repairPartService->calculateTotalPrice($repairPartIds);

        return response()->json([
            'total_price' => $totalPrice,
            'formatted_total' => number_format($totalPrice, 2)
        ], JsonResponse::HTTP_OK);
    }

    /**
     * Create multiple repair parts for a repair (quotation).
     *
     * POST /api/v1/repairs/{repair}/quotation
     *
     * @param  int  $repairId  Repair ID
     * @param  StoreRepairPartRequest  $request  Validated request data
     * @return JsonResponse  Quotation creation results
     */
    public function createQuotation(int $repairId, StoreRepairPartRequest $request): JsonResponse
    {
        $partsData = $request->all();
        $createdParts = $this->repairPartService->createQuotationParts($repairId, $partsData);

        return response()->json([
            'message' => 'Quotation parts created successfully',
            'created_parts' => RepairPartResource::collection($createdParts)
        ], JsonResponse::HTTP_CREATED);
    }

    /**
     * Approve quotation and update repair status.
     *
     * POST /api/v1/repairs/{repair}/approve-quotation
     *
     * @param  int  $repairId  Repair ID
     * @param  \Illuminate\Http\Request  $request  Request with repair part IDs
     * @return JsonResponse  Quotation approval results
     */
    public function approveQuotation(int $repairId, \Illuminate\Http\Request $request): JsonResponse
    {
        $repairPartIds = $request->input('repair_part_ids', []);
        $results = $this->repairPartService->approveQuotation($repairId, $repairPartIds);

        return response()->json([
            'message' => 'Quotation approved successfully',
            'results' => $results
        ], JsonResponse::HTTP_OK);
    }
}
