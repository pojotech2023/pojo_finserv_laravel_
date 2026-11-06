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
        if (!Schema::hasColumn('k_y_c_s', 'aadhaar_linked_mobile')) {
            Schema::table('k_y_c_s', function (Blueprint $table) {
                $table->string('aadhaar_linked_mobile', 50)->nullable()->after('phone');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('k_y_c_s', 'aadhaar_linked_mobile')) {
            Schema::table('k_y_c_s', function (Blueprint $table) {
                $table->dropColumn('aadhaar_linked_mobile');
            });
        }
    }
};
