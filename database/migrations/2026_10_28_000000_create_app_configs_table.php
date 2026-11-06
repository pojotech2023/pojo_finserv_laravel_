<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('app_configs')) {
            Schema::create('app_configs', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->text('value')->nullable();
                $table->timestamps();
            });

            // Initialize default values
            DB::table('app_configs')->insert([
                ['key' => 'api_mode', 'value' => 'test', 'created_at' => now(), 'updated_at' => now()],
                ['key' => 'aadhaar_api_hits', 'value' => '0', 'created_at' => now(), 'updated_at' => now()],
                ['key' => 'pan_api_hits', 'value' => '0', 'created_at' => now(), 'updated_at' => now()],
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('app_configs');
    }
};
