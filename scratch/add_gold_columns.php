<?php

require dirname(__DIR__) . '/vendor/autoload.php';
$app = require_once dirname(__DIR__) . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

$columnsToAdd = [
    'gold_article' => 'string',
    'gross_weight' => 'string',
    'net_weight'   => 'string',
    'gold_rate'    => 'string',
    'payment_mode' => 'string',
    'utr_ref'      => 'string',
];

foreach ($columnsToAdd as $col => $type) {
    if (!Schema::hasColumn('k_y_c_s', $col)) {
        Schema::table('k_y_c_s', function (Blueprint $table) use ($col, $type) {
            $table->$type($col)->nullable();
        });
        echo "Added {$col} column to k_y_c_s.\n";
    } else {
        echo "{$col} already exists in k_y_c_s.\n";
    }
}

// Create contact_enquiries table if it doesn't exist
if (!Schema::hasTable('contact_enquiries')) {
    Schema::create('contact_enquiries', function (Blueprint $table) {
        $table->id();
        $table->string('name')->nullable();
        $table->string('phone')->nullable();
        $table->string('email')->nullable();
        $table->string('service_required')->nullable();
        $table->text('message')->nullable();
        $table->timestamps();
    });
    echo "Created contact_enquiries table.\n";
} else {
    echo "contact_enquiries table already exists.\n";
}
