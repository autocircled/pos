<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('settings')) {
            $exists = DB::table('settings')->where('key', 'timezone')->exists();
            if (!$exists) {
                DB::table('settings')->insert([
                    'key' => 'timezone',
                    'value' => 'Asia/Dhaka',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('settings')) {
            DB::table('settings')->where('key', 'timezone')->delete();
        }
    }
};
