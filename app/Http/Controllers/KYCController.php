<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\KYC;
use App\Models\ChequeUpload;
use App\Models\loanUpload;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage; // ✅ correct location
use Illuminate\Support\Facades\Auth;
use NumberToWords\NumberToWords;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use App\Exports\KycExport;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Google_Client;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;
use Illuminate\Support\Facades\Log;
use App\Models\AppConfig;




class KYCController extends Controller
{
    public function aadhaarForm(Request $request)
    {
        $service = $request->query('service', 'takeover');
        if (!in_array($service, ['takeover', 'sell_gold'])) {
            $service = 'takeover';
        }
        session(['service_type' => $service]);
        return view('aadhaar', compact('service'));
    }

public function sendOtp(Request $request)
{
    // Step 1: Validate all fields as optional
    $request->validate([
        'aadhaar_number' => 'nullable|digits:12',
        'aadhar_front' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
        'aadhar_back' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
    ]);

    $aadhaarNumber = $request->aadhaar_number;
    $hasFront = $request->hasFile('aadhar_front');
    $hasBack = $request->hasFile('aadhar_back');

    // Step 2: If nothing is provided, return error
    if (!$aadhaarNumber && !$hasFront && !$hasBack) {
        return back()->withErrors(['msg' => 'Please verify Aadhaar number or upload Aadhaar images.']);
    }

    // Step 3: If Aadhaar number is given, send OTP
    $requestId = null;
    if ($aadhaarNumber) {
        if (!AppConfig::isLiveMode()) {
            // TESTING MODE: Do NOT call QuickeKYC API
            $requestId = 'TEST_REQ_' . strtoupper(Str::random(12));
            \Illuminate\Support\Facades\Log::info("[TEST MODE] Aadhaar OTP simulated for {$aadhaarNumber}. Request ID: {$requestId}. Sample OTP: 123456");
        } else {
            // LIVE MODE: Increment hit counter and call QuickeKYC API
            AppConfig::incrementHit('aadhaar_api_hits');
            try {
                $response = Http::withoutVerifying()->timeout(20)->post('https://quickekyc.com/api/v1/aadhaar-v2/generate-otp', [
                    'key' => '52edbbf2-a1b2-46aa-aae8-c05d9ba5eaab',
                    'id_number' => $aadhaarNumber,
                ]);

                $result = $response->json();
                \Illuminate\Support\Facades\Log::info('QuickeKYC Generate OTP Response: ' . json_encode($result));

                if (!isset($result['status']) || $result['status'] !== 'success' || !isset($result['request_id'])) {
                    $errMsg = $result['message'] ?? ($result['msg'] ?? 'OTP generation failed. Please verify Aadhaar number and try again.');
                    return back()->withErrors(['msg' => $errMsg]);
                }

                $requestId = $result['request_id'];
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Aadhaar Send OTP Exception: ' . $e->getMessage());
                return back()->withErrors(['msg' => 'Could not connect to Aadhaar service. ' . $e->getMessage()]);
            }
        }
    }

    $serviceType = session('service_type', $request->input('service_type', 'takeover'));

    // Step 4: Save KYC record
    $kyc = KYC::create([
        'aadhaar_number' => $aadhaarNumber,
        'request_id'     => $requestId,
        'service_type'   => $serviceType,
    ]);

    // Step 5: Save front image if present
    if ($hasFront) {
        $frontFile = $request->file('aadhar_front');
        $frontFilename = 'aadhar_front_' . $kyc->id . '.' . $frontFile->getClientOriginalExtension();
        $frontPath = $frontFile->storeAs('aadhar', $frontFilename, 'public');
        $kyc->aadhar_front = $frontPath;
    }

    // Step 6: Save back image if present
    if ($hasBack) {
        $backFile = $request->file('aadhar_back');
        $backFilename = 'aadhar_back_' . $kyc->id . '.' . $backFile->getClientOriginalExtension();
        $backPath = $backFile->storeAs('aadhar', $backFilename, 'public');
        $kyc->aadhar_back = $backPath;
    }

    $kyc->save();

    // Step 7: Redirect
    if ($requestId) {
        return redirect()->route('aadhaar.otp.form', ['request_id' => $requestId])
                         ->with('success', 'OTP sent successfully. Please enter it below.');
    }

    return redirect()->route('pan.upload.form', ['kyc_id' => $kyc->id])->with('success', 'Aadhaar details uploaded successfully.');
}




   public function showOtpForm($request_id)
{
    return view('otp', ['request_id' => $request_id]);
}

public function verifyOtp(Request $request, $request_id)
{
    $request->validate([
        'otp' => 'required|digits:6',
    ]);

    // Check if in TEST MODE (QuickeKYC API is bypassed)
    if (!AppConfig::isLiveMode()) {
        if ($request->otp !== '123456') {
            return back()->withErrors(['otp' => 'Invalid OTP. Testing mode active: Please enter sample OTP "123456".']);
        }

        $kyc = KYC::where('request_id', $request_id)->firstOrFail();
        $testName = 'Sample Customer';
        $verifiedAadhaar = $kyc->aadhaar_number ?? '123456789012';

        $data = [
            'full_name' => $testName,
            'gender' => 'M',
            'dob' => '1995-05-15',
            'care_of' => 'Test Father',
            'zip' => '638012',
            'mobile' => '9994777004',
            'address' => [
                'house' => '02/628',
                'street' => 'Mahil Nagar, Athappampalayam',
                'vtc' => 'Melthindal',
                'dist' => 'Erode',
                'state' => 'Tamil Nadu',
                'country' => 'India',
                'zip' => '638012'
            ],
            'status' => 'verified',
        ];

        $kyc->update([
            'full_name'             => $testName,
            'verified_name'         => $testName,
            'verified_aadhaar_no'   => $verifiedAadhaar,
            'verified_addhar_no'    => $verifiedAadhaar,
            'aadhaar_linked_mobile' => '9994777004',
            'dob'                   => '1995-05-15',
            'gender'                => 'M',
            'care_of'               => 'Test Father',
            'zip'                   => '638012',
            'address'               => json_encode($data['address']),
            'status'                => 'verified',
        ]);

        if ($request->wantsJson() || $request->ajax() || $request->is('api/*')) {
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'success' => true,
                'message' => '[TEST MODE] Aadhaar verified successfully with sample OTP (123456).',
                'data' => $data,
            ]);
        }

        return redirect()->route('pan.upload.form', ['kyc_id' => $kyc->id])
                         ->with('success', '[TEST MODE] Aadhaar verified successfully with sample OTP: 123456. Please upload PAN card.');
    }

    // LIVE MODE: Call QuickeKYC API
    try {
        $response = Http::withoutVerifying()->timeout(20)->post('https://quickekyc.com/api/v1/aadhaar-v2/submit-otp', [
            'key' => '52edbbf2-a1b2-46aa-aae8-c05d9ba5eaab',
            'request_id' => $request_id,
            'otp' => $request->otp,
        ]);

        $result = $response->json();
        \Illuminate\Support\Facades\Log::info('QuickeKYC Verify OTP Response: ' . json_encode($result));
    } catch (\Throwable $e) {
        \Illuminate\Support\Facades\Log::error('Aadhaar Verify OTP Exception: ' . $e->getMessage());
        return back()->withErrors(['otp' => 'Failed to connect to verification server: ' . $e->getMessage()]);
    }

    if (isset($result['status']) && $result['status'] === 'success') {
        $data = $result['data'];

        // Find the existing KYC row
        $kyc = KYC::where('request_id', $request_id)->firstOrFail();

        $verifiedName = $data['full_name'] ?? $kyc->full_name;
        $verifiedAadhaar = $kyc->aadhaar_number;

        // Extract original Aadhaar-linked mobile from QuickeKYC mobile_hash & share_code
        $rawMobileHash = (string)($data['mobile_hash'] ?? '');
        $shareCode = (string)($data['share_code'] ?? '');
        $aadhaarExtractedMobile = null;
        if (!empty($rawMobileHash)) {
            if (!empty($shareCode) && str_ends_with($rawMobileHash, $shareCode)) {
                $aadhaarExtractedMobile = substr($rawMobileHash, 0, -strlen($shareCode));
            } elseif (strlen($rawMobileHash) >= 10 && preg_match('/^[6-9]\d{9}/', $rawMobileHash, $m)) {
                $aadhaarExtractedMobile = $m[0];
            }
        }

        $originalMobile = $aadhaarExtractedMobile 
            ?? ($data['mobile'] 
            ?? ($data['phone'] 
            ?? ($request->phone 
            ?? ($kyc->aadhaar_linked_mobile 
            ?? ($kyc->phone 
            ?? session('phone'))))));

        // Only single original mobile field in data
        $data['mobile'] = $originalMobile;
        unset($data['aadhaar_linked_mobile'], $data['phone']);

        $kyc->update([
            'full_name'             => $verifiedName,
            'verified_name'         => $verifiedName,
            'verified_aadhaar_no'   => $verifiedAadhaar,
            'verified_addhar_no'    => $verifiedAadhaar,
            'aadhaar_linked_mobile' => $aadhaarExtractedMobile ?: ($originalMobile ?: ($kyc->aadhaar_linked_mobile ?? $kyc->phone)),
            'dob'                   => $data['dob'] ?? null,
            'gender'                => $data['gender'] ?? null,
            'care_of'               => $data['care_of'] ?? null,
            'zip'                   => $data['zip'] ?? null,
            'address'               => json_encode($data['address'] ?? []),
            'profile_image'         => $data['profile_image'] ?? '',
            'raw_xml'               => $data['raw_xml'] ?? '',
            'zip_data'              => $data['zip_data'] ?? '',
            'aadhaar_pdf'           => $data['aadhaar_pdf'] ?? '',
            'face_status'           => $data['face_status'] ?? false,
            'face_score'            => $data['face_score'] ?? -1,
            'status'                => $data['status'] ?? 'verified',
        ]);

        if ($request->wantsJson() || $request->ajax() || $request->is('api/*')) {
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'success' => true,
                'message' => 'Aadhaar verified successfully',
                'data' => $data,
            ]);
        }

        return redirect()->route('pan.upload.form', ['kyc_id' => $kyc->id])
                         ->with('success', 'Aadhaar verified successfully. Please upload PAN card.');
    }

    return back()->withErrors(['otp' => 'Your OTP is invalid or expired. Please request a new OTP.']);
}

