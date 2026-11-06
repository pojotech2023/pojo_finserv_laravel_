<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$kyc = \App\Models\KYC::first();

// Test render promissory
$pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.promissory', ['kyc' => $kyc])
    ->setPaper('a4')
    ->setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif', 'isRemoteEnabled' => true]);
$out = $pdf->output();

// Check how many pages before first attachment
// Let's create a template without attachments and test
