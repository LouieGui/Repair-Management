<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Return\StoreReturnRequest;
use App\Http\Requests\Return\UpdateReturnRequest;
use App\Http\Resources\ReturnResource;
use App\Services\ReturnService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ReturnController extends Controller
{
    public function __construct(
        protected ReturnService $returnService
    ) {}

    /**
     * Display a listing of returns.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $returns = $this->returnService->getAllReturns();
        return ReturnResource::collection($returns);
    }

    /**
     * Store a newly created return in storage.
     *
     * @param StoreReturnRequest $request
     * @return ReturnResource
     */
    public function store(StoreReturnRequest $request)
    {
        $return = $this->returnService->createReturn($request->validated());
        return new ReturnResource($return);
    }

    /**
     * Display the specified return.
     *
     * @param int $id
     * @return ReturnResource
     */
    public function show($id)
    {
        $return = $this->returnService->getReturnById($id);
        return new ReturnResource($return);
    }

    /**
     * Update the specified return in storage.
     *
     * @param UpdateReturnRequest $request
     * @param int $id
     * @return ReturnResource
     */
    public function update(UpdateReturnRequest $request, $id)
    {
        $return = $this->returnService->updateReturn($id, $request->validated());
        return new ReturnResource($return);
    }

    /**
     * Toggle active status of the specified return.
     *
     * @param int $id
     * @return ReturnResource
     */
    public function toggleActive($id)
    {
        $return = $this->returnService->toggleReturnActiveStatus($id);
        return new ReturnResource($return);
    }

    /**
     * Remove the specified return from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $result = $this->returnService->deleteReturn($id);
        return response()->json(['success' => $result], Response::HTTP_OK);
    }

    /**
     * Get returns by repair ID.
     *
     * @param int $repairId
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getByRepair($repairId)
    {
        $returns = $this->returnService->getReturnsByRepair($repairId);
        return ReturnResource::collection($returns);
    }

    /**
     * Get returns by return type.
     *
     * @param string $returnType
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getByType($returnType)
    {
        $returns = $this->returnService->getReturnsByType($returnType);
        return ReturnResource::collection($returns);
    }

    /**
     * Get returns by warranty status.
     *
     * @param bool $isUnderWarranty
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getByWarrantyStatus($isUnderWarranty)
    {
        $returns = $this->returnService->getReturnsByWarrantyStatus($isUnderWarranty);
        return ReturnResource::collection($returns);
    }

    /**
     * Process a warranty claim and create new repair.
     *
     * @param StoreReturnRequest $request
     * @return JsonResponse
     */
    public function processWarrantyClaim(StoreReturnRequest $request)
    {
        $result = $this->returnService->processWarrantyClaim($request->validated());

        return response()->json([
            'return' => new ReturnResource($result['return']),
            'new_repair' => new ReturnResource($result['new_repair']),
            'message' => 'Warranty claim processed successfully'
        ], Response::HTTP_CREATED);
    }
}