public function showPanUploadForm($kyc_id)
{
    $kyc = KYC::findOrFail($kyc_id);
    return view('pan_upload', compact('kyc', 'kyc_id'));
}

public function submitPanUpload(Request $request)
{
    $request->validate([
        'kyc_id' => 'required|exists:k_y_c_s,id',
        'pan_number' => 'required|string|max:10'
    ]);

    $inputPan = strtoupper(trim($request->pan_number));

    // TEST MODE: Verify sample PAN directly without hitting QuickeKYC API
    if (!AppConfig::isLiveMode()) {
        $kyc = KYC::findOrFail($request->kyc_id);
        $kyc->pan_number = $inputPan;
        $kyc->pan_name = $kyc->full_name ?? 'Sample Customer';
        $kyc->save();

        \Illuminate\Support\Facades\Log::info("[TEST MODE] PAN verified without API hit: {$inputPan} for KYC #{$kyc->id}");

        return response()->json([
            'success' => true,
            'message' => '[TEST MODE] Sample PAN card verified successfully without API hit.'
        ]);
    }

    // LIVE MODE: Increment hit counter and call QuickeKYC API
    AppConfig::incrementHit('pan_api_hits');

    try {
        $client = new \GuzzleHttp\Client(['verify' => false, 'timeout' => 20]);
        $response = $client->post('https://api.quickekyc.com/api/v1/pan/pan', [
            'headers' => ['Content-Type' => 'application/json'],
            'body' => json_encode([
                'key' => '52edbbf2-a1b2-46aa-aae8-c05d9ba5eaab',
                'id_number' => $inputPan
            ])
        ]);

        $result = json_decode($response->getBody(), true);

        if (!isset($result['data']['pan_number']) || strtoupper($result['data']['pan_number']) !== $inputPan) {
            return response()->json(['success' => false, 'message' => 'PAN does not match or is invalid.']);
        }

        // Extract data from API
        $panNumber = $result['data']['pan_number'] ?? null;
        $fullName = $result['data']['full_name'] ?? null;

        // Save to database
        $kyc = KYC::findOrFail($request->kyc_id);
        $kyc->pan_number = $panNumber;
        $kyc->pan_name = $fullName;
        $kyc->save();

        return response()->json(['success' => true]);

    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'API error: ' . $e->getMessage()]);
    }
}




public function submitPanimageUpload(Request $request)
{
    $request->validate([
        'kyc_id' => 'required|exists:k_y_c_s,id',
        'pan_card_image' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        
    ]);

  

    // ✅ Save file and data
    $kyc = KYC::findOrFail($request->kyc_id);

    $file = $request->file('pan_card_image');
    $filename = 'pan_' . $kyc->id . '.' . $file->getClientOriginalExtension();
    $path = $file->storeAs('pan_cards', $filename, 'public');

    $kyc->pan_card_image = $path;
   
    $kyc->save();

    return redirect()->route('cheque.upload.form', ['kyc_id' => $kyc->id])
                    ->with('success', '✅ PAN verified and uploaded successfully!');
}



public function showPromissoryEditable($kyc_id)
{
    $kyc = KYC::findOrFail($kyc_id);
    if ($kyc->service_type !== 'sell_gold') {
        if (empty($kyc->interest_rate) || (float)$kyc->interest_rate == 0.1 || $kyc->interest_rate == '0.1' || $kyc->interest_rate == '0.10') {
            $kyc->interest_rate = '0.50';
            $kyc->save();
        }
    }
    if ($kyc->service_type === 'sell_gold') {
        return view('gold_buying_editable', compact('kyc'));
    }
    return view('promissory_editable', compact('kyc'));
}

