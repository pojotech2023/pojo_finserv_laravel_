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
        Schema::table('k_y_c_s', function (Blueprint $table) {
            if (!Schema::hasColumn('k_y_c_s', 'verified_aadhaar_no')) {
                $table->string('verified_aadhaar_no')->nullable();
            }
            if (!Schema::hasColumn('k_y_c_s', 'verified_addhar_no')) {
                $table->string('verified_addhar_no')->nullable();
            }
            if (!Schema::hasColumn('k_y_c_s', 'verified_name')) {
                $table->string('verified_name')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('k_y_c_s', function (Blueprint $table) {
            $table->dropColumn(['verified_aadhaar_no', 'verified_addhar_no', 'verified_name']);
        });
    }
};
