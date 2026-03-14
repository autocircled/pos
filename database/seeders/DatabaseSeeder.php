<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin',
            'email' => 'admin@pos.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Create Categories
        $categories = [
            ['name' => 'Pens & Pencils', 'description' => 'Writing instruments including ballpoint pens, gel pens, and pencils', 'is_active' => true],
            ['name' => 'Notebooks & Papers', 'description' => 'Notebooks, registers, loose sheets, and paper products', 'is_active' => true],
            ['name' => 'Files & Folders', 'description' => 'File folders, binders, and document organizers', 'is_active' => true],
            ['name' => 'Art Supplies', 'description' => 'Colors, brushes, sketch books, and craft materials', 'is_active' => true],
            ['name' => 'Office Supplies', 'description' => 'Staplers, scissors, tape, and general office items', 'is_active' => true],
            ['name' => 'School Bags', 'description' => 'Backpacks, sling bags, and pouches', 'is_active' => true],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        // Create Sample Products
        $products = [
            // Pens & Pencils
            ['category_id' => 1, 'name' => 'Blue Ballpoint Pen', 'sku' => 'STN00001', 'cost_price' => 5, 'selling_price' => 10, 'quantity' => 100, 'unit' => 'piece', 'is_active' => true],
            ['category_id' => 1, 'name' => 'Black Gel Pen', 'sku' => 'STN00002', 'cost_price' => 10, 'selling_price' => 20, 'quantity' => 80, 'unit' => 'piece', 'is_active' => true],
            ['category_id' => 1, 'name' => 'HB Pencil (Pack of 10)', 'sku' => 'STN00003', 'cost_price' => 25, 'selling_price' => 40, 'quantity' => 50, 'unit' => 'pack', 'is_active' => true],
            ['category_id' => 1, 'name' => 'Mechanical Pencil 0.5mm', 'sku' => 'STN00004', 'cost_price' => 30, 'selling_price' => 50, 'quantity' => 40, 'unit' => 'piece', 'is_active' => true],
            ['category_id' => 1, 'name' => 'Highlighter Set (5 colors)', 'sku' => 'STN00005', 'cost_price' => 45, 'selling_price' => 75, 'quantity' => 30, 'unit' => 'set', 'is_active' => true],
            
            // Notebooks & Papers
            ['category_id' => 2, 'name' => 'A4 Notebook 200 Pages', 'sku' => 'STN00006', 'cost_price' => 40, 'selling_price' => 65, 'quantity' => 60, 'unit' => 'piece', 'is_active' => true],
            ['category_id' => 2, 'name' => 'Long Register 400 Pages', 'sku' => 'STN00007', 'cost_price' => 80, 'selling_price' => 120, 'quantity' => 25, 'unit' => 'piece', 'is_active' => true],
            ['category_id' => 2, 'name' => 'A4 Printing Paper (500 sheets)', 'sku' => 'STN00008', 'cost_price' => 200, 'selling_price' => 280, 'quantity' => 20, 'unit' => 'ream', 'is_active' => true],
            ['category_id' => 2, 'name' => 'Spiral Notebook A5', 'sku' => 'STN00009', 'cost_price' => 25, 'selling_price' => 45, 'quantity' => 70, 'unit' => 'piece', 'is_active' => true],
            ['category_id' => 2, 'name' => 'Graph Paper Notebook', 'sku' => 'STN00010', 'cost_price' => 35, 'selling_price' => 55, 'quantity' => 40, 'unit' => 'piece', 'is_active' => true],
            
            // Files & Folders
            ['category_id' => 3, 'name' => 'Box File', 'sku' => 'STN00011', 'cost_price' => 60, 'selling_price' => 90, 'quantity' => 35, 'unit' => 'piece', 'is_active' => true],
            ['category_id' => 3, 'name' => 'Clear Folder A4 (10 pcs)', 'sku' => 'STN00012', 'cost_price' => 50, 'selling_price' => 80, 'quantity' => 45, 'unit' => 'pack', 'is_active' => true],
            ['category_id' => 3, 'name' => 'Ring Binder 2 inch', 'sku' => 'STN00013', 'cost_price' => 80, 'selling_price' => 120, 'quantity' => 20, 'unit' => 'piece', 'is_active' => true],
            
            // Art Supplies
            ['category_id' => 4, 'name' => 'Watercolor Set (12 colors)', 'sku' => 'STN00014', 'cost_price' => 80, 'selling_price' => 130, 'quantity' => 25, 'unit' => 'set', 'is_active' => true],
            ['category_id' => 4, 'name' => 'Sketch Book A4', 'sku' => 'STN00015', 'cost_price' => 50, 'selling_price' => 85, 'quantity' => 35, 'unit' => 'piece', 'is_active' => true],
            ['category_id' => 4, 'name' => 'Color Pencils (24 pcs)', 'sku' => 'STN00016', 'cost_price' => 70, 'selling_price' => 110, 'quantity' => 30, 'unit' => 'box', 'is_active' => true],
            ['category_id' => 4, 'name' => 'Crayons (16 colors)', 'sku' => 'STN00017', 'cost_price' => 25, 'selling_price' => 45, 'quantity' => 50, 'unit' => 'box', 'is_active' => true],
            
            // Office Supplies
            ['category_id' => 5, 'name' => 'Stapler with Pins', 'sku' => 'STN00018', 'cost_price' => 60, 'selling_price' => 95, 'quantity' => 20, 'unit' => 'piece', 'is_active' => true],
            ['category_id' => 5, 'name' => 'Scissors 7 inch', 'sku' => 'STN00019', 'cost_price' => 30, 'selling_price' => 50, 'quantity' => 30, 'unit' => 'piece', 'is_active' => true],
            ['category_id' => 5, 'name' => 'Transparent Tape', 'sku' => 'STN00020', 'cost_price' => 15, 'selling_price' => 25, 'quantity' => 60, 'unit' => 'piece', 'is_active' => true],
            ['category_id' => 5, 'name' => 'Paper Clips (100 pcs)', 'sku' => 'STN00021', 'cost_price' => 10, 'selling_price' => 20, 'quantity' => 80, 'unit' => 'box', 'is_active' => true],
            ['category_id' => 5, 'name' => 'Correction Pen', 'sku' => 'STN00022', 'cost_price' => 20, 'selling_price' => 35, 'quantity' => 45, 'unit' => 'piece', 'is_active' => true],
            
            // School Bags
            ['category_id' => 6, 'name' => 'School Backpack Large', 'sku' => 'STN00023', 'cost_price' => 400, 'selling_price' => 650, 'quantity' => 15, 'unit' => 'piece', 'is_active' => true],
            ['category_id' => 6, 'name' => 'Pencil Pouch', 'sku' => 'STN00024', 'cost_price' => 50, 'selling_price' => 85, 'quantity' => 40, 'unit' => 'piece', 'is_active' => true],
            ['category_id' => 6, 'name' => 'Geometry Box', 'sku' => 'STN00025', 'cost_price' => 80, 'selling_price' => 130, 'quantity' => 35, 'unit' => 'piece', 'is_active' => true],
        ];

        foreach ($products as $product) {
            $product['alert_quantity'] = 10;
            Product::create($product);
        }
    }
}
