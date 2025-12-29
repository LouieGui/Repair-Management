<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AuditTrail;

class AuditTrailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Audit trail for user creation (admin)
        AuditTrail::create([
            'user_id' => 1,
            'user_type' => 'admin',
            'audit_table' => 'users',
            'event' => 'created',
            'old_values' => null,
            'new_values' => json_encode(['username' => 'admin', 'email' => 'admin@repairmanagement.com', 'role' => 'admin']),
            'audited_at' => now()
        ]);

        // Audit trail for customer creation
        AuditTrail::create([
            'user_id' => 1,
            'user_type' => 'admin',
            'audit_table' => 'customers',
            'event' => 'created',
            'old_values' => null,
            'new_values' => json_encode(['fullname' => 'John Doe', 'contact' => '09123456789']),
            'audited_at' => now()->subMinutes(30)
        ]);

        // Audit trail for device creation
        AuditTrail::create([
            'user_id' => 4,
            'user_type' => 'receptionist',
            'audit_table' => 'devices',
            'event' => 'created',
            'old_values' => null,
            'new_values' => json_encode(['brand' => 'Apple', 'model' => 'iPhone 13', 'customer_id' => 1]),
            'audited_at' => now()->subMinutes(20)
        ]);

        // Audit trail for repair creation
        AuditTrail::create([
            'user_id' => 4,
            'user_type' => 'receptionist',
            'audit_table' => 'repairs',
            'event' => 'created',
            'old_values' => null,
            'new_values' => json_encode(['status' => 'received', 'customer_id' => 1, 'device_id' => 1]),
            'audited_at' => now()->subMinutes(15)
        ]);

        // Audit trail for repair status change
        AuditTrail::create([
            'user_id' => 2,
            'user_type' => 'technician',
            'audit_table' => 'repairs',
            'event' => 'status_changed',
            'old_values' => json_encode(['status' => 'received']),
            'new_values' => json_encode(['status' => 'diagnosing']),
            'audited_at' => now()->subMinutes(10)
        ]);

        // Audit trail for payment creation
        AuditTrail::create([
            'user_id' => 4,
            'user_type' => 'receptionist',
            'audit_table' => 'payments',
            'event' => 'created',
            'old_values' => null,
            'new_values' => json_encode(['repair_id' => 3, 'amount' => 3500.00, 'status' => 'completed']),
            'audited_at' => now()->subMinutes(5)
        ]);

        // Audit trail for return creation
        AuditTrail::create([
            'user_id' => 4,
            'user_type' => 'receptionist',
            'audit_table' => 'returns',
            'event' => 'created',
            'old_values' => null,
            'new_values' => json_encode(['repair_id' => 3, 'return_type' => 'warranty_claim', 'is_under_warranty' => true]),
            'audited_at' => now()
        ]);
    }
}
