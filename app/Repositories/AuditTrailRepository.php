<?php

namespace App\Repositories;

use App\Models\AuditTrail;

class AuditTrailRepository
{
    /**
     * Create a new audit trail record
     *
     * @param array $data Audit trail data
     * @return AuditTrail Created audit trail
     */
    public function create(array $data)
    {
        return AuditTrail::create($data);
    }

    /**
     * Get all audit trails with relationships
     *
     * @return \Illuminate\Database\Eloquent\Collection Collection of audit trails
     */
    public function getAll()
    {
        return AuditTrail::with(['user'])
            ->orderBy('audited_at', 'desc')
            ->get();
    }

    /**
     * Find audit trails by user ID
     *
     * @param int $userId User ID
     * @return \Illuminate\Database\Eloquent\Collection Collection of audit trails
     */
    public function findByUser($userId)
    {
        return AuditTrail::with(['user'])
            ->where('user_id', $userId)
            ->orderBy('audited_at', 'desc')
            ->get();
    }

    /**
     * Find audit trails by user type
     *
     * @param string $userType User type
     * @return \Illuminate\Database\Eloquent\Collection Collection of audit trails
     */
    public function findByUserType($userType)
    {
        return AuditTrail::with(['user'])
            ->where('user_type', $userType)
            ->orderBy('audited_at', 'desc')
            ->get();
    }

    /**
     * Find audit trails by table name
     *
     * @param string $tableName Table name
     * @return \Illuminate\Database\Eloquent\Collection Collection of audit trails
     */
    public function findByTable($tableName)
    {
        return AuditTrail::with(['user'])
            ->where('audit_table', $tableName)
            ->orderBy('audited_at', 'desc')
            ->get();
    }

    /**
     * Find audit trails by event type
     *
     * @param string $event Event type
     * @return \Illuminate\Database\Eloquent\Collection Collection of audit trails
     */
    public function findByEvent($event)
    {
        return AuditTrail::with(['user'])
            ->where('event', $event)
            ->orderBy('audited_at', 'desc')
            ->get();
    }

    /**
     * Find audit trails by date range
     *
     * @param string $startDate Start date
     * @param string $endDate End date
     * @return \Illuminate\Database\Eloquent\Collection Collection of audit trails
     */
    public function findByDateRange($startDate, $endDate)
    {
        return AuditTrail::with(['user'])
            ->whereBetween('audited_at', [$startDate, $endDate])
            ->orderBy('audited_at', 'desc')
            ->get();
    }

    /**
     * Get recent audit trails
     *
     * @param int $limit Number of records to return
     * @return \Illuminate\Database\Eloquent\Collection Collection of recent audit trails
     */
    public function getRecent($limit = 50)
    {
        return AuditTrail::with(['user'])
            ->orderBy('audited_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get audit trail statistics
     *
     * @return array Audit trail statistics
     */
    public function getStatistics()
    {
        $total = AuditTrail::count();
        $users = AuditTrail::distinct('user_id')->count('user_id');
        $tables = AuditTrail::distinct('audit_table')->count('audit_table');

        $events = AuditTrail::select('event', AuditTrail::raw('count(*) as count'))
            ->groupBy('event')
            ->orderBy('count', 'desc')
            ->get();

        $userTypes = AuditTrail::select('user_type', AuditTrail::raw('count(*) as count'))
            ->groupBy('user_type')
            ->orderBy('count', 'desc')
            ->get();

        return [
            'total_audit_trails' => $total,
            'unique_users' => $users,
            'tables_tracked' => $tables,
            'events_by_type' => $events,
            'user_types' => $userTypes
        ];
    }

    /**
     * Clear old audit trails
     *
     * @param string $cutoffDate Cutoff date
     * @return int Number of deleted records
     */
    public function clearOldAuditTrails($cutoffDate)
    {
        return AuditTrail::where('audited_at', '<', $cutoffDate)->delete();
    }
}
