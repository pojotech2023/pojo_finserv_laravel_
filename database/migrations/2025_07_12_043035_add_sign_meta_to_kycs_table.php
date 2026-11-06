<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
{
    Schema::table('k_y_c_s', function (Blueprint $table) {
        $table->timestamp('signed_at')->nullable();
        $table->string('device_id', 255)->nullable();
        $table->string('ip_address', 45)->nullable();
    });
}

public function down()
{
    Schema::table('k_y_c_s', function (Blueprint $table) {
        $table->dropColumn(['signed_at', 'device_id', 'ip_address']);
    });
}
};
