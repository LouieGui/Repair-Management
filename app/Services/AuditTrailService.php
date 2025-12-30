<?php

namespace App\Services;

use App\Repositories\AuditTrailRepository;
use App\Models\AuditTrail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuditTrailService
{
    public function __construct(
        protected AuditTrailRepository $auditTrailRepository
    ) {}

    /**
     * Create a new audit trail record
     *
     * @param string $tableName Table name
     * @param string $event Event type
     * @param array|null $oldValues Old values
     * @param array|null $newValues New values
     * @param int|null $userId User ID
     * @param string|null $userType User type
     * @return AuditTrail Created audit trail
     */
    public function createAuditTrail(
        string $tableName,
        string $event,
        array $oldValues = null,
        array $newValues = null,
        int $userId = null,
        string $userType = null
    ) {
        // Get current user if not provided
        if (!$userId && Auth::check()) {
            $user = Auth::user();
            $userId = $user->id;
            $userType = $user->role ?? 'user';
        }

        // Default to system if no user
        $userType = $userType ?? 'system';

        // Convert arrays to JSON strings
        $oldValuesJson = $oldValues ? json_encode($oldValues) : null;
        $newValuesJson = $newValues ? json_encode($newValues) : null;

        $data = [
            'user_id' => $userId,
            'user_type' => $userType,
            'audit_table' => $tableName,
            'event' => $event,
            'old_values' => $oldValuesJson,
            'new_values' => $newValuesJson,
            'audited_at' => now()
        ];

        return $this->auditTrailRepository->create($data);
    }

    /**
     * Get all audit trails
     *
     * @return \Illuminate\Database\Eloquent\Collection Collection of audit trails
     */
    public function getAllAuditTrails()
    {
        return $this->auditTrailRepository->getAll();
    }

    /**
     * Get audit trails by user ID
     *
     * @param int $userId User ID
     * @return \Illuminate\Database\Eloquent\Collection Collection of audit trails
     */
    public function getAuditTrailsByUser($userId)
    {
        return $this->auditTrailRepository->findByUser($userId);
    }

    /**
     * Get audit trails by user type
     *
     * @param string $userType User type
     * @return \Illuminate\Database\Eloquent\Collection Collection of audit trails
     */
    public function getAuditTrailsByUserType($userType)
    {
        return $this->auditTrailRepository->findByUserType($userType);
    }

    /**
     * Get audit trails by table name
     *
     * @param string $tableName Table name
     * @return \Illuminate\Database\Eloquent\Collection Collection of audit trails
     */
    public function getAuditTrailsByTable($tableName)
    {
        return $this->auditTrailRepository->findByTable($tableName);
    }

    /**
     * Get audit trails by event type
     *
     * @param string $event Event type
     * @return \Illuminate\Database\Eloquent\Collection Collection of audit trails
     */
    public function getAuditTrailsByEvent($event)
    {
        return $this->auditTrailRepository->findByEvent($event);
    }

    /**
     * Get audit trails by date range
     *
     * @param string $startDate Start date
     * @param string $endDate End date
     * @return \Illuminate\Database\Eloquent\Collection Collection of audit trails
     */
    public function getAuditTrailsByDateRange($startDate, $endDate)
    {
        return $this->auditTrailRepository->findByDateRange($startDate, $endDate);
    }

    /**
     * Get recent audit trails
     *
     * @param int $limit Number of records to return
     * @return \Illuminate\Database\Eloquent\Collection Collection of recent audit trails
     */
    public function getRecentAuditTrails($limit = 50)
    {
        return $this->auditTrailRepository->getRecent($limit);
    }

    /**
     * Get audit trail statistics
     *
     * @return array Audit trail statistics
     */
    public function getAuditTrailStatistics()
    {
        return $this->auditTrailRepository->getStatistics();
    }

    /**
     * Clear old audit trails
     *
     * @param string $cutoffDate Cutoff date
     * @return int Number of deleted records
     */
    public function clearOldAuditTrails($cutoffDate)
    {
        return $this->auditTrailRepository->clearOldAuditTrails($cutoffDate);
    }

    /**
     * Log a creation event
     *
     * @param string $tableName Table name
     * @param array $newValues New values
     * @param int|null $userId User ID
     * @param string|null $userType User type
     * @return AuditTrail Created audit trail
     */
    public function logCreation(
        string $tableName,
        array $newValues,
        int $userId = null,
        string $userType = null
    ) {
        return $this->createAuditTrail($tableName, 'created', null, $newValues, $userId, $userType);
    }

    /**
     * Log an update event
     *
     * @param string $tableName Table name
     * @param array $oldValues Old values
     * @param array $newValues New values
     * @param int|null $userId User ID
     * @param string|null $userType User type
     * @return AuditTrail Created audit trail
     */
    public function logUpdate(
        string $tableName,
        array $oldValues,
        array $newValues,
        int $userId = null,
        string $userType = null
    ) {
        return $this->createAuditTrail($tableName, 'updated', $oldValues, $newValues, $userId, $userType);
    }

    /**
     * Log a deletion event
     *
     * @param string $tableName Table name
     * @param array $oldValues Old values
     * @param int|null $userId User ID
     * @param string|null $userType User type
     * @return AuditTrail Created audit trail
     */
    public function logDeletion(
        string $tableName,
        array $oldValues,
        int $userId = null,
        string $userType = null
    ) {
        return $this->createAuditTrail($tableName, 'deleted', $oldValues, null, $userId, $userType);
    }

    /**
     * Log a restoration event
     *
     * @param string $tableName Table name
     * @param array $restoredValues Restored values
     * @param int|null $userId User ID
     * @param string|null $userType User type
     * @return AuditTrail Created audit trail
     */
    public function logRestoration(
        string $tableName,
        array $restoredValues,
        int $userId = null,
        string $userType = null
    ) {
        return $this->createAuditTrail($tableName, 'restored', null, $restoredValues, $userId, $userType);
    }

    /**
     * Log a status change event
     *
     * @param string $tableName Table name
     * @param string $oldStatus Old status
     * @param string $newStatus New status
     * @param int $recordId Record ID
     * @param int|null $userId User ID
     * @param string|null $userType User type
     * @return AuditTrail Created audit trail
     */
    public function logStatusChange(
        string $tableName,
        string $oldStatus,
        string $newStatus,
        int $recordId,
        int $userId = null,
        string $userType = null
    ) {
        $oldValues = ['id' => $recordId, 'status' => $oldStatus];
        $newValues = ['id' => $recordId, 'status' => $newStatus];

        return $this->createAuditTrail($tableName, 'status_changed', $oldValues, $newValues, $userId, $userType);
    }

    /**
     * Log a custom event
     *
     * @param string $tableName Table name
     * @param string $event Custom event type
     * @param array|null $oldValues Old values
     * @param array|null $newValues New values
     * @param int|null $userId User ID
     * @param string|null $userType User type
     * @return AuditTrail Created audit trail
     */
    public function logCustomEvent(
        string $tableName,
        string $event,
        array $oldValues = null,
        array $newValues = null,
        int $userId = null,
        string $userType = null
    ) {
        return $this->createAuditTrail($tableName, $event, $oldValues, $newValues, $userId, $userType);
    }
}
