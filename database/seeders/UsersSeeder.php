<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin user
        User::create([
            'username' => 'admin',
            'email' => 'admin@repairmanagement.com',
            'phone' => '09123456789',
            'role' => 'admin',
            'password' => Hash::make('admin123'),
            'is_active' => true
        ]);

        // Technicians
        User::create([
            'username' => 'tech1',
            'email' => 'tech1@repairmanagement.com',
            'phone' => '09123456788',
            'role' => 'technician',
            'password' => Hash::make('tech123'),
            'is_active' => true
        ]);

        User::create([
            'username' => 'tech2',
            'email' => 'tech2@repairmanagement.com',
            'phone' => '09123456787',
            'role' => 'technician',
            'password' => Hash::make('tech123'),
            'is_active' => true
        ]);

        // Receptionist
        User::create([
            'username' => 'reception',
            'email' => 'reception@repairmanagement.com',
            'phone' => '09123456786',
            'role' => 'receptionist',
            'password' => Hash::make('reception123'),
            'is_active' => true
        ]);

        // Sample customers
        User::create([
            'username' => 'customer1',
            'email' => 'customer1@example.com',
            'phone' => '09123456785',
            'role' => 'customer',
            'password' => Hash::make('customer123'),
            'is_active' => true
        ]);

        User::create([
            'username' => 'customer2',
            'email' => 'customer2@example.com',
            'phone' => '09123456784',
            'role' => 'customer',
            'password' => Hash::make('customer123'),
            'is_active' => true
        ]);
    }
}