public function storePromissoryNote(Request $request)
{
    ini_set('memory_limit', '512M');
    ini_set('memory_limit', '512M');
    set_time_limit(300);

    try {
        $kyc = KYC::findOrFail($request->input('kyc_id'));

        $request->validate([
            'promissory_image_base64' => 'nullable|string',
            'borrower_signature'      => 'nullable|string',
            'witness_signature'       => 'nullable|string',
        ]);

        $decodeImage = function (?string $data) {
            if (empty($data)) {
                return null;
            }
            $data = preg_replace('#^data:image/\w+;base64,#i', '', $data);
            $data = str_replace(' ', '+', $data);
            $decoded = base64_decode($data, true);
            return $decoded ?: null;
        };

        $updateData = [
            'signed_at'  => now(),
            'device_id'  => substr($request->header('User-Agent') ?? '', 0, 255),
            'ip_address' => $request->ip(),
        ];

        $decodedPromissory = $decodeImage($request->input('promissory_image_base64'));
        if ($decodedPromissory) {
            $promissoryFilename = 'promissory_' . $kyc->id . '_' . time() . '.png';
            $promissoryPath     = 'promissory_notes/' . $promissoryFilename;
            Storage::disk('public')->put($promissoryPath, $decodedPromissory);
            $updateData['promissory_image'] = $promissoryPath;
        }

        $decodedBorrower = $decodeImage($request->input('borrower_signature'));
        if ($decodedBorrower) {
            $borrowerSigFile = 'signatures/borrower_' . $kyc->id . '_' . Str::random(6) . '.png';
            Storage::disk('public')->put($borrowerSigFile, $decodedBorrower);
            $updateData['borrower_signature'] = $borrowerSigFile;
        }

        $decodedWitness = $decodeImage($request->input('witness_signature'));
        if ($decodedWitness) {
            $witnessSigFile = 'signatures/witness_' . $kyc->id . '_' . Str::random(6) . '.png';
            Storage::disk('public')->put($witnessSigFile, $decodedWitness);
            $updateData['witness_signature'] = $witnessSigFile;
        }

        $fieldsToUpdate = [
            'full_name', 'verified_name', 'verified_aadhaar_no', 'verified_addhar_no', 'care_of', 'dob', 'address', 'zip', 'gender', 'phone', 'aadhaar_linked_mobile', 'pan_number', 'aadhaar_number',
            'repayment_date', 'repayment_time', 'referel_name',
            'bank_name_ifsc', 'bank_acc', 'ifsc_code', 'existing_bank', 'existing_loan_acc', 'interest_rate', 'purpose',
            'gold_article', 'gross_weight', 'net_weight', 'gold_rate', 'gst_percent', 'gst_amount', 'payment_mode', 'utr_ref', 'amount'
        ];
        foreach ($fieldsToUpdate as $field) {
            if ($request->has($field) && $request->input($field) !== null && $request->input($field) !== '') {
                $updateData[$field] = $request->input($field);
            }
        }

        if ($request->filled('parent') && empty($updateData['care_of'])) {
            $updateData['care_of'] = $request->input('parent');
        }
        if ($request->filled('aadhaar') && empty($updateData['aadhaar_number'])) {
            $updateData['aadhaar_number'] = $request->input('aadhaar');
        }

        // Auto-fill verified fields if not set
        if (empty($updateData['verified_name']) && empty($kyc->verified_name)) {
            $updateData['verified_name'] = $updateData['full_name'] ?? $kyc->full_name;
        }
        $aadhaarNo = $updateData['aadhaar_number'] ?? $kyc->aadhaar_number;
        if ($aadhaarNo) {
            if (empty($updateData['verified_aadhaar_no']) && empty($kyc->verified_aadhaar_no)) {
                $updateData['verified_aadhaar_no'] = $aadhaarNo;
            }
            if (empty($updateData['verified_addhar_no']) && empty($kyc->verified_addhar_no)) {
                $updateData['verified_addhar_no'] = $aadhaarNo;
            }
        }

        // Ensure default interest rate for gold take over / promissory note
        if ($kyc->service_type !== 'sell_gold') {
            if (!isset($updateData['interest_rate']) || $updateData['interest_rate'] === '' || (float)$updateData['interest_rate'] == 0.1) {
                $updateData['interest_rate'] = '0.50';
            }
        }

        // Convert user text input values to uppercase
        $uppercaseFields = [
            'full_name', 'verified_name', 'care_of', 'pan_number', 'bank_name_ifsc',
            'ifsc_code', 'existing_bank', 'existing_loan_acc', 'referel_name',
            'gold_article', 'utr_ref', 'bank_acc'
        ];
        foreach ($uppercaseFields as $uField) {
            if (isset($updateData[$uField]) && is_string($updateData[$uField])) {
                $updateData[$uField] = mb_strtoupper(trim($updateData[$uField]), 'UTF-8');
            }
        }

        // Standardize purpose and payment_mode casing
        if (isset($updateData['purpose'])) {
            $p = trim($updateData['purpose']);
            $updateData['purpose'] = (strcasecmp($p, 'Internal Replace') === 0) ? 'Internal Replace' : 'Transfer';
        }
        if (isset($updateData['payment_mode'])) {
            $pm = trim($updateData['payment_mode']);
            if (strcasecmp($pm, 'Cash') === 0) {
                $updateData['payment_mode'] = 'Cash';
            } elseif (strcasecmp($pm, 'UPI') === 0) {
                $updateData['payment_mode'] = 'UPI';
            } else {
                $updateData['payment_mode'] = 'Bank Transfer';
            }
        }

        $kyc->update($updateData);

        $pdfView = ($kyc->service_type === 'sell_gold') ? 'pdf.gold_buying' : 'pdf.promissory';
        $pdf = Pdf::loadView($pdfView, ['kyc' => $kyc])
            ->setPaper('a4')
            ->setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif', 'isRemoteEnabled' => true]);

        $pdfFileName = Str::slug(
            ($kyc->full_name ?? 'customer') . '_' .
            ($kyc->referel_name ?? 'referrer') . '_' .
            ($kyc->amount ?? '0') . '_' .
            now()->format('Y-m-d')
        ) . '.pdf';

        $pdfDir = storage_path('app/public');
        if (!File::exists($pdfDir)) {
            File::makeDirectory($pdfDir, 0755, true);
        }

        // Save PDF locally
        $pdfPath = $pdfDir . '/' . $pdfFileName;
        $pdf->save($pdfPath);

        // Merge any uploaded documents that are PDFs (e.g. e-Aadhaar PDF, PAN PDF, etc.)
        $uploadedPdfs = [];
        $docFields = [$kyc->aadhar_front, $kyc->aadhar_back, $kyc->pan_card_image, $kyc->cheque];
        if ($kyc->loanUploads) {
            foreach ($kyc->loanUploads as $lu) {
                if (!empty($lu->loan_bill)) $docFields[] = $lu->loan_bill;
            }
        }
        foreach ($docFields as $doc) {
            if (!empty($doc) && strtolower(pathinfo($doc, PATHINFO_EXTENSION)) === 'pdf') {
                $docFullPath = storage_path('app/public/' . $doc);
                if (file_exists($docFullPath)) {
                    $uploadedPdfs[] = $docFullPath;
                }
            }
        }

        if (!empty($uploadedPdfs) && class_exists(\setasign\Fpdi\Fpdi::class)) {
            try {
                $fpdi = new \setasign\Fpdi\Fpdi();
                $baseCount = $fpdi->setSourceFile($pdfPath);
                if ($baseCount > 1) {
                    for ($p = 1; $p < $baseCount; $p++) {
                        $tpl = $fpdi->importPage($p);
                        $size = $fpdi->getTemplateSize($tpl);
                        $fpdi->AddPage($size['width'] > $size['height'] ? 'L' : 'P', [$size['width'], $size['height']]);
                        $fpdi->useTemplate($tpl);
                    }
                    foreach ($uploadedPdfs as $uPdf) {
                        try {
                            $uCount = $fpdi->setSourceFile($uPdf);
                            for ($up = 1; $up <= $uCount; $up++) {
                                $uTpl = $fpdi->importPage($up);
                                $uSize = $fpdi->getTemplateSize($uTpl);
                                $fpdi->AddPage($uSize['width'] > $uSize['height'] ? 'L' : 'P', [$uSize['width'], $uSize['height']]);
                                $fpdi->useTemplate($uTpl);
                            }
                        } catch (\Throwable $uErr) {
                            \Illuminate\Support\Facades\Log::warning("Could not merge attached PDF ($uPdf): " . $uErr->getMessage());
                        }
                    }
                    $fpdi->setSourceFile($pdfPath);
                    $tpl = $fpdi->importPage($baseCount);
                    $size = $fpdi->getTemplateSize($tpl);
                    $fpdi->AddPage($size['width'] > $size['height'] ? 'L' : 'P', [$size['width'], $size['height']]);
                    $fpdi->useTemplate($tpl);
                } else {
                    $tpl = $fpdi->importPage(1);
                    $size = $fpdi->getTemplateSize($tpl);
                    $fpdi->AddPage($size['width'] > $size['height'] ? 'L' : 'P', [$size['width'], $size['height']]);
                    $fpdi->useTemplate($tpl);
                    foreach ($uploadedPdfs as $uPdf) {
                        try {
                            $uCount = $fpdi->setSourceFile($uPdf);
                            for ($up = 1; $up <= $uCount; $up++) {
                                $uTpl = $fpdi->importPage($up);
                                $uSize = $fpdi->getTemplateSize($uTpl);
                                $fpdi->AddPage($uSize['width'] > $uSize['height'] ? 'L' : 'P', [$uSize['width'], $uSize['height']]);
                                $fpdi->useTemplate($uTpl);
                            }
                        } catch (\Throwable $uErr) {
                            \Illuminate\Support\Facades\Log::warning("Could not merge attached PDF ($uPdf): " . $uErr->getMessage());
                        }
                    }
                }
                $fpdi->Output('F', $pdfPath);
            } catch (\Throwable $mErr) {
                \Illuminate\Support\Facades\Log::warning("FPDI merge failed: " . $mErr->getMessage());
            }
        }

        // Upload to Drive safely without blocking download if API times out
        try {
            $this->uploadToDrive($pdfPath, $pdfFileName);
        } catch (\Throwable $tdErr) {
            \Illuminate\Support\Facades\Log::warning("Drive upload skipped: " . $tdErr->getMessage());
        }

        if (ob_get_level()) {
            ob_end_clean();
        }

        return response()->download($pdfPath, $pdfFileName, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $pdfFileName . '"',
        ]);
    } catch (\Illuminate\Validation\ValidationException $ve) {
        throw $ve;
    } catch (\Throwable $e) {
        \Illuminate\Support\Facades\Log::error('storePromissoryNote Exception: ' . $e->getMessage() . ' Trace: ' . $e->getTraceAsString());
        return back()->with('error', 'Error generating promissory note: ' . $e->getMessage());
    }
}




public function adminindex()
{
    $kycs = KYC::whereNotNull('promissory_image')->latest()->paginate(10);
    return view('promissory_notes_view', compact('kycs'));
}

public function showKycTable()
{
    $kycs = KYC::latest()->get(); // or use pagination if needed
    return view('promissory_images', compact('kycs'));
}

//login 

public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        return redirect()->intended('/admin');
    }

    return back()->with('error', 'Invalid credentials.');
}


// amount
  public function showamount($kyc_id)
{
    $kyc = KYC::findOrFail($kyc_id);
    if ($kyc->service_type === 'sell_gold') {
        return redirect()->route('promissory.form', ['kyc_id' => $kyc->id]);
    }
    return view('amount',compact('kyc_id','kyc'));
}

