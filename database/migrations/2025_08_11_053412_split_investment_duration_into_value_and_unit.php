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
            $table->integer('investment_duration_value')->nullable()->after('investment_duration');
            $table->enum('investment_duration_unit', ['day', 'week', 'month', 'year'])->nullable()->after('investment_duration_value');
        });

        // Migrate old data
        $investors = DB::table('investors')->get();
        foreach ($investors as $inv) {
            if (!empty($inv->investment_duration)) {
                $parts = explode(' ', trim($inv->investment_duration));
                DB::table('investors')->where('id', $inv->id)->update([
                    'investment_duration_value' => intval($parts[0] ?? 0),
                    'investment_duration_unit' => strtolower($parts[1] ?? 'month'),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('investors', function (Blueprint $table) {
            $table->dropColumn(['investment_duration_value', 'investment_duration_unit']);
        });
    }
};
