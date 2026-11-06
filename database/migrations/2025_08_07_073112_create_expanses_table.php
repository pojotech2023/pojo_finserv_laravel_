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
         Schema::create('expanses', function (Blueprint $table) {
        $table->id();
        $table->text('remarks')->nullable();
        $table->string('upload_bills')->nullable();
        $table->string('gst_number')->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expanses');
    }
};
