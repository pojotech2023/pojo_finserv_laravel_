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
         Schema::create('cheque_uploads', function (Blueprint $table) {
        $table->id();
        $table->foreignId('kyc_id')->constrained('k_y_c_s')->onDelete('cascade');
        $table->string('file_path');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cheque_uploads');
    }
};
