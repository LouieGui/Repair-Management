<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Return\StoreReturnRequest;
use App\Http\Requests\Return\UpdateReturnRequest;
use App\Http\Resources\ReturnResource;
use App\Services\ReturnService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Return Controller
 *
 * Handles API requests for return management.
 * Follows RESTful conventions and industry standards.
 */
class ReturnController extends Controller
{
    /**
     * Create a new ReturnController instance.
     *
     * @param  ReturnService  $returnService  Return service instance
     */
    public function __construct(protected ReturnService $returnService)
    {
        //
    }

    /**
     * List all returns.
     *
     * GET /api/v1/returns
     *
     * @return AnonymousResourceCollection  Collection of returns
     */
    public function index(): AnonymousResourceCollection
    {
        $returns = $this->returnService->getAllReturns();
        return ReturnResource::collection($returns);
    }

    /**
     * Create a new return.
     *
     * POST /api/v1/returns
     *
     * @param  StoreReturnRequest  $request  Validated request data
     * @return ReturnResource  Created return resource
     */
    public function store(StoreReturnRequest $request): ReturnResource
    {
        $return = $this->returnService->createReturn($request->validated());
        return new ReturnResource($return);
    }

    /**
     * Show a specific return.
     *
     * GET /api/v1/returns/{return}
     *
     * @param  int  $id  Return ID
     * @return ReturnResource  Return resource
     */
    public function show(int $id): ReturnResource
    {
        $return = $this->returnService->getReturnById($id);
        return new ReturnResource($return);
    }

    /**
     * Update a return.
     *
     * PUT/PATCH /api/v1/returns/{return}
     *
     * @param  UpdateReturnRequest  $request  Validated request data
     * @param  int  $id  Return ID
     * @return ReturnResource  Updated return resource
     */
    public function update(UpdateReturnRequest $request, int $id): ReturnResource
    {
        $return = $this->returnService->updateReturn($id, $request->validated());
        return new ReturnResource($return);
    }

    /**
     * Toggle active status of a return.
     *
     * PUT /api/v1/returns/{return}/active
     *
     * @param  int  $id  Return ID
     * @return ReturnResource  Updated return resource
     */
    public function toggleActive(int $id): ReturnResource
    {
        $return = $this->returnService->toggleReturnActiveStatus($id);
        return new ReturnResource($return);
    }

    /**
     * Soft delete a return (toggle active status).
     *
     * DELETE /api/v1/returns/{return}
     *
     * @param  int  $id  Return ID
     * @return JsonResponse  Success response
     */
    public function destroy(int $id): JsonResponse
    {
        $result = $this->returnService->deleteReturn($id);
        return response()->json(['success' => $result], Response::HTTP_OK);
    }

    /**
     * Get returns by repair ID.
     *
     * GET /api/v1/repairs/{repair}/returns
     *
     * @param  int  $repairId  Repair ID
     * @return AnonymousResourceCollection  Collection of returns for the repair
     */
    public function getByRepair(int $repairId): AnonymousResourceCollection
    {
        $returns = $this->returnService->getReturnsByRepair($repairId);
        return ReturnResource::collection($returns);
    }

    /**
     * Get returns by return type.
     *
     * GET /api/v1/returns/type/{returnType}
     *
     * @param  string  $returnType  Return type
     * @return AnonymousResourceCollection  Collection of returns of the specified type
     */
    public function getByType(string $returnType): AnonymousResourceCollection
    {
        $returns = $this->returnService->getReturnsByType($returnType);
        return ReturnResource::collection($returns);
    }

    /**
     * Get returns by warranty status.
     *
     * GET /api/v1/returns/warranty/{isUnderWarranty}
     *
     * @param  bool  $isUnderWarranty  Warranty status
     * @return AnonymousResourceCollection  Collection of returns with the specified warranty status
     */
    public function getByWarrantyStatus(bool $isUnderWarranty): AnonymousResourceCollection
    {
        $returns = $this->returnService->getReturnsByWarrantyStatus($isUnderWarranty);
        return ReturnResource::collection($returns);
    }

    /**
     * Process a warranty claim and create new repair.
     *
     * POST /api/v1/returns/warranty-claim
     *
     * @param  StoreReturnRequest  $request  Validated request data
     * @return JsonResponse  Warranty claim processing result
     */
    public function processWarrantyClaim(StoreReturnRequest $request): JsonResponse
    {
        $result = $this->returnService->processWarrantyClaim($request->validated());

        return response()->json([
            'return' => new ReturnResource($result['return']),
            'new_repair' => new ReturnResource($result['new_repair']),
            'message' => 'Warranty claim processed successfully'
        ], Response::HTTP_CREATED);
    }
}
