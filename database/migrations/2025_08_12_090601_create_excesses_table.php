<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */ public function up(): void
    {
     Schema::create('excesses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('statement_id'); // links to bank statement
            $table->unsignedBigInteger('user_id')->nullable(); // who saved the record
            $table->string('letter_path')->nullable(); // letter file path
            $table->timestamps();

            // Foreign key relationship (optional)
            $table->foreign('statement_id')
                  ->references('id')
                  ->on('bankstatement')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('excesses');
    }
};
