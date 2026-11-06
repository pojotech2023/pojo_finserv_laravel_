<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$kyc = new \App\Models\KYC();
$kyc->id = 1;
$kyc->full_name = 'KARTHIKEYAN M';
$kyc->care_of = 'MURUGESAN';
$kyc->phone = '9876543210';
$kyc->pan_number = 'ABCDE1234F';
$kyc->aadhaar_number = '123456789012';
$kyc->amount = 168096;
$kyc->interest_rate = 0.50;
$kyc->bank_acc = '1234567890';
$kyc->ifsc_code = 'SBIN0001234';
$kyc->bank_name_ifsc = 'KARTHIKEYAN M';
$kyc->referel_name = 'GOWTHAMAN';
$kyc->address = '123 Main St, Coimbatore, Tamil Nadu';

$pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.promissory', ['kyc' => $kyc])
    ->setPaper('a4')
    ->setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif', 'isRemoteEnabled' => true]);
$out = $pdf->output();

$pageCount = preg_match_all('/\/Page\W/', $out);
echo "Promissory PDF with no uploads (only Agreement + Audit page) total pages: {$pageCount}\n";
