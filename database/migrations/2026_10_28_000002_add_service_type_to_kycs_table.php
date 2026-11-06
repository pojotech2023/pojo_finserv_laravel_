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
        if (Schema::hasTable('k_y_c_s') && !Schema::hasColumn('k_y_c_s', 'service_type')) {
            Schema::table('k_y_c_s', function (Blueprint $table) {
                $table->string('service_type')->nullable()->default('takeover');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('k_y_c_s') && Schema::hasColumn('k_y_c_s', 'service_type')) {
            Schema::table('k_y_c_s', function (Blueprint $table) {
                $table->dropColumn('service_type');
            });
        }
    }
};
