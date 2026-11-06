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
       Schema::create('bankstatement', function (Blueprint $table) {
        $table->id();
        $table->date('date');
        $table->string('utrno')->nullable();
        $table->text('remarks')->nullable();
        $table->string('name')->nullable();
        $table->decimal('amount', 15, 2);
        $table->enum('cr_dr', ['CR', 'DR']);
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bankstatement');
    }
};
