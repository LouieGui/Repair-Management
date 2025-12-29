<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RepairPart;

class RepairPartsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Parts for Repair 1 (John Doe's iPhone 13)
        RepairPart::create([
            'repair_id' => 1,
            'part_id' => 1,
            'custom_part_name' => null,
            'quantity' => 1,
            'unit_price' => 3500.00,
            'total_price' => 3500.00,
            'is_approved' => false,
            'notes' => 'Original OEM screen replacement',
            'is_active' => true
        ]);

        RepairPart::create([
            'repair_id' => 1,
            'part_id' => 2,
            'custom_part_name' => null,
            'quantity' => 1,
            'unit_price' => 1500.00,
            'total_price' => 1500.00,
            'is_approved' => false,
            'notes' => 'Original OEM battery replacement',
            'is_active' => true
        ]);

        // Parts for Repair 2 (Jane Smith's Galaxy S22)
        RepairPart::create([
            'repair_id' => 2,
            'part_id' => 3,
            'custom_part_name' => null,
            'quantity' => 1,
            'unit_price' => 4000.00,
            'total_price' => 4000.00,
            'is_approved' => true,
            'notes' => 'Screen replacement due to charging port issue',
            'is_active' => true
        ]);

        // Parts for Repair 3 (Robert Johnson's MacBook Pro)
        RepairPart::create([
            'repair_id' => 3,
            'part_id' => 4,
            'custom_part_name' => null,
            'quantity' => 1,
            'unit_price' => 2800.00,
            'total_price' => 2800.00,
            'is_approved' => true,
            'notes' => 'Keyboard replacement due to liquid damage',
            'is_active' => true
        ]);
    }
}
