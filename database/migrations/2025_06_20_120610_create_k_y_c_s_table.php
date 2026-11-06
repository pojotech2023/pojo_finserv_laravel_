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
        Schema::create('k_y_c_s', function (Blueprint $table) {
                       $table->id();

            // Step 1 - OTP generation
            $table->string('aadhaar_number');
            $table->string('request_id')->nullable();

            // Step 2 - OTP verification Aadhaar data
            $table->string('full_name')->nullable();
            $table->date('dob')->nullable();
            $table->string('gender', 10)->nullable();
            $table->string('care_of')->nullable();
            $table->string('zip')->nullable();
            $table->text('address')->nullable(); // JSON-encoded address
            $table->text('profile_image')->nullable();
            $table->longText('raw_xml')->nullable();
            $table->longText('zip_data')->nullable();
            $table->string('aadhaar_pdf')->nullable();
            $table->boolean('face_status')->default(false);
            $table->float('face_score')->default(-1);
            $table->string('status')->nullable(); // status like "success_aadhaar"

            // Step 3 - PAN Upload
            $table->string('pan_card_image')->nullable(); // Store uploaded PAN image path

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('k_y_c_s');
    }
};
