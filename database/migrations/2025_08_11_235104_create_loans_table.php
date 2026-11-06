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
        Schema::create('loans', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('statement_id')->unique(); // assuming one loan per statement
        $table->string('bank_name');
        $table->string('staff_name');
        $table->string('customer_name');
        $table->decimal('percentage', 5, 2);
        $table->decimal('balance', 15, 2);
        $table->decimal('interest_amount', 15, 2)->nullable();
        $table->timestamps();

        $table->foreign('statement_id')->references('id')->on('bankstatement')->onDelete('cascade');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
