<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mfs_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mfs_account_id')->constrained('mfs_accounts')->onDelete('cascade');
            $table->enum('transaction_type', ['cash_in', 'cash_out']);
            $table->decimal('amount', 15, 2);
            $table->decimal('commission_rate', 5, 2)->default(0.4);
            $table->decimal('commission_earned', 15, 2)->default(0);
            $table->string('transaction_id', 100)->nullable();
            $table->string('customer_phone', 20)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mfs_transactions');
    }
};