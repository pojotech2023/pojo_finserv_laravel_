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
        Schema::table('bankstatement', function (Blueprint $table) {
            $table->string('investment_ref')->nullable()->after('cr_dr');
            $table->string('loan_ref')->nullable()->after('investment_ref');
            $table->string('excess_ref')->nullable()->after('loan_ref');
            $table->string('excess_return_ref')->nullable()->after('excess_ref');
            $table->string('investment_return_ref')->nullable()->after('excess_return_ref');
            $table->string('loan_return_ref')->nullable()->after('investment_return_ref');
            $table->string('expanses_ref')->nullable()->after('loan_return_ref');
            $table->string('expanses_return_ref')->nullable()->after('expanses_ref');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bankstatement', function (Blueprint $table) {
            $table->dropColumn([
                'investment_ref',
                'loan_ref',
                'excess_ref',
                'excess_return_ref',
                'investment_return_ref',
                'loan_return_ref',
                'expanses_ref',
                'expanses_return_ref'
            ]);
        });
    }
};
