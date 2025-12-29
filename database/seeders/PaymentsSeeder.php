<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Payment;

class PaymentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Payment for Repair 3 (Robert Johnson's MacBook Pro - completed)
        Payment::create([
            'repair_id' => 3,
            'amount' => 3500.00,
            'payment_method' => 'card',
            'status' => 'completed',
            'transaction_id' => 'TRX-2023-001',
            'notes' => 'Full payment for MacBook Pro keyboard replacement',
            'is_active' => true
        ]);

        // Partial payment for Repair 2 (Jane Smith's Galaxy S22)
        Payment::create([
            'repair_id' => 2,
            'amount' => 1000.00,
            'payment_method' => 'cash',
            'status' => 'completed',
            'transaction_id' => 'TRX-2023-002',
            'notes' => 'Partial payment for Galaxy S22 repair',
            'is_active' => true
        ]);
    }
}
