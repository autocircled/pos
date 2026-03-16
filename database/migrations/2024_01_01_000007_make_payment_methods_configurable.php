<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Change payment_method from enum to string to support custom methods
        DB::statement("ALTER TABLE sales MODIFY payment_method VARCHAR(50) NOT NULL DEFAULT 'cash'");

        // Add default payment methods to settings if settings table exists
        if (Schema::hasTable('settings')) {
            $exists = DB::table('settings')->where('key', 'payment_methods')->exists();
            if (!$exists) {
                $defaultMethods = json_encode([
                    ['code' => 'cash', 'name' => 'Cash'],
                    ['code' => 'card', 'name' => 'Card'],
                    ['code' => 'upi', 'name' => 'UPI'],
                    ['code' => 'mobile_banking', 'name' => 'Mobile Banking'],
                ]);
                DB::table('settings')->insert([
                    'key' => 'payment_methods',
                    'value' => $defaultMethods,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE sales MODIFY payment_method ENUM('cash', 'card', 'upi') NOT NULL DEFAULT 'cash'");

        if (Schema::hasTable('settings')) {
            DB::table('settings')->where('key', 'payment_methods')->delete();
        }
    }
};
