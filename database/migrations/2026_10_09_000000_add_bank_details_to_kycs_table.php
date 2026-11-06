<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('k_y_c_s', function (Blueprint $table) {
            if (!Schema::hasColumn('k_y_c_s', 'bank_acc')) {
                $table->string('bank_acc')->nullable();
            }
            if (!Schema::hasColumn('k_y_c_s', 'bank_name_ifsc')) {
                $table->string('bank_name_ifsc')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('k_y_c_s', function (Blueprint $table) {
            $table->dropColumn(['bank_acc', 'bank_name_ifsc']);
        });
    }
};