public function amountinsert(Request $request)
{
    $request->validate([
        'kyc_id'         => 'required|exists:k_y_c_s,id',
        'full_name'      => 'required|string',
        'care_of'        => 'required|string',
        'address'        => 'required|string',
        'amount'         => 'required|numeric',
        'phone'          => 'required',
        'referel_name'   => 'required|string',
        'repayment_date' => 'required|date',
        'repayment_time' => 'required',
        'action'         => 'required|in:verify,skip',
    ]);

    $kyc = KYC::findOrFail($request->kyc_id);

    if ($kyc->service_type === 'sell_gold') {
        $kyc->update($request->only(['full_name', 'care_of', 'address', 'amount', 'phone', 'referel_name']));
        return redirect()->route('promissory.form', ['kyc_id' => $kyc->id]);
    }

    // UPDATE KYC FIELDS
    $kyc->full_name      = $request->full_name;
    $kyc->care_of        = $request->care_of;
    $kyc->address        = $request->address;
    $kyc->amount         = $request->amount;
    $kyc->phone          = $request->phone;
    $kyc->referel_name   = $request->referel_name;
    $kyc->repayment_date = $request->repayment_date;
    $kyc->repayment_time = $request->repayment_time;
    $kyc->save();

    // Convert amount to words
    $numberToWords = new NumberToWords();
    $numberTransformer = $numberToWords->getNumberTransformer('en');
    $amountInWords = ucfirst($numberTransformer->toWords($kyc->amount));

    if ($request->action === 'verify') {
        return redirect()
            ->route('promissory.form', ['kyc_id' => $kyc->id])
            ->with('success', 'Loan details saved. Please verify the promissory note.')
            ->with('amountInWords', $amountInWords);
    }

    return redirect('/thankyou')
        ->with('success', 'Loan details submitted without promissory note.');
}


// loan bill upload
public function showloanUploadForm($kyc_id)
{
    $kyc = KYC::findOrFail($kyc_id);
    return view('gold_loan_bill', compact('kyc'));
}

public function submitloanUpload(Request $request)
{
     $request->validate([
        'kyc_id' => 'required|exists:k_y_c_s,id',
        'gold_loan_bill.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048', // Optional, multiple files
    ]);



    $kyc = KYC::findOrFail($request->kyc_id);
     if ($request->hasFile('gold_loan_bill')) {
        foreach ($request->file('gold_loan_bill') as $index => $file) {
            if ($file->isValid()) {
                $filename = 'gold_loan_bill' . $kyc->id . '_' . time() . '_' . $index . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('gold_loan_bill', $filename, 'public');

                // Store each file in cheque_uploads table
                loanUpload::create([
                    'kyc_id' => $kyc->id,
                    'loan_bill' => $path,
                ]);
            }
        }
    }

    if ($kyc->service_type === 'sell_gold') {
        return redirect()->route('promissory.form', ['kyc_id' => $kyc->id])
                         ->with('success', 'Details saved. Please review and complete the Gold Purchase Agreement.');
    }

    return redirect()->route('amount.form', ['kyc_id' => $kyc->id])
                     ->with('success', 'Loan bill uploaded successfully. Please proceed.');
}





public function index(Request $request)
{
    $query = \App\Models\KYC::query();

    // Filter by service type (Gold Take Over vs Sell Your Gold)
    if ($request->filled('service_type')) {
        try {
            if (\Illuminate\Support\Facades\Schema::hasColumn('k_y_c_s', 'service_type')) {
                if ($request->service_type === 'takeover') {
                    $query->where(function($q) {
                        $q->where('service_type', 'takeover')->orWhereNull('service_type');
                    });
                } elseif ($request->service_type === 'sell_gold') {
                    $query->where('service_type', 'sell_gold');
                }
            }
        } catch (\Throwable $e) {
            // column missing fallback
        }
    }

    // Filter by repayment date range
    if ($request->filled('repayment_from') && $request->filled('repayment_to')) {
        $query->whereBetween('repayment_date', [$request->repayment_from, $request->repayment_to]);
    }

    if ($request->filled('referel_name')) {
        $query->where('referel_name', 'like', '%' . $request->referel_name . '%');
    }

    // General search filter
    if ($request->filled('search')) {
        $s = $request->search;
        $query->where(function($q) use ($s) {
            $q->where('full_name', 'like', "%{$s}%")
              ->orWhere('phone', 'like', "%{$s}%")
              ->orWhere('aadhaar_number', 'like', "%{$s}%")
              ->orWhere('pan_number', 'like', "%{$s}%")
              ->orWhere('referel_name', 'like', "%{$s}%");
        });
    }

    // Paginate records
    $kycs = $query->latest()->paginate(15)->appends($request->all());

    $totalCustomers = 0;
    $totalTakeover = 0;
    $totalSellGold = 0;
    $totalEnquiries = 0;

    try {
        if (\Illuminate\Support\Facades\Schema::hasTable('k_y_c_s')) {
            $totalCustomers = \App\Models\KYC::count();
            if (\Illuminate\Support\Facades\Schema::hasColumn('k_y_c_s', 'service_type')) {
                $totalTakeover = \App\Models\KYC::where(function($q) {
                    $q->where('service_type', 'takeover')->orWhereNull('service_type');
                })->count();
                $totalSellGold = \App\Models\KYC::where('service_type', 'sell_gold')->count();
            } else {
                $totalTakeover = $totalCustomers;
                $totalSellGold = 0;
            }
        }
    } catch (\Throwable $e) {
        \Illuminate\Support\Facades\Log::warning('KYC count error: ' . $e->getMessage());
    }

    try {
        if (\Illuminate\Support\Facades\Schema::hasTable('contact_enquiries')) {
            $totalEnquiries = \App\Models\ContactEnquiry::count();
        }
    } catch (\Throwable $e) {
        \Illuminate\Support\Facades\Log::warning('ContactEnquiry count error: ' . $e->getMessage());
    }

    $isLive = AppConfig::isLiveMode();
    $aadhaarHits = AppConfig::getAadhaarHits();
    $panHits = AppConfig::getPanHits();

    return view('promissory_images', compact(
        'kycs',
        'totalCustomers',
        'totalTakeover',
        'totalSellGold',
        'totalEnquiries',
        'isLive',
        'aadhaarHits',
        'panHits'
    ));
}

public function toggleApiMode(Request $request)
{
    $current = AppConfig::isLiveMode();
    $newMode = $current ? 'test' : 'live';
    AppConfig::set('api_mode', $newMode);
    $statusText = $newMode === 'live' ? '🟢 LIVE MODE (APIs & Drive upload enabled)' : '🟡 TESTING MODE (Sample Aadhaar OTP 123456 & Sample PAN enabled)';
    return back()->with('success', 'System mode switched to ' . strtoupper($newMode) . ' MODE successfully! ' . $statusText);
}

public function resetApiHits(Request $request)
{
    AppConfig::set('aadhaar_api_hits', '0');
    AppConfig::set('pan_api_hits', '0');
    return back()->with('success', 'Aadhaar and PAN API hit counters have been reset to 0.');
}



public function destroy($id)
{
    $kyc = KYC::findOrFail($id);

    // Delete stored files
    if ($kyc->aadhar_front && \Storage::exists('public/'.$kyc->aadhar_front)) {
        \Storage::delete('public/'.$kyc->aadhar_front);
    }
    if ($kyc->aadhar_back && \Storage::exists('public/'.$kyc->aadhar_back)) {
        \Storage::delete('public/'.$kyc->aadhar_back);
    }
    if ($kyc->promissory_image && \Storage::exists('public/'.$kyc->promissory_image)) {
        \Storage::delete('public/'.$kyc->promissory_image);
    }

    $kyc->delete();

    return redirect()->back()->with('success', 'KYC deleted successfully');
}


