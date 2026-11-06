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
        Schema::create('investor_interests', function (Blueprint $table) {
            $table->id();
          // Bank statement related
    $table->unsignedBigInteger('statement_id');
    $table->string('utr_no')->nullable();
    $table->string('ref_no')->nullable();
    $table->string('conform_ref_no')->nullable();
    $table->date('date')->nullable();
    $table->decimal('amount', 15, 2)->default(0);
    $table->string('remarks')->nullable();
    $table->string('action_type')->nullable();
    $table->string('old_action_type')->nullable();

    // Investor related
    $table->unsignedBigInteger('investor_id')->nullable();
    $table->string('investor_name')->nullable();
    $table->decimal('percentage', 8, 2)->nullable();
    $table->string('investment_duration')->nullable();
    $table->decimal('interest_amount', 15, 2)->nullable();

    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investor_interests');
    }
};
