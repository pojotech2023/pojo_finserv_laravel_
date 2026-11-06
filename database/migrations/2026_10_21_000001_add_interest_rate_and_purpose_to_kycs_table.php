<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('k_y_c_s', function (Blueprint $table) {
            if (!Schema::hasColumn('k_y_c_s', 'interest_rate')) {
                $table->decimal('interest_rate', 5, 2)->nullable()->default(0.10);
            }
            if (!Schema::hasColumn('k_y_c_s', 'purpose')) {
                $table->string('purpose')->nullable()->default('Transfer');
            }
        });
    }

    public function down()
    {
        Schema::table('k_y_c_s', function (Blueprint $table) {
            $table->dropColumn(['interest_rate', 'purpose']);
        });
    }
};
