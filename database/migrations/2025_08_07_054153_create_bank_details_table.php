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
         Schema::create('bank_details', function (Blueprint $table) {
        $table->id();
        $table->string('bank_name')->nullable();
        $table->string('journal')->nullable();
        $table->string('cluster')->nullable();
        $table->string('region')->nullable();
        $table->string('area')->nullable();
        $table->string('state')->nullable();
        $table->string('referred_by')->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_details');
    }
};
