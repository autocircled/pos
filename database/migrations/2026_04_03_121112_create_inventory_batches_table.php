<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inventory_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('purchase_item_id')->nullable()->constrained()->onDelete('cascade');
            $table->decimal('cost_price', 10, 2);
            $table->decimal('selling_price', 10, 2)->nullable(); // For future price tracking
            $table->integer('quantity_initial');
            $table->integer('quantity_remaining');
            $table->date('batch_date');
            $table->text('notes')->nullable();
            $table->timestamps();

            // Indexes for FIFO operations
            $table->index(['product_id', 'batch_date']); // For FIFO selection
            $table->index(['product_id', 'quantity_remaining']); // For available stock
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_batches');
    }
};
