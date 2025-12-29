<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Repair;

class RepairsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Repair for John Doe's iPhone 13 (received status)
        Repair::create([
            'customer_id' => 1,
            'device_id' => 1,
            'technician_id' => 2,
            'reported_issue' => 'Cracked screen and battery draining quickly',
            'accessories' => 'Charger, case',
            'status' => 'received',
            'diagnosis' => null,
            'technician_notes' => null,
            'date_received' => now(),
            'estimated_completion_date' => now()->addDays(3),
            'date_completed' => null,
            'warranty_days' => 90,
            'warranty_until_date' => null,
            'total_amount' => 0,
            'paid_amount' => 0,
            'is_active' => true
        ]);

        // Repair for Jane Smith's Galaxy S22 (in progress)
        Repair::create([
            'customer_id' => 2,
            'device_id' => 2,
            'technician_id' => 2,
            'reported_issue' => 'Phone not charging properly',
            'accessories' => 'Charger, earphones',
            'status' => 'in_progress',
            'diagnosis' => 'Faulty charging port needs replacement',
            'technician_notes' => 'Ordered new charging port, waiting for delivery',
            'date_received' => now()->subDays(2),
            'estimated_completion_date' => now()->addDays(1),
            'date_completed' => null,
            'warranty_days' => 90,
            'warranty_until_date' => null,
            'total_amount' => 1800.00,
            'paid_amount' => 0,
            'is_active' => true
        ]);

        // Repair for Robert Johnson's MacBook Pro (completed)
        Repair::create([
            'customer_id' => 3,
            'device_id' => 3,
            'technician_id' => 3,
            'reported_issue' => 'Keyboard not working, some keys stuck',
            'accessories' => 'Charger',
            'status' => 'completed',
            'diagnosis' => 'Liquid damage to keyboard, needs replacement',
            'technician_notes' => 'Replaced keyboard and cleaned internal components',
            'date_received' => now()->subDays(10),
            'estimated_completion_date' => now()->subDays(5),
            'date_completed' => now()->subDays(3),
            'warranty_days' => 90,
            'warranty_until_date' => now()->addDays(87),
            'total_amount' => 3500.00,
            'paid_amount' => 3500.00,
            'is_active' => true
        ]);
    }
}
