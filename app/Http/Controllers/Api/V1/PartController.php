<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Part\StorePartRequest;
use App\Http\Requests\Part\UpdatePartRequest;
use App\Http\Resources\PartResource;
use App\Services\PartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Part Controller
 *
 * Handles API requests for parts inventory management.
 * Follows RESTful conventions and industry standards.
 */
class PartController extends Controller
{
    /**
     * Create a new PartController instance.
     *
     * @param  PartService  $partService  Part service instance
     */
    public function __construct(protected PartService $partService)
    {
        //
    }

    /**
     * List all parts.
     *
     * GET /api/v1/parts
     *
     * @return AnonymousResourceCollection  Collection of parts
     */
    public function index(): AnonymousResourceCollection
    {
        $parts = $this->partService->getAllParts();
        return PartResource::collection($parts);
    }

    /**
     * Create a new part.
     *
     * POST /api/v1/parts
     *
     * @param  StorePartRequest  $request  Validated request data
     * @return PartResource  Created part resource
     */
    public function store(StorePartRequest $request): PartResource
    {
        $part = $this->partService->createPart($request->validated());
        return new PartResource($part);
    }

    /**
     * Show a specific part.
     *
     * GET /api/v1/parts/{part}
     *
     * @param  int  $id  Part ID
     * @return PartResource  Part resource
     */
    public function show(int $id): PartResource
    {
        $part = $this->partService->getPartById($id);
        return new PartResource($part);
    }

    /**
     * Update a part.
     *
     * PUT/PATCH /api/v1/parts/{part}
     *
     * @param  UpdatePartRequest  $request  Validated request data
     * @param  int  $id  Part ID
     * @return PartResource  Updated part resource
     */
    public function update(UpdatePartRequest $request, int $id): PartResource
    {
        $part = $this->partService->updatePart($id, $request->validated());
        return new PartResource($part);
    }

    /**
     * Toggle active status of a part.
     *
     * PUT /api/v1/parts/{part}/active
     *
     * @param  int  $id  Part ID
     * @return PartResource  Updated part resource
     */
    public function toggleActive(int $id): PartResource
    {
        $part = $this->partService->togglePartActiveStatus($id);
        return new PartResource($part);
    }

    /**
     * Soft delete a part (toggle active status).
     *
     * DELETE /api/v1/parts/{part}
     *
     * @param  int  $id  Part ID
     * @return JsonResponse  Success response
     */
    public function destroy(int $id): JsonResponse
    {
        $result = $this->partService->deletePart($id);
        return response()->json(['success' => $result], Response::HTTP_OK);
    }

    /**
     * Search parts by name.
     *
     * GET /api/v1/parts/search/{searchTerm}
     *
     * @param  string  $searchTerm  Search term
     * @return AnonymousResourceCollection  Collection of matching parts
     */
    public function searchByName(string $searchTerm): AnonymousResourceCollection
    {
        $parts = $this->partService->searchPartsByName($searchTerm);
        return PartResource::collection($parts);
    }

    /**
     * Find part by SKU.
     *
     * GET /api/v1/parts/sku/{sku}
     *
     * @param  string  $sku  SKU to search for
     * @return PartResource|JsonResponse  Part resource or not found response
     */
    public function findBySku(string $sku)
    {
        $part = $this->partService->findPartBySku($sku);

        if (!$part) {
            return response()->json(['message' => 'Part not found'], Response::HTTP_NOT_FOUND);
        }

        return new PartResource($part);
    }

    /**
     * Get parts with low stock.
     *
     * GET /api/v1/parts/low-stock/{threshold?}
     *
     * @param  int  $threshold  Stock threshold (default: 5)
     * @return AnonymousResourceCollection  Collection of low stock parts
     */
    public function getLowStockParts(int $threshold = 5): AnonymousResourceCollection
    {
        $parts = $this->partService->getLowStockParts($threshold);
        return PartResource::collection($parts);
    }

    /**
     * Add stock to a part.
     *
     * PUT /api/v1/parts/{part}/add-stock/{quantity}
     *
     * @param  int  $id  Part ID
     * @param  int  $quantity  Quantity to add
     * @return PartResource  Updated part resource
     */
    public function addStock(int $id, int $quantity): PartResource
    {
        $part = $this->partService->addStock($id, $quantity);
        return new PartResource($part);
    }

    /**
     * Subtract stock from a part.
     *
     * PUT /api/v1/parts/{part}/subtract-stock/{quantity}
     *
     * @param  int  $id  Part ID
     * @param  int  $quantity  Quantity to subtract
     * @return PartResource|JsonResponse  Updated part resource or error response
     */
    public function subtractStock(int $id, int $quantity)
    {
        try {
            $part = $this->partService->subtractStock($id, $quantity);
            return new PartResource($part);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Bulk import parts.
     *
     * POST /api/v1/parts/bulk-import
     *
     * @param  \Illuminate\Http\Request  $request  Request with parts data
     * @return JsonResponse  Import results
     */
    public function bulkImport(\Illuminate\Http\Request $request): JsonResponse
    {
        $results = $this->partService->bulkImportParts($request->all());

        return response()->json([
            'message' => 'Bulk import completed',
            'results' => $results
        ], Response::HTTP_OK);
    }
}
