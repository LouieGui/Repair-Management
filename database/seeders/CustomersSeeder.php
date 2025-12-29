<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::create([
            'fullname' => 'John Doe',
            'unique_id' => 'CUST-001',
            'contact' => '09123456789',
            'email' => 'john.doe@example.com',
            'address' => '123 Main Street, Manila, Philippines',
            'is_active' => true
        ]);

        Customer::create([
            'fullname' => 'Jane Smith',
            'unique_id' => 'CUST-002',
            'contact' => '09123456788',
            'email' => 'jane.smith@example.com',
            'address' => '456 Oak Avenue, Cebu City, Philippines',
            'is_active' => true
        ]);

        Customer::create([
            'fullname' => 'Robert Johnson',
            'unique_id' => 'CUST-003',
            'contact' => '09123456787',
            'email' => 'robert.j@example.com',
            'address' => '789 Pine Road, Davao City, Philippines',
            'is_active' => true
        ]);

        Customer::create([
            'fullname' => 'Maria Garcia',
            'unique_id' => 'CUST-004',
            'contact' => '09123456786',
            'email' => 'maria.g@example.com',
            'address' => '321 Elm Street, Quezon City, Philippines',
            'is_active' => true
        ]);

        Customer::create([
            'fullname' => 'Michael Brown',
            'unique_id' => 'CUST-005',
            'contact' => '09123456785',
            'email' => 'michael.b@example.com',
            'address' => '654 Maple Lane, Makati City, Philippines',
            'is_active' => true
        ]);
    }
}
