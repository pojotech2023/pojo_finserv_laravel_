<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (file_exists(base_path('vendor/setasign/fpdf/fpdf.php'))) {
            require_once base_path('vendor/setasign/fpdf/fpdf.php');
        }
        if (file_exists(base_path('vendor/setasign/fpdi/src/autoload.php'))) {
            require_once base_path('vendor/setasign/fpdi/src/autoload.php');
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            // Auto-check and create app_configs table if missing
            if (!\Illuminate\Support\Facades\Schema::hasTable('app_configs')) {
                \Illuminate\Support\Facades\Schema::create('app_configs', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->id();
                    $table->string('key')->unique();
                    $table->text('value')->nullable();
                    $table->timestamps();
                });

                \Illuminate\Support\Facades\DB::table('app_configs')->insertOrIgnore([
                    ['key' => 'api_mode', 'value' => 'test', 'created_at' => now(), 'updated_at' => now()],
                    ['key' => 'aadhaar_api_hits', 'value' => '0', 'created_at' => now(), 'updated_at' => now()],
                    ['key' => 'pan_api_hits', 'value' => '0', 'created_at' => now(), 'updated_at' => now()],
                ]);
            }

            // Auto-check and create contact_enquiries table if missing
            if (!\Illuminate\Support\Facades\Schema::hasTable('contact_enquiries')) {
                \Illuminate\Support\Facades\Schema::create('contact_enquiries', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->id();
                    $table->string('name');
                    $table->string('phone');
                    $table->string('email')->nullable();
                    $table->string('service_required')->nullable();
                    $table->text('message')->nullable();
                    $table->timestamps();
                });
            }

            // Auto-check and add service_type column to k_y_c_s if missing
            if (\Illuminate\Support\Facades\Schema::hasTable('k_y_c_s') && !\Illuminate\Support\Facades\Schema::hasColumn('k_y_c_s', 'service_type')) {
                \Illuminate\Support\Facades\Schema::table('k_y_c_s', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->string('service_type')->nullable()->default('takeover');
                });
            }

            // Ensure default admin user exists if cr_users table is empty
            if (\Illuminate\Support\Facades\Schema::hasTable('cr_users')) {
                if (\App\Models\CrUser::count() === 0) {
                    \App\Models\CrUser::create([
                        'name' => 'Admin',
                        'email' => 'admin@demo.com',
                        'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
                        'role' => 'admin',
                        'status' => 'active',
                    ]);
                }
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('AppServiceProvider boot setup notice: ' . $e->getMessage());
        }
    }
}
