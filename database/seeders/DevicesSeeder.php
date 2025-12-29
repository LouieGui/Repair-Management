<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Device;

class DevicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Devices for John Doe (Customer 1)
        Device::create([
            'customer_id' => 1,
            'brand' => 'Apple',
            'model' => 'iPhone 13',
            'serial_number' => 'F2LQX1234567',
            'imei' => '358912091234567',
            'device_warranty_status' => 'expired',
            'device_password' => '1234',
            'is_active' => true
        ]);

        // Devices for Jane Smith (Customer 2)
        Device::create([
            'customer_id' => 2,
            'brand' => 'Samsung',
            'model' => 'Galaxy S22',
            'serial_number' => 'R58N98765432',
            'imei' => '357890123456789',
            'device_warranty_status' => 'active',
            'device_password' => '4321',
            'is_active' => true
        ]);

        // Devices for Robert Johnson (Customer 3)
        Device::create([
            'customer_id' => 3,
            'brand' => 'Apple',
            'model' => 'MacBook Pro 14"',
            'serial_number' => 'C02ZQ0ABCDEFG',
            'imei' => null,
            'device_warranty_status' => 'active',
            'device_password' => 'password123',
            'is_active' => true
        ]);

        // Devices for Maria Garcia (Customer 4)
        Device::create([
            'customer_id' => 4,
            'brand' => 'Dell',
            'model' => 'XPS 15',
            'serial_number' => 'CN-1234567890',
            'imei' => null,
            'device_warranty_status' => 'none',
            'device_password' => 'dell123',
            'is_active' => true
        ]);

        // Devices for Michael Brown (Customer 5)
        Device::create([
            'customer_id' => 5,
            'brand' => 'Samsung',
            'model' => 'Galaxy Tab S8',
            'serial_number' => 'R5AT98765432',
            'imei' => '359012345678901',
            'device_warranty_status' => 'expired',
            'device_password' => 'tab123',
            'is_active' => true
        ]);
    }
}
