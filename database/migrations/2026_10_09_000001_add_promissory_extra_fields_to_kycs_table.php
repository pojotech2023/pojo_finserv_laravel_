<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('k_y_c_s', function (Blueprint $table) {
            if (!Schema::hasColumn('k_y_c_s', 'ifsc_code')) {
                $table->string('ifsc_code')->nullable();
            }
            if (!Schema::hasColumn('k_y_c_s', 'existing_bank')) {
                $table->string('existing_bank')->nullable();
            }
            if (!Schema::hasColumn('k_y_c_s', 'existing_loan_acc')) {
                $table->string('existing_loan_acc')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('k_y_c_s', function (Blueprint $table) {
            $table->dropColumn(['ifsc_code', 'existing_bank', 'existing_loan_acc']);
        });
    }
};
