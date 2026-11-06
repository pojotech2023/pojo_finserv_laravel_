<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
       public function up(): void
    {
        Schema::create('account_expanses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('statement_id');
            $table->string('expanses_remark')->nullable();
            $table->decimal('salary', 15, 2)->nullable();
            $table->decimal('payout', 15, 2)->nullable();
            $table->decimal('commision', 15, 2)->nullable();
            $table->decimal('rent', 15, 2)->nullable();
            $table->decimal('interest', 15, 2)->nullable();
            $table->decimal('exrtn', 15, 2)->nullable();
            $table->string('document')->nullable();
            $table->timestamps();

             $table->foreign('statement_id')->references('id')->on('bankstatement')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expanses');
    }
};
