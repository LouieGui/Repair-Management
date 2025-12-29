<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ReturnModel;

class ReturnsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Return for Repair 3 (Robert Johnson's MacBook Pro - warranty claim)
        ReturnModel::create([
            'repair_id' => 3,
            'new_repair_id' => null,
            'return_type' => 'warranty_claim',
            'return_date' => now()->subDays(1),
            'return_reason' => 'Keyboard issue returned after 2 days - some keys still not working properly',
            'is_same_issue' => true,
            'is_under_warranty' => true,
            'received_by' => 4, // Receptionist
            'notes' => 'Customer claims the repair was not done properly, keyboard still has issues',
            'is_active' => true
        ]);
    }
}