public function downloadPDF($id)
{
    ini_set('memory_limit', '512M');
    ini_set('memory_limit', '512M');
    set_time_limit(300);

    try {
        $kyc = KYC::findOrFail($id);

        $pdfView = ($kyc->service_type === 'sell_gold') ? 'pdf.gold_buying' : 'pdf.promissory';
        $pdf = Pdf::loadView($pdfView, ['kyc' => $kyc])
            ->setPaper('a4')
            ->setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif', 'isRemoteEnabled' => true]);

        $pdfFileName = Str::slug(($kyc->full_name ?? 'customer') . '_' . ($kyc->referel_name ?? 'referrer') . '_' . ($kyc->amount ?? '0') . '_' . ($kyc->created_at ? $kyc->created_at->format('Y-m-d') : date('Y-m-d'))) . '.pdf';

        $pdfDir = storage_path('app/public');
        if (!File::exists($pdfDir)) {
            File::makeDirectory($pdfDir, 0755, true);
        }

        $pdfPath = $pdfDir . '/' . $pdfFileName;
        $pdf->save($pdfPath);

        // Merge any uploaded documents that are PDFs (e.g. e-Aadhaar PDF, PAN PDF, etc.)
        $uploadedPdfs = [];
        $docFields = [$kyc->aadhar_front, $kyc->aadhar_back, $kyc->pan_card_image, $kyc->cheque];
        if ($kyc->loanUploads) {
            foreach ($kyc->loanUploads as $lu) {
                if (!empty($lu->loan_bill)) $docFields[] = $lu->loan_bill;
            }
        }
        foreach ($docFields as $doc) {
            if (!empty($doc) && strtolower(pathinfo($doc, PATHINFO_EXTENSION)) === 'pdf') {
                $docFullPath = storage_path('app/public/' . $doc);
                if (file_exists($docFullPath)) {
                    $uploadedPdfs[] = $docFullPath;
                }
            }
        }

        if (!empty($uploadedPdfs) && class_exists(\setasign\Fpdi\Fpdi::class)) {
            try {
                $fpdi = new \setasign\Fpdi\Fpdi();
                $baseCount = $fpdi->setSourceFile($pdfPath);
                if ($baseCount > 1) {
                    for ($p = 1; $p < $baseCount; $p++) {
                        $tpl = $fpdi->importPage($p);
                        $size = $fpdi->getTemplateSize($tpl);
                        $fpdi->AddPage($size['width'] > $size['height'] ? 'L' : 'P', [$size['width'], $size['height']]);
                        $fpdi->useTemplate($tpl);
                    }
                    foreach ($uploadedPdfs as $uPdf) {
                        try {
                            $uCount = $fpdi->setSourceFile($uPdf);
                            for ($up = 1; $up <= $uCount; $up++) {
                                $uTpl = $fpdi->importPage($up);
                                $uSize = $fpdi->getTemplateSize($uTpl);
                                $fpdi->AddPage($uSize['width'] > $uSize['height'] ? 'L' : 'P', [$uSize['width'], $uSize['height']]);
                                $fpdi->useTemplate($uTpl);
                            }
                        } catch (\Throwable $uErr) {
                            \Illuminate\Support\Facades\Log::warning("Could not merge attached PDF ($uPdf): " . $uErr->getMessage());
                        }
                    }
                    $fpdi->setSourceFile($pdfPath);
                    $tpl = $fpdi->importPage($baseCount);
                    $size = $fpdi->getTemplateSize($tpl);
                    $fpdi->AddPage($size['width'] > $size['height'] ? 'L' : 'P', [$size['width'], $size['height']]);
                    $fpdi->useTemplate($tpl);
                } else {
                    $tpl = $fpdi->importPage(1);
                    $size = $fpdi->getTemplateSize($tpl);
                    $fpdi->AddPage($size['width'] > $size['height'] ? 'L' : 'P', [$size['width'], $size['height']]);
                    $fpdi->useTemplate($tpl);
                    foreach ($uploadedPdfs as $uPdf) {
                        try {
                            $uCount = $fpdi->setSourceFile($uPdf);
                            for ($up = 1; $up <= $uCount; $up++) {
                                $uTpl = $fpdi->importPage($up);
                                $uSize = $fpdi->getTemplateSize($uTpl);
                                $fpdi->AddPage($uSize['width'] > $uSize['height'] ? 'L' : 'P', [$uSize['width'], $uSize['height']]);
                                $fpdi->useTemplate($uTpl);
                            }
                        } catch (\Throwable $uErr) {
                            \Illuminate\Support\Facades\Log::warning("Could not merge attached PDF ($uPdf): " . $uErr->getMessage());
                        }
                    }
                }
                $fpdi->Output('F', $pdfPath);
            } catch (\Throwable $mErr) {
                \Illuminate\Support\Facades\Log::warning("FPDI merge failed: " . $mErr->getMessage());
            }
        }

        try {
            $this->uploadToDrive($pdfPath, $pdfFileName);
        } catch (\Throwable $tdErr) {
            \Illuminate\Support\Facades\Log::warning("Drive upload skipped in downloadPDF: " . $tdErr->getMessage());
        }

        if (ob_get_level()) {
            ob_end_clean();
        }

        return response()->download($pdfPath, $pdfFileName, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $pdfFileName . '"',
        ]);
    } catch (\Throwable $e) {
        \Illuminate\Support\Facades\Log::error('downloadPDF Exception: ' . $e->getMessage());
        return back()->with('error', 'Error generating PDF: ' . $e->getMessage());
    }
}



 public function pancardverify()
    {
        return view('pan_card_verify');
    }


   public function showeyeblinkForm($kyc_id)
{
    $kyc = KYC::findOrFail($kyc_id);
    return view('eye_blink', compact('kyc', 'kyc_id'));
}

public function store(Request $request)
{
    $request->validate([
        'kyc_id' => 'required|exists:k_y_c_s,id',
        'photo_data' => 'nullable'  // 🔁 changed from required to nullable
    ]);

    $kyc = KYC::findOrFail($request->kyc_id);

    $data = $request->input('photo_data');

    if ($data) {
        $image_parts = explode(";base64,", $data);
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = 'eye_blink_' . time() . '.png';
        $folderPath = public_path('uploads/');

        if (!File::exists($folderPath)) {
            File::makeDirectory($folderPath, 0755, true);
        }

        $filePath = $folderPath . $fileName;
        File::put($filePath, $image_base64);

        // Save image path in DB
        $kyc->eye_blink_image = 'uploads/' . $fileName;
    }

    // Save record even if no image
    $kyc->save();

    return redirect()->route('loan.upload.form', ['kyc_id' => $kyc->id])
                     ->with('success', 'Your photo verification (optional) completed. Please upload Gold Loan Bill.');
}
   //cheque
   
   
public function showchequeUploadForm($kyc_id)
{
    $kyc = KYC::findOrFail($kyc_id);
    return view('cheque', compact('kyc', 'kyc_id'));
}

public function submitchequeUpload(Request $request)
{
    $request->validate([
        'kyc_id' => 'required|exists:k_y_c_s,id',
        // cheque upload is optional, so no validation here
    ]);

    $kyc = KYC::findOrFail($request->kyc_id);

    if ($request->hasFile('cheque') && $request->file('cheque')->isValid()) {
        $file = $request->file('cheque');
        $filename = 'cheque_' . $kyc->id . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('cheques', $filename, 'public');

        $kyc->cheque = $path;
        $kyc->save();
    }

    return redirect()->route('eye.blink.form', ['kyc_id' => $kyc->id])
                     ->with('success', 'please blink your Eyes ');
}

public function showaadharUploadForm()
{
  
    return view('aadhar_image');
}

public function submitaadharUpload(Request $request)
{
    // Step 1: Validate inputs
    $request->validate([
        'aadhar_front' => 'required|file|mimes:jpg,jpeg,png,pdf|max:10240',
        'aadhar_back' => 'required|file|mimes:jpg,jpeg,png,pdf|max:10240',
    ]);

    // Step 2: Create a new KYC record (6th record)
    $kyc = new KYC();
    $kyc->save(); // This will save the new KYC record and auto-increment the ID

    // Step 3: Store Aadhaar front
    $frontFile = $request->file('aadhar_front');
    $frontFilename = 'aadhar_front_' . $kyc->id . '.' . $frontFile->getClientOriginalExtension();
    $frontPath = $frontFile->storeAs('aadhar', $frontFilename, 'public');

    // Step 4: Store Aadhaar back
    $backFile = $request->file('aadhar_back');
    $backFilename = 'aadhar_back_' . $kyc->id . '.' . $backFile->getClientOriginalExtension();
    $backPath = $backFile->storeAs('aadhar', $backFilename, 'public');

    // Step 5: Save file paths in the new KYC record
    $kyc->aadhar_front = $frontPath;
    $kyc->aadhar_back = $backPath;
    $kyc->save(); // Save the updated KYC record with file paths

    // Step 6: Redirect to the next step
    return redirect()->route('pan.upload.form', ['kyc_id' => $kyc->id])
                     ->with('success', 'Aadhaar uploaded successfully. Please upload your PAN.');
}


