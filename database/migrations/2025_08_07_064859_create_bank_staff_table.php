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
        Schema::create('bank_staff', function (Blueprint $table) {
        $table->id();
        $table->string('staff_name');
        $table->string('bank_name');
        $table->string('employee_id')->nullable();
        $table->string('employee_designation');
        $table->string('aadhaar_number')->nullable();
        $table->text('address')->nullable();
        $table->string('document_path')->nullable();
        $table->decimal('percentage', 5, 2)->default(0);
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_staff');
    }
};
