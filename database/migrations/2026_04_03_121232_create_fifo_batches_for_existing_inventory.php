<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Create FIFO batches for existing products with quantity
        $products = DB::table('products')
            ->where('quantity', '>', 0)
            ->get();

        foreach ($products as $product) {
            DB::table('inventory_batches')->insert([
                'product_id' => $product->id,
                'purchase_item_id' => null, // Existing stock, no specific purchase
                'cost_price' => $product->cost_price,
                'selling_price' => $product->selling_price,
                'quantity_initial' => $product->quantity,
                'quantity_remaining' => $product->quantity,
                'batch_date' => now()->toDateString(),
                'notes' => 'Initial stock migration',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        // Remove the migration-created batches
        DB::table('inventory_batches')
            ->where('notes', 'Initial stock migration')
            ->delete();
    }
};
