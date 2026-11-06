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
        Schema::create('application_forms', function (Blueprint $table) {
            $table->id();
            $table->string('name'); 
            $table->string('email')->unique();
            $table->string('mobile')->nullable(); 
            $table->string('parent_mobile')->nullable(); 
            $table->string('address')->nullable(); 
            $table->date('joining_date')->nullable(); 
            $table->string('advance')->nullable(); 
            $table->string('rent')->nullable(); 
            $table->string('aadhar_card_path')->nullable(); 
            $table->string('company_id_card_path')->nullable();
            $table->string('signature_path')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_forms');
    }
};
