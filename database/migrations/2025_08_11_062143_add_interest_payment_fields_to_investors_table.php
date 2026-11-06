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
    Schema::table('investors', function (Blueprint $table) {
        $table->decimal('received_commission', 10, 2)->default(0)->after('percentage');
        $table->decimal('interest_balance', 10, 2)->default(0)->after('received_commission');
    });
}

public function down(): void
{
    Schema::table('investors', function (Blueprint $table) {
        $table->dropColumn(['received_commission', 'interest_balance']);
    });
}
};
