<?php

require dirname(__DIR__) . '/vendor/autoload.php';
$app = require_once dirname(__DIR__) . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\KYC;
use Barryvdh\DomPDF\Facade\Pdf;

try {
    $kyc = KYC::latest()->first();
    if (!$kyc) {
        echo "No KYC record found.\n";
        exit;
    }
    echo "Testing PDF generation for KYC ID: {$kyc->id}, service_type: {$kyc->service_type}\n";

    $pdfView = ($kyc->service_type === 'sell_gold') ? 'pdf.gold_buying' : 'pdf.promissory';
    $pdf = Pdf::loadView($pdfView, ['kyc' => $kyc])
        ->setPaper('a4')
        ->setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif', 'isRemoteEnabled' => true]);

    $pdfContent = $pdf->output();
    echo "PDF generated successfully! Bytes size: " . strlen($pdfContent) . "\n";
} catch (\Throwable $e) {
    echo "PDF Generation ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
