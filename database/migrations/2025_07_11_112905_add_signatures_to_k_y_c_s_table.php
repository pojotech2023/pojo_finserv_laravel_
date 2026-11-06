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
             $table->string('borrower_signature')->nullable();
    $table->string('witness_signature')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('k_y_c_s', function (Blueprint $table) {
            //
        });
    }
};