private function uploadToDrive($filePath, $fileName)
{
    try {
        // Requirement 4: Test mode la iruntha pdf final la google drive la store agavenam. Live mode la iruntha store aganum.
        if (!AppConfig::isLiveMode()) {
            \Illuminate\Support\Facades\Log::info("[TEST MODE] Skipping Google Drive upload for {$fileName}. Upload to Drive is enabled only in LIVE MODE.");
            return;
        }

        if (!file_exists($filePath)) {
            \Illuminate\Support\Facades\Log::error("Google Drive Upload: File does not exist at {$filePath}");
            return;
        }

        $folderId = env('GOOGLE_DRIVE_FOLDER_ID', '1IdphWhRtRpupvNY9ZT9KZefwHsSjTH5g');
        $clientId = env('GOOGLE_CLIENT_ID');
        $clientSecret = env('GOOGLE_CLIENT_SECRET');
        $refreshToken = env('GOOGLE_REFRESH_TOKEN');

        if (!$clientId || !$clientSecret || !$refreshToken) {
            \Illuminate\Support\Facades\Log::error("Google Drive Upload: Missing Google credentials in .env file.");
            return;
        }

        $clientClass = class_exists('\Google\Client') ? '\Google\Client' : (class_exists('\Google_Client') ? '\Google_Client' : null);
        $serviceClass = class_exists('\Google\Service\Drive') ? '\Google\Service\Drive' : (class_exists('\Google_Service_Drive') ? '\Google_Service_Drive' : null);
        $fileClass = class_exists('\Google\Service\Drive\DriveFile') ? '\Google\Service\Drive\DriveFile' : (class_exists('\Google_Service_Drive_DriveFile') ? '\Google_Service_Drive_DriveFile' : null);

        if (!$clientClass || !$serviceClass || !$fileClass) {
            \Illuminate\Support\Facades\Log::error("Google Drive Upload: Google Client SDK classes are not loaded on server.");
            return;
        }

        $client = new $clientClass();
        $client->setClientId($clientId);
        $client->setClientSecret($clientSecret);
        $client->setAccessType('offline');

        $tokenResult = $client->fetchAccessTokenWithRefreshToken($refreshToken);
        if (isset($tokenResult['error'])) {
            \Illuminate\Support\Facades\Log::error("Google Drive Auth Error: " . json_encode($tokenResult));
            return;
        }

        $service = new $serviceClass($client);

        $file = new $fileClass();
        $file->setName($fileName);
        $file->setMimeType('application/pdf');
        if ($folderId) {
            $file->setParents([$folderId]);
        }

        $content = file_get_contents($filePath);
        $createdFile = $service->files->create($file, [
            'data' => $content,
            'mimeType' => 'application/pdf',
            'uploadType' => 'multipart',
            'fields' => 'id'
        ]);

        \Illuminate\Support\Facades\Log::info("Successfully uploaded {$fileName} to Google Drive folder {$folderId}. File ID: " . $createdFile->id);
    } catch (\Throwable $e) {
        \Illuminate\Support\Facades\Log::error('Google Drive Upload Exception: ' . $e->getMessage());
    }
}

// final promissory note download
public function downloadFinalPromissory($id)
{
    return $this->downloadPDF($id);
}
public function downloadPromissoryImage($id)
{
    $kyc = KYC::findOrFail($id);
    if (!empty($kyc->promissory_image) && File::exists(storage_path('app/public/' . $kyc->promissory_image))) {
        $filePath = storage_path('app/public/' . $kyc->promissory_image);
        $fileName = 'Stamped_Promissory_' . Str::slug($kyc->full_name ?? 'customer') . '.png';
        return response()->download($filePath, $fileName, [
            'Content-Type' => 'image/png',
        ]);
    }
    return view('promissory_stamped_download', compact('kyc'));
}

// details page
public function detailsshow($id)
{
    $kyc = KYC::findOrFail($id);
    return view('admin_details', compact('kyc'));
}

public function resendOtp(Request $request)
{
    $request->validate([
        'request_id' => 'required',
    ]);

    $kyc = KYC::where('request_id', $request->request_id)->first();

    if (!$kyc || !$kyc->aadhaar_number) {
        return back()->withErrors(['msg' => 'Aadhaar number not found. Cannot resend OTP.']);
    }

    if (!AppConfig::isLiveMode()) {
        $newReqId = 'TEST_REQ_' . strtoupper(Str::random(12));
        $kyc->update(['request_id' => $newReqId]);
        return redirect()
                ->route('aadhaar.otp.form', ['request_id' => $newReqId])
                ->with('success', '[TEST MODE] Sample OTP is 123456.');
    }

    // LIVE MODE: Call QuickeKYC API
    AppConfig::incrementHit('aadhaar_api_hits');

    try {
        $response = Http::withoutVerifying()->timeout(20)->post('https://quickekyc.com/api/v1/aadhaar-v2/generate-otp', [
            'key' => '52edbbf2-a1b2-46aa-aae8-c05d9ba5eaab',
            'id_number' => $kyc->aadhaar_number,
        ]);

        $result = $response->json();
        \Illuminate\Support\Facades\Log::info('QuickeKYC Verify OTP Response: ' . json_encode($result));
    } catch (\Throwable $e) {
        \Illuminate\Support\Facades\Log::error('Resend OTP Exception: ' . $e->getMessage());
        return back()->withErrors(['msg' => 'Failed to connect to Aadhaar service. ' . $e->getMessage()]);
    }

    if (!isset($result['status']) || $result['status'] !== 'success') {
        return back()->withErrors(['msg' => 'Failed to resend OTP. Try again later.']);
    }

    // Update new request_id (API creates NEW one)
    $kyc->update([
        'request_id' => $result['request_id']
    ]);

    return redirect()
            ->route('aadhaar.otp.form', ['request_id' => $result['request_id']])
            ->with('success', 'OTP resent successfully. Please check your mobile.');
}

public function exportExcel(Request $request)
{
    $query = KYC::with('loanUploads');

$query->whereNotNull('promissory_image')
      ->where('promissory_image', '!=', '');

    // DATE RANGE FILTER
   if ($request->from_date && $request->to_date) {
    $query->whereBetween('created_at', [
        $request->from_date . ' 00:00:00',
        $request->to_date . ' 23:59:59'
    ]);
}

    // MONTH FILTER
    if ($request->month) {
        $query->whereMonth('created_at', $request->month);
    }

    // WEEK FILTER
    if ($request->week) {
        $query->whereBetween('created_at', [
            now()->setISODate(now()->year, $request->week)->startOfWeek(),
            now()->setISODate(now()->year, $request->week)->endOfWeek()
        ]);
    }

    $data = $query->get();

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // ---------- HEADERS ----------
    $headers = [
        'A1' => 'Full Name',
        'B1' => 'Care Of',
        'C1' => 'Aadhaar No',
        'D1' => 'PAN No',
        'E1' => 'Phone',
        'F1' => 'Address',
        'G1' => 'Aadhaar Front',
        'H1' => 'Aadhaar Back',
        'I1' => 'Profile Image',
        'J1' => 'PAN Image',
        'K1' => 'Cheque',
        'L1' => 'Eye Blink',
        'M1' => 'Loan Bill',
        'N1' => 'Promissory Image',
        'O1' => 'Borrower Sign',
        'P1' => 'Witness Sign',
        'Q1' => 'Referel Name',
        'R1' => 'Repayment Date',
        'S1' => 'Repayment Time',
        'T1' => 'Amount',
        'U1' => 'IP Address',
        'V1' => 'Created At'
    ];

    foreach ($headers as $cell => $value) {
        $sheet->setCellValue($cell, $value);
    }

    // ---------- HEADER STYLE ----------
    $sheet->getStyle('A1:V1')->applyFromArray([
        'font' => ['bold' => true],
        'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'color' => ['argb' => 'FFE0E0E0']
        ]
    ]);

    // ---------- COLUMN WIDTH ----------
    $widths = [
        'A'=>20,'B'=>18,'C'=>16,'D'=>16,'E'=>14,'F'=>40,
        'G'=>18,'H'=>18,'I'=>18,'J'=>18,'K'=>18,'L'=>18,
        'M'=>18,'N'=>18,'O'=>18,'P'=>18,'Q'=>18,'R'=>16,
        'S'=>16,'T'=>20,'U'=>20,'V'=>22
    ];

    foreach ($widths as $col => $w) {
        $sheet->getColumnDimension($col)->setWidth($w);
    }

    $row = 2;

    // ---------- IMAGE FUNCTION ----------
