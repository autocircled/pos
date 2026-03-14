<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        DB::table('settings')->insert([
            ['key' => 'currency_symbol', 'value' => '৳', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'currency_code', 'value' => 'BDT', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'shop_name', 'value' => 'Stationery POS', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'shop_address', 'value' => '', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'shop_phone', 'value' => '', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'tax_percentage', 'value' => '0', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
