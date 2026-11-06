<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('k_y_c_s', function (Blueprint $table) {
            if (!Schema::hasColumn('k_y_c_s', 'gst_percent')) {
                $table->decimal('gst_percent', 5, 2)->nullable()->default(3.00);
            }
            if (!Schema::hasColumn('k_y_c_s', 'gst_amount')) {
                $table->decimal('gst_amount', 12, 2)->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('k_y_c_s', function (Blueprint $table) {
            $table->dropColumn(['gst_percent', 'gst_amount']);
        });
    }
};