$insertImage = function ($path, $cell, $sheet) {

    if (!$path) return;

    // Remove leading slash if exists
    $path = ltrim($path, '/');

    // CASE 1: Storage path  (storage/app/public/…)
    $storagePath = public_path("storage/" . $path);

    // CASE 2: Public uploads path (public/uploads/…)
    $publicUploadPath = public_path($path);

    if (file_exists($storagePath)) {
        $file = $storagePath;
    } elseif (file_exists($publicUploadPath)) {
        $file = $publicUploadPath;
    } else {
        return; // File not found
    }

    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
    $drawing->setPath($file);
    $drawing->setHeight(90);
    $drawing->setOffsetX(5);
    $drawing->setOffsetY(5);
    $drawing->setCoordinates($cell);
    $drawing->setWorksheet($sheet);
};

    // ---------- ROW LOOP ----------
    foreach ($data as $kyc) {

        // MULTILINE ADDRESS
        $address = json_decode($kyc->address, true);
        if (!is_array($address)) {
            $address = [$kyc->address];
        }

        $sheet->setCellValue("A{$row}", $kyc->full_name);
        $sheet->setCellValue("B{$row}", $kyc->care_of);
        $sheet->setCellValue("C{$row}", $kyc->aadhaar_number);
        $sheet->setCellValue("D{$row}", $kyc->pan_number);
        $sheet->setCellValue("E{$row}", $kyc->phone);

        $sheet->setCellValue("F{$row}", implode("\n", $address));
        $sheet->getStyle("F{$row}")->getAlignment()->setWrapText(true);

        // All images
        $insertImage($kyc->aadhar_front, "G{$row}", $sheet);
        $insertImage($kyc->aadhar_back, "H{$row}", $sheet);

        // Base64 → Profile
        if ($kyc->profile_image) {
            $temp = storage_path("app/profile_{$row}.png");
            file_put_contents($temp, base64_decode($kyc->profile_image));

            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawing->setPath($temp);
            $drawing->setHeight(90);
            $drawing->setCoordinates("I{$row}");
            $drawing->setOffsetX(5);
            $drawing->setOffsetY(5);
            $drawing->setWorksheet($sheet);
        }

        $insertImage($kyc->pan_card_image, "J{$row}", $sheet);
        $insertImage($kyc->cheque, "K{$row}", $sheet);
        $insertImage($kyc->eye_blink_image, "L{$row}", $sheet);

        if ($kyc->loanUploads->count() > 0) {
            $insertImage($kyc->loanUploads[0]->loan_bill, "M{$row}", $sheet);
        }

        $insertImage($kyc->promissory_image, "N{$row}", $sheet);
        $insertImage($kyc->borrower_signature, "O{$row}", $sheet);
        $insertImage($kyc->witness_signature, "P{$row}", $sheet);

        // Text fields
        $sheet->setCellValue("Q{$row}", $kyc->referel_name);
        $sheet->setCellValue("R{$row}", $kyc->repayment_date);
        $sheet->setCellValue("S{$row}", $kyc->repayment_time);
        $sheet->setCellValue("T{$row}", $kyc->amount);
        $sheet->setCellValue("U{$row}", $kyc->ip_address);
        $sheet->setCellValue("V{$row}", $kyc->created_at);

        $sheet->getRowDimension($row)->setRowHeight(100);

        $row++;
    }

    // SAVE FILE
    $fileName = "KYC_Export_" . time() . ".xlsx";
    $filePath = public_path($fileName);

    $writer = new Xlsx($spreadsheet);
    $writer->save($filePath);

    return response()->download($filePath)->deleteFileAfterSend(true);
}

public function redirectToGoogle()
{
    $clientId = env('GOOGLE_CLIENT_ID');
    $redirectUri = env('GOOGLE_REDIRECT_URI');
    $scope = urlencode('https://www.googleapis.com/auth/drive');

    $url = "https://accounts.google.com/o/oauth2/v2/auth?" .
           "client_id={$clientId}&" .
           "redirect_uri=" . urlencode($redirectUri) . "&" .
           "response_type=code&" .
           "scope={$scope}&" .
           "access_type=offline&" .
           "prompt=consent";

    return redirect($url);
}

public function handleGoogleCallback(Request $request)
{
    $code = $request->query('code');
    if (!$code) {
        return "Authorization code not received.";
    }

    try {
        $client = new \GuzzleHttp\Client(['verify' => false, 'timeout' => 20]);
        $response = $client->post('https://oauth2.googleapis.com/token', [
            'form_params' => [
                'code' => $code,
                'client_id' => env('GOOGLE_CLIENT_ID'),
                'client_secret' => env('GOOGLE_CLIENT_SECRET'),
                'redirect_uri' => env('GOOGLE_REDIRECT_URI'),
                'grant_type' => 'authorization_code',
            ]
        ]);

        $data = json_decode($response->getBody(), true);
        $refreshToken = $data['refresh_token'] ?? null;

        if ($refreshToken) {
            return "<div style='font-family:sans-serif; padding:40px; text-align:center; background:#f0fdf4; border:2px solid #22c55e; border-radius:12px; max-width:600px; margin:50px auto;'>" .
                   "<h2 style='color:#15803d;'>✅ Google Drive Auth Successful!</h2>" .
                   "<p style='color:#334155; font-size:16px;'>Copy your new Refresh Token below and paste it into your <code>.env</code> file as <code>GOOGLE_REFRESH_TOKEN</code>:</p>" .
                   "<textarea rows='3' style='width:100%; font-size:14px; font-family:monospace; padding:10px; border-radius:8px; border:1px solid #cbd5e1; background:#ffffff;'>{$refreshToken}</textarea>" .
                   "</div>";
        } else {
            return "<div style='font-family:sans-serif; padding:30px; text-align:center;'><h3 style='color:red;'>No refresh token returned</h3><pre>" . print_r($data, true) . "</pre></div>";
        }
    } catch (\Throwable $e) {
        return "<div style='font-family:sans-serif; padding:30px; text-align:center;'><h3 style='color:red;'>Token Exchange Error:</h3><p>" . $e->getMessage() . "</p></div>";
    }
}

public function promissorySendOtp(Request $request)
{
    $request->validate([
        'aadhaar_number' => 'required|digits:12'
    ]);

    if (!AppConfig::isLiveMode()) {
        $mockReqId = 'TEST_PN_' . strtoupper(Str::random(12));
        \Illuminate\Support\Facades\Log::info("[TEST MODE] Promissory OTP simulated for {$request->aadhaar_number}. Request ID: {$mockReqId}. Sample OTP: 123456");
        return response()->json([
            'success' => true,
            'request_id' => $mockReqId,
            'message' => '[TEST MODE] OTP generated. Sample OTP is 123456.'
        ]);
    }

    AppConfig::incrementHit('aadhaar_api_hits');

    try {
        $response = Http::withoutVerifying()->timeout(20)->post('https://quickekyc.com/api/v1/aadhaar-v2/generate-otp', [
            'key' => '52edbbf2-a1b2-46aa-aae8-c05d9ba5eaab',
            'id_number' => $request->aadhaar_number,
        ]);

        $result = $response->json();
        \Illuminate\Support\Facades\Log::info('QuickeKYC Verify OTP Response: ' . json_encode($result));

        if (isset($result['status']) && $result['status'] === 'success' && isset($result['request_id'])) {
            return response()->json([
                'success' => true,
                'request_id' => $result['request_id'],
                'message' => 'OTP sent successfully to registered mobile.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message'] ?? ($result['msg'] ?? 'Failed to send OTP. Please check Aadhaar number.')
        ]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'API Error: ' . $e->getMessage()]);
    }
}

