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
       Schema::create('investors', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('investment_duration');
        $table->decimal('percentage', 5, 2)->nullable();
        $table->string('payout_time')->nullable();
        $table->string('account_number');
        $table->string('ifsc_code');
        $table->string('branch_name')->nullable();
        $table->text('address')->nullable();
        $table->string('gst_number')->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investors');
    }
};
