<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mfs_accounts', function (Blueprint $table) {
            $table->decimal('cash_in_rate', 5, 3)->default(0.375)->after('is_active');
            $table->decimal('cash_out_rate', 5, 3)->default(0.4)->after('cash_in_rate');
        });
    }

    public function down(): void
    {
        Schema::table('mfs_accounts', function (Blueprint $table) {
            $table->dropColumn(['cash_in_rate', 'cash_out_rate']);
        });
    }
};