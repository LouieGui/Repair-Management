<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            UsersSeeder::class,
            CustomersSeeder::class,
            DevicesSeeder::class,
            PartsSeeder::class,
            RepairsSeeder::class,
            RepairPartsSeeder::class,
            PaymentsSeeder::class,
            ReturnsSeeder::class,
            AuditTrailsSeeder::class,
        ]);
    }
}