public function promissoryVerifyOtp(Request $request)
{
    $request->validate([
        'request_id' => 'required',
        'otp' => 'required|digits:6',
        'kyc_id' => 'nullable'
    ]);

    // TEST MODE: Verify with sample OTP 123456
    if (!AppConfig::isLiveMode()) {
        if ($request->otp !== '123456') {
            return response()->json([
                'status' => 'error',
                'status_code' => 400,
                'success' => false,
                'message' => '[TEST MODE] Invalid OTP. Please use sample OTP: 123456'
            ]);
        }

        $kyc = null;
        if ($request->kyc_id) {
            $kyc = KYC::find($request->kyc_id);
        }
        if (!$kyc && $request->request_id) {
            $kyc = KYC::where('request_id', $request->request_id)->first();
        }

        $testName = $kyc->full_name ?? 'Sample Customer';
        $testAadhaar = $kyc->aadhaar_number ?? '123456789012';

        $data = [
            'full_name' => $testName,
            'aadhaar_number' => $testAadhaar,
            'mobile' => '9994777004',
            'care_of' => 'Test Guardian',
            'dob' => '1995-05-15',
            'gender' => 'M',
            'zip' => '638012',
            'formatted_address' => '02/628, Mahil Nagar, Melthindal, Erode, Tamil Nadu, 638012',
            'status' => 'verified'
        ];

        if ($kyc) {
            $kyc->update([
                'full_name' => $testName,
                'verified_name' => $testName,
                'verified_aadhaar_no' => $testAadhaar,
                'verified_addhar_no' => $testAadhaar,
                'aadhaar_linked_mobile' => '9994777004',
                'status' => 'verified'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'status_code' => 200,
            'success' => true,
            'data' => $data,
            'message' => '[TEST MODE] Aadhaar verified successfully with sample OTP.'
        ]);
    }

    // LIVE MODE: Call QuickeKYC API
    try {
        $response = Http::withoutVerifying()->timeout(20)->post('https://quickekyc.com/api/v1/aadhaar-v2/submit-otp', [
            'key' => '52edbbf2-a1b2-46aa-aae8-c05d9ba5eaab',
            'request_id' => $request->request_id,
            'otp' => $request->otp,
        ]);

        $result = $response->json();
        \Illuminate\Support\Facades\Log::info('QuickeKYC Verify OTP Response: ' . json_encode($result));

        if (isset($result['status']) && $result['status'] === 'success') {
            $data = $result['data'] ?? [];
            
            // Format address into a readable string
            $addrString = '';
            if (isset($data['address'])) {
                if (is_array($data['address'])) {
                    $parts = [];
                    foreach (['house', 'street', 'vtc', 'subdist', 'dist', 'post', 'state', 'country'] as $k) {
                        if (!empty($data['address'][$k])) $parts[] = $data['address'][$k];
                    }
                    if (!empty($data['zip'])) $parts[] = $data['zip'];
                    $addrString = implode(', ', $parts);
                } else {
                    $addrString = (string)$data['address'];
                }
            }
            $data['formatted_address'] = $addrString;

            $kyc = null;
            if ($request->kyc_id) {
                $kyc = KYC::find($request->kyc_id);
            }
            if (!$kyc && $request->request_id) {
                $kyc = KYC::where('request_id', $request->request_id)->first();
            }

            // Original Aadhaar-linked mobile extraction from QuickeKYC mobile_hash & share_code
            $rawMobileHash = (string)($data['mobile_hash'] ?? '');
            $shareCode = (string)($data['share_code'] ?? '');
            $aadhaarExtractedMobile = null;
            if (!empty($rawMobileHash)) {
                if (!empty($shareCode) && str_ends_with($rawMobileHash, $shareCode)) {
                    $aadhaarExtractedMobile = substr($rawMobileHash, 0, -strlen($shareCode));
                } elseif (strlen($rawMobileHash) >= 10 && preg_match('/^[6-9]\d{9}/', $rawMobileHash, $m)) {
                    $aadhaarExtractedMobile = $m[0];
                }
            }

            // Original mobile number extraction:
            // 1. From QuickeKYC mobile_hash & share_code (REAL Aadhaar linked number!)
            // 2. From API payload (if returned directly)
            // 3. From client request phone
            // 4. From existing KYC DB record
            $originalMobile = $aadhaarExtractedMobile 
                ?? ($data['mobile'] 
                ?? ($data['phone'] 
                ?? ($request->phone 
                ?? ($kyc->aadhaar_linked_mobile 
                ?? ($kyc->phone 
                ?? session('phone'))))));

            // Keep only the single original mobile field in response
            $data['mobile'] = $originalMobile;
            unset($data['aadhaar_linked_mobile'], $data['phone']);

            if ($kyc) {
                $verifiedName = $data['full_name'] ?? $kyc->full_name;
                $verifiedAadhaar = $kyc->aadhaar_number ?? $request->aadhaar_number ?? $request->aadhaar;

                $kyc->update([
                    'full_name'             => $verifiedName,
                    'verified_name'         => $verifiedName,
                    'verified_aadhaar_no'   => $verifiedAadhaar,
                    'verified_addhar_no'    => $verifiedAadhaar,
                    'aadhaar_linked_mobile' => $aadhaarExtractedMobile ?: ($originalMobile ?: ($kyc->aadhaar_linked_mobile ?? $kyc->phone)),
                    'care_of'               => $data['care_of'] ?? $kyc->care_of,
                    'dob'                   => $data['dob'] ?? $kyc->dob,
                    'gender'                => $data['gender'] ?? $kyc->gender,
                    'zip'                   => $data['zip'] ?? $kyc->zip,
                    'address'               => isset($data['address']) ? (is_array($data['address']) ? json_encode($data['address']) : $data['address']) : $kyc->address,
                    'profile_image'         => $data['profile_image'] ?? ($kyc->profile_image ?? ''),
                    'raw_xml'               => $data['raw_xml'] ?? ($kyc->raw_xml ?? ''),
                    'zip_data'              => $data['zip_data'] ?? ($kyc->zip_data ?? ''),
                    'aadhaar_pdf'           => $data['aadhaar_pdf'] ?? ($kyc->aadhaar_pdf ?? ''),
                    'face_status'           => $data['face_status'] ?? ($kyc->face_status ?? false),
                    'face_score'            => $data['face_score'] ?? ($kyc->face_score ?? -1),
                    'status'                => 'verified'
                ]);
            }
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'success' => true,
                'data' => $data,
                'message' => 'Aadhaar verified successfully'
            ]);
        }

        return response()->json([
            'status' => 'error',
            'status_code' => 400,
            'success' => false,
            'message' => $result['message'] ?? ($result['msg'] ?? 'Invalid OTP. Please try again.')
        ]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'API Error: ' . $e->getMessage()]);
    }
}

public function submitContactEnquiry(Request $request)
{
    $request->validate([
        'name'             => 'required|string|max:255',
        'phone'            => 'required|string|max:50',
        'email'            => 'nullable|email|max:255',
        'service_required' => 'nullable|string|max:255',
        'message'          => 'nullable|string',
    ]);

    $enquiry = \App\Models\ContactEnquiry::create([
        'name'             => $request->name,
        'phone'            => $request->phone,
        'email'            => $request->email,
        'service_required' => $request->service_required,
        'message'          => $request->message,
    ]);

    try {
        \Illuminate\Support\Facades\Mail::send('emails.contact_enquiry', [
            'enquiry' => $enquiry,
        ], function ($message) use ($request) {
            $toEmail = config('mail.enquiry_to', env('ENQUIRY_TO_EMAIL', 'dhanapalg93@gmail.com'));
            $message->to($toEmail)
                    ->subject('New Contact Enquiry from ' . $request->name . ' (' . ($request->service_required ?? 'General') . ')');
        });
    } catch (\Throwable $e) {
        \Illuminate\Support\Facades\Log::error('Contact Enquiry Mail Error: ' . $e->getMessage());
    }

    return back()->with('success', 'Thank you! Your message has been received. Our team will contact you shortly.');
}

public function submitQuickEnquiry(Request $request)
{
    $request->validate([
        'name'             => 'required|string|max:255',
        'amount'           => 'required',
        'phone'            => 'required|string|max:50',
        'service_required' => 'required|string|max:255',
    ]);

    $enquiry = \App\Models\ContactEnquiry::create([
        'name'             => $request->name,
        'phone'            => $request->phone,
        'email'            => null,
        'service_required' => $request->service_required,
        'message'          => 'Customer: ' . $request->name . ' | Requirement Amount: ₹' . number_format((float)str_replace(',', '', $request->amount), 2),
    ]);

    try {
        \Illuminate\Support\Facades\Mail::send('emails.quick_enquiry', [
            'enquiry' => $enquiry,
            'name'    => $request->name,
            'amount'  => $request->amount,
            'phone'   => $request->phone,
            'service' => $request->service_required,
        ], function ($message) use ($request) {
            $toEmail = config('mail.enquiry_to', env('ENQUIRY_TO_EMAIL', 'dhanapalg93@gmail.com'));
            $message->to($toEmail)
                    ->subject('New Quick Enquiry: ' . $request->name . ' - ' . $request->service_required . ' - ' . $request->phone);
        });
    } catch (\Throwable $e) {
        \Illuminate\Support\Facades\Log::error('Quick Enquiry Mail Error: ' . $e->getMessage());
    }

    return back()->with('quick_success', 'Thank you! Your requirement has been submitted. Our team will contact you shortly.');
}

public function contactEnquiriesList()
{
    $enquiries = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15);
    $totalEnquiries = 0;
    $totalCustomers = 0;

    try {
        if (\Illuminate\Support\Facades\Schema::hasTable('contact_enquiries')) {
            $enquiries = \App\Models\ContactEnquiry::latest()->paginate(15);
            $totalEnquiries = \App\Models\ContactEnquiry::count();
        }
    } catch (\Throwable $e) {
        \Illuminate\Support\Facades\Log::warning('Contact enquiries list error: ' . $e->getMessage());
    }

    try {
        if (\Illuminate\Support\Facades\Schema::hasTable('k_y_c_s')) {
            $totalCustomers = \App\Models\KYC::count();
        }
    } catch (\Throwable $e) {
        // ignore
    }

    $isLive = AppConfig::isLiveMode();
    $aadhaarHits = AppConfig::getAadhaarHits();
    $panHits = AppConfig::getPanHits();

    return view('contact_enquiries', compact(
        'enquiries',
        'totalEnquiries',
        'totalCustomers',
        'isLive',
        'aadhaarHits',
        'panHits'
    ));
}

public function destroyContactEnquiry($id)
{
    $enquiry = \App\Models\ContactEnquiry::findOrFail($id);
    $enquiry->delete();
    return back()->with('success', 'Contact enquiry deleted successfully.');
}

}
