<?php

require dirname(__DIR__) . '/vendor/autoload.php';
$app = require_once dirname(__DIR__) . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

if (!Schema::hasColumn('k_y_c_s', 'service_type')) {
    Schema::table('k_y_c_s', function (Blueprint $table) {
        $table->string('service_type')->default('takeover')->nullable();
    });
    echo "Added service_type column successfully.\n";
} else {
    echo "service_type column already exists.\n";
}
