<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Part;

class PartsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Part::create([
            'name' => 'iPhone 13 Screen',
            'sku' => 'IPH-13-SCREEN',
            'description' => 'Original OEM screen for iPhone 13',
            'purchase_price' => 2500.00,
            'selling_price' => 3500.00,
            'stock_quantity' => 15,
            'is_active' => true
        ]);

        Part::create([
            'name' => 'iPhone 13 Battery',
            'sku' => 'IPH-13-BATT',
            'description' => 'Original OEM battery for iPhone 13',
            'purchase_price' => 800.00,
            'selling_price' => 1500.00,
            'stock_quantity' => 25,
            'is_active' => true
        ]);

        Part::create([
            'name' => 'Galaxy S22 Screen',
            'sku' => 'GAL-S22-SCREEN',
            'description' => 'Original OEM screen for Samsung Galaxy S22',
            'purchase_price' => 3000.00,
            'selling_price' => 4000.00,
            'stock_quantity' => 10,
            'is_active' => true
        ]);

        Part::create([
            'name' => 'MacBook Pro Keyboard',
            'sku' => 'MAC-KEYBOARD',
            'description' => 'Replacement keyboard for MacBook Pro 14"',
            'purchase_price' => 1800.00,
            'selling_price' => 2800.00,
            'stock_quantity' => 8,
            'is_active' => true
        ]);

        Part::create([
            'name' => 'Dell XPS Charger',
            'sku' => 'DELL-XPS-CHARGER',
            'description' => 'Original charger for Dell XPS 15',
            'purchase_price' => 500.00,
            'selling_price' => 1200.00,
            'stock_quantity' => 20,
            'is_active' => true
        ]);

        Part::create([
            'name' => 'Galaxy Tab S8 Battery',
            'sku' => 'TAB-S8-BATT',
            'description' => 'Original battery for Samsung Galaxy Tab S8',
            'purchase_price' => 1200.00,
            'selling_price' => 2000.00,
            'stock_quantity' => 12,
            'is_active' => true
        ]);
    }
}
