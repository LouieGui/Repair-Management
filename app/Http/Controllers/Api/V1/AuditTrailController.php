<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuditTrail\StoreAuditTrailRequest;
use App\Http\Resources\AuditTrailResource;
use App\Services\AuditTrailService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Audit Trail Controller
 *
 * Handles API requests for audit trail management.
 * Follows RESTful conventions and industry standards.
 */
class AuditTrailController extends Controller
{
    /**
     * Create a new AuditTrailController instance.
     *
     * @param  AuditTrailService  $auditTrailService  Audit trail service instance
     */
    public function __construct(protected AuditTrailService $auditTrailService)
    {
        //
    }

    /**
     * List all audit trails.
     *
     * GET /api/v1/audit-trails
     *
     * @return AnonymousResourceCollection  Collection of audit trails
     */
    public function index(): AnonymousResourceCollection
    {
        $auditTrails = $this->auditTrailService->getAllAuditTrails();
        return AuditTrailResource::collection($auditTrails);
    }

    /**
     * Create a new audit trail.
     *
     * POST /api/v1/audit-trails
     *
     * @param  StoreAuditTrailRequest  $request  Validated request data
     * @return AuditTrailResource  Created audit trail resource
     */
    public function store(StoreAuditTrailRequest $request): AuditTrailResource
    {
        $auditTrail = $this->auditTrailService->createAuditTrail(
            $request->input('audit_table'),
            $request->input('event'),
            $request->input('old_values') ? json_decode($request->input('old_values'), true) : null,
            $request->input('new_values') ? json_decode($request->input('new_values'), true) : null,
            $request->input('user_id'),
            $request->input('user_type')
        );

        return new AuditTrailResource($auditTrail);
    }

    /**
     * Get audit trails by user ID.
     *
     * GET /api/v1/users/{user}/audit-trails
     *
     * @param  int  $userId  User ID
     * @return AnonymousResourceCollection  Collection of audit trails for the user
     */
    public function getByUser(int $userId): AnonymousResourceCollection
    {
        $auditTrails = $this->auditTrailService->getAuditTrailsByUser($userId);
        return AuditTrailResource::collection($auditTrails);
    }

    /**
     * Get audit trails by user type.
     *
     * GET /api/v1/audit-trails/user-type/{userType}
     *
     * @param  string  $userType  User type
     * @return AnonymousResourceCollection  Collection of audit trails for the user type
     */
    public function getByUserType(string $userType): AnonymousResourceCollection
    {
        $auditTrails = $this->auditTrailService->getAuditTrailsByUserType($userType);
        return AuditTrailResource::collection($auditTrails);
    }

    /**
     * Get audit trails by table name.
     *
     * GET /api/v1/audit-trails/table/{tableName}
     *
     * @param  string  $tableName  Table name
     * @return AnonymousResourceCollection  Collection of audit trails for the table
     */
    public function getByTable(string $tableName): AnonymousResourceCollection
    {
        $auditTrails = $this->auditTrailService->getAuditTrailsByTable($tableName);
        return AuditTrailResource::collection($auditTrails);
    }

    /**
     * Get audit trails by event type.
     *
     * GET /api/v1/audit-trails/event/{event}
     *
     * @param  string  $event  Event type
     * @return AnonymousResourceCollection  Collection of audit trails for the event
     */
    public function getByEvent(string $event): AnonymousResourceCollection
    {
        $auditTrails = $this->auditTrailService->getAuditTrailsByEvent($event);
        return AuditTrailResource::collection($auditTrails);
    }

    /**
     * Get audit trails by date range.
     *
     * GET /api/v1/audit-trails/date-range/{startDate}/{endDate}
     *
     * @param  string  $startDate  Start date
     * @param  string  $endDate  End date
     * @return AnonymousResourceCollection  Collection of audit trails in date range
     */
    public function getByDateRange(string $startDate, string $endDate): AnonymousResourceCollection
    {
        $auditTrails = $this->auditTrailService->getAuditTrailsByDateRange($startDate, $endDate);
        return AuditTrailResource::collection($auditTrails);
    }

    /**
     * Get recent audit trails.
     *
     * GET /api/v1/audit-trails/recent/{limit?}
     *
     * @param  int  $limit  Number of records to return (default: 50)
     * @return AnonymousResourceCollection  Collection of recent audit trails
     */
    public function getRecent(int $limit = 50): AnonymousResourceCollection
    {
        $auditTrails = $this->auditTrailService->getRecentAuditTrails($limit);
        return AuditTrailResource::collection($auditTrails);
    }

    /**
     * Get audit trail statistics.
     *
     * GET /api/v1/audit-trails/statistics
     *
     * @return JsonResponse  Audit trail statistics
     */
    public function getStatistics(): JsonResponse
    {
        $statistics = $this->auditTrailService->getAuditTrailStatistics();

        return response()->json([
            'message' => 'Audit trail statistics retrieved successfully',
            'statistics' => $statistics
        ], JsonResponse::HTTP_OK);
    }

    /**
     * Clear old audit trails.
     *
     * DELETE /api/v1/audit-trails/clear/{cutoffDate}
     *
     * @param  string  $cutoffDate  Cutoff date
     * @return JsonResponse  Clear operation results
     */
    public function clearOldAuditTrails(string $cutoffDate): JsonResponse
    {
        $deletedCount = $this->auditTrailService->clearOldAuditTrails($cutoffDate);

        return response()->json([
            'message' => 'Old audit trails cleared successfully',
            'deleted_count' => $deletedCount,
            'cutoff_date' => $cutoffDate
        ], JsonResponse::HTTP_OK);
    }
}
