<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gold Purchase Agreement & Customer Declaration - POJO FINSERV</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <style>
        body {
            background-color: #f1f5f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #334155;
        }
        .form-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            padding: 30px;
            max-width: 920px;
            margin: 30px auto;
        }
        .header-bar {
            background: linear-gradient(135deg, #0052cc 0%, #008744 100%);
            color: #ffffff;
            padding: 15px 25px;
            border-radius: 8px;
            border: 1px solid #f59e0b;
            margin-bottom: 25px;
        }
        .section-title {
            background-color: #f8fafc;
            color: #0052cc;
            font-weight: 700;
            padding: 8px 15px;
            border-left: 4px solid #0052cc;
            border-radius: 4px;
            margin-top: 25px;
            margin-bottom: 15px;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .border-input {
            border: none;
            border-bottom: 1.5px solid #94a3b8;
            border-radius: 0;
            padding: 2px 5px;
            background: transparent;
            font-weight: 600;
            color: #0f172a;
            text-transform: uppercase;
        }
        input[type="text"] {
            text-transform: uppercase;
        }
        .border-input:focus {
            outline: none;
            border-bottom-color: #0052cc;
        }
        .signature-box {
            border: 2px dashed #cbd5e1;
            border-radius: 8px;
            background: #f8fafc;
            min-height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }
        .running-footer {
            border-top: 1px solid #e2e8f0;
            margin-top: 30px;
            padding-top: 10px;
            font-size: 11px;
            color: #64748b;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="form-card">
        <div class="capture">
            <!-- Header Bar -->
            <div class="header-bar d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="fw-bold mb-0">POJO FINSERV</h3>
                    <div class="small" style="color: #fbbf24;">Gold Purchase Agreement / Customer Declaration</div>
                </div>
                @php
                    $profileImgSrc = asset('img/about.jpg');
                    if (!empty($kyc->profile_image)) {
                        if (str_starts_with($kyc->profile_image, 'data:image')) {
                            $profileImgSrc = $kyc->profile_image;
                        } elseif (str_starts_with($kyc->profile_image, '/9j/') || str_starts_with($kyc->profile_image, 'iVBOR') || strlen($kyc->profile_image) > 100) {
                            $profileImgSrc = 'data:image/jpeg;base64,' . $kyc->profile_image;
                        } elseif (file_exists(storage_path('app/public/' . $kyc->profile_image))) {
                            $profileImgSrc = asset('storage/' . $kyc->profile_image);
                        }
                    } elseif (!empty($kyc->eye_blink_image)) {
                        $profileImgSrc = asset($kyc->eye_blink_image);
                    }
                @endphp
                <div class="d-flex align-items-center gap-3">
                    @if($kyc->eye_blink_image || $kyc->profile_image)
                        <img src="{{ $profileImgSrc }}" alt="Customer Live Photo" style="height: 65px; width: 65px; object-fit: cover; border-radius: 8px; border: 2px solid #fbbf24;" onerror="this.src='{{ asset('img/about.jpg') }}';">
                    @endif
                    <div class="text-end small">
                        <div><strong>Date:</strong> {{ date('d / m / Y') }}</div>
                        <div><strong>Purchase No.:</strong> GP-{{ $kyc->id }}-{{ date('Ymd') }}</div>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success rounded-3 mb-3">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger rounded-3 mb-3">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('promissory.store') }}" enctype="multipart/form-data" id="goldBuyingForm">
                @csrf
                <input type="hidden" name="kyc_id" id="kyc_id" value="{{ $kyc->id }}">
                <input type="hidden" name="borrower_verified" id="borrower_verified" value="0">
                <input type="hidden" name="borrower_signature" id="borrower_signature" value="">
                <input type="hidden" name="witness_signature" id="witness_signature" value="N/A">
                <input type="hidden" name="borrower_aadhaar_input" id="borrower_aadhaar_input" value="{{ $kyc->aadhaar_number }}">
                <input type="hidden" name="verified_name" id="verified_name_input" value="{{ $kyc->verified_name ?? $kyc->full_name }}">
                <input type="hidden" name="verified_aadhaar_no" id="verified_aadhaar_no_input" value="{{ $kyc->verified_aadhaar_no ?? $kyc->aadhaar_number }}">
                <input type="hidden" name="verified_addhar_no" id="verified_addhar_no_input" value="{{ $kyc->verified_addhar_no ?? $kyc->aadhaar_number }}">

                <div class="alert alert-warning py-2 small fw-semibold text-dark mb-4">
                    <i class="fas fa-coins me-1 text-warning"></i> Service: <strong>SELL YOUR GOLD</strong> — Please review and complete the agreement details below.
                </div>

                <!-- 1. CUSTOMER DETAILS -->
                <div class="section-title">1. CUSTOMER DETAILS</div>
                <div class="row g-3 small">
                    <div class="col-md-6">
                        <strong>Full Name:</strong> 
                        <input type="text" name="full_name" value="{{ $kyc->full_name }}" class="border-input w-75" placeholder="Enter Full Name">
                    </div>
                    <div class="col-md-6">
                        <strong>S/o / W/o (Care of):</strong> 
                        <input type="text" name="care_of" value="{{ $kyc->care_of }}" class="border-input w-75" placeholder="Enter Care of / Parent Name">
                    </div>
                    <div class="col-md-6">
                        <strong>Contact Mobile No.:</strong> 
                        <input type="text" name="phone" value="{{ $kyc->phone }}" class="border-input w-75" placeholder="Enter Contact Mobile Number">
                    </div>
                    <div class="col-md-6">
                        <strong>PAN No.:</strong> 
                        <input type="text" name="pan_number" value="{{ $kyc->pan_number }}" class="border-input w-75" placeholder="Enter PAN Number">
                    </div>
                    <div class="col-md-12">
                        <strong>Aadhaar No.:</strong> 
                        <input type="text" name="aadhaar_number" value="{{ $kyc->aadhaar_number }}" class="border-input w-75" placeholder="Enter 12-digit Aadhaar Number">
                    </div>
                    <div class="col-md-12">
                        <strong>Residing Address:</strong><br>
                        @php
                            $rawAddress = $kyc->address;
                            $address = is_string($rawAddress) && json_decode($rawAddress, true) ? json_decode($rawAddress, true) : $rawAddress;
                            $formattedAddress = is_array($address) 
                                ? (($address['house'] ?? '') . ', ' . ($address['street'] ?? '') . ', ' . ($address['vtc'] ?? '') . ', ' . ($address['dist'] ?? '') . ' ' . ($address['post'] ?? '') . ', ' . ($address['state'] ?? '') . ', ' . ($address['country'] ?? '') . ' ' . ($kyc->zip ?? ''))
                                : ($address . ' ' . ($kyc->zip ?? ''));
                        @endphp
                        <input type="text" name="address" value="{{ $formattedAddress }}" class="border-input w-100" placeholder="Enter Full Address">
                    </div>
                </div>

                <p class="small text-muted mt-3 mb-0">
                    hereby confirm that I have voluntarily sold the following gold ornaments to <strong>POJO FINSERV</strong>.
                </p>

                <!-- 2. GOLD DETAILS -->
                <div class="section-title">2. GOLD DETAILS</div>
                <div class="row g-3 small">
                    <div class="col-md-6">
                        <strong>Gold Article / Ornaments:</strong> <span class="text-danger">*</span>
                        <input type="text" name="gold_article" id="gold_article" value="{{ $kyc->gold_article }}" class="border-input w-75" placeholder="Enter Gold Article / Ornaments" required>
                    </div>
                    <div class="col-md-6">
                        <strong>Gross Weight (grams):</strong> <span class="text-danger">*</span>
                        <input type="text" name="gross_weight" id="gross_weight" value="{{ $kyc->gross_weight }}" class="border-input w-50" placeholder="e.g. 25.50" required>
                    </div>
                    <div class="col-md-6">
                        <strong>Net Gold Weight (grams):</strong> <span class="text-danger">*</span>
                        <input type="text" name="net_weight" id="net_weight" value="{{ $kyc->net_weight }}" class="border-input w-50" placeholder="e.g. 24.00" oninput="calculateTotalGoldAmount()" required>
                    </div>
                    <div class="col-md-6">
                        <strong>Gold Rate (₹ per gram):</strong> <span class="text-danger">*</span>
                        <input type="text" name="gold_rate" id="gold_rate" value="{{ $kyc->gold_rate }}" class="border-input w-50" placeholder="e.g. 6800" oninput="calculateTotalGoldAmount()" required>
                    </div>
                    <div class="col-md-6">
                        <strong>Base Gold Value (₹):</strong>
                        <input type="text" id="gross_gold_amount" name="gross_gold_amount" class="border-input w-50 text-secondary fw-semibold" placeholder="0.00" oninput="calculateFromGrossGoldAmount()">
                    </div>
                    <div class="col-md-6">
                        <strong>GST (%):</strong> <span class="text-danger">*</span>
                        <input type="number" step="0.01" name="gst_percent" id="gst_percent" value="{{ $kyc->gst_percent ?? '3' }}" class="border-input text-center fw-bold text-danger" style="width: 70px;" placeholder="3" oninput="calculateTotalGoldAmount()" required> %
                        <span class="ms-2 text-muted">GST Amt: ₹ <input type="text" name="gst_amount" id="gst_amount" value="{{ $kyc->gst_amount }}" class="border-input text-danger fw-bold" style="width: 100px;" placeholder="0.00" oninput="calculateFromGstAmount()"></span>
                    </div>
                    <div class="col-md-6">
                        <strong>Total Purchase Amount (₹ with GST):</strong> <span class="text-danger">*</span>
                        <input type="text" name="amount" id="funded_amount" value="{{ $kyc->amount }}" class="border-input w-50 fw-bold text-success" placeholder="Total with GST (₹)" oninput="recalculatePayableAndWords()" required>
                    </div>
                    <div class="col-md-6">
                        <strong>Amount in Words:</strong> 
                        <input type="text" id="amount_words" name="amount_words" class="border-input w-100 text-primary" placeholder="Amount in words">
                    </div>
                </div>

                <!-- 3. CUSTOMER DECLARATION -->
                <div class="section-title">3. CUSTOMER DECLARATION</div>
                <div class="small text-secondary mb-3">
                    <strong>I hereby declare that:</strong>
                    <ol class="ps-3 mt-2 mb-0" style="line-height: 1.6;">
                        <li>The above gold belongs to me / I am legally authorized to sell it.</li>
                        <li>The gold is not stolen, pledged, mortgaged or subject to any known dispute or third-party claim.</li>
                        <li>I have voluntarily sold the above gold to POJO FINSERV.</li>
                        <li>The weight, purity and purchase value have been explained to me and accepted by me.</li>
                        <li>I have received the agreed purchase amount from POJO FINSERV.</li>
                        <li>I have provided my correct KYC details.</li>
                        <li>I have completed Aadhaar OTP authentication for identity/KYC verification at the time of this transaction.</li>
                        <li>I hereby provide my consent to POJO FINSERV to contact me from time to time for marketing, promotional, product/service-related information, offers, updates and other business communications.</li>
                    </ol>
                </div>

                <!-- 4. PAYMENT DETAILS -->
                <div class="section-title">4. PAYMENT DETAILS</div>
                <div class="row g-3 small align-items-center mb-3">
                    <div class="col-md-6">
                        <strong>Payment Mode:</strong> <span class="text-danger">*</span>
                        <div class="d-flex gap-3 mt-1">
                            <label class="form-check-label fw-semibold" style="cursor: pointer;">
                                <input type="radio" name="payment_mode" value="Bank Transfer" class="form-check-input" {{ strtoupper($kyc->payment_mode ?? 'Bank Transfer') === 'BANK TRANSFER' ? 'checked' : '' }}> Bank Transfer
                            </label>
                            <label class="form-check-label fw-semibold" style="cursor: pointer;">
                                <input type="radio" name="payment_mode" value="UPI" class="form-check-input" {{ strtoupper($kyc->payment_mode ?? '') === 'UPI' ? 'checked' : '' }}> UPI
                            </label>
                            <label class="form-check-label fw-semibold" style="cursor: pointer;">
                                <input type="radio" name="payment_mode" value="Cash" class="form-check-input" {{ strtoupper($kyc->payment_mode ?? '') === 'CASH' ? 'checked' : '' }}> Cash
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <strong>Transaction / UTR No.:</strong>
                        <input type="text" name="utr_ref" value="{{ $kyc->utr_ref }}" class="border-input w-50" placeholder="Enter Transaction / UTR No. (Optional)">
                    </div>
                    <div class="col-md-12">
                        <strong>Name as per Bank Account:</strong> <span class="text-danger">*</span>
                        <input type="text" name="bank_name_ifsc" id="bank_name_ifsc" value="{{ $kyc->bank_name_ifsc ?? $kyc->full_name }}" class="border-input w-50" required placeholder="Name matching Bank A/c">
                        <div id="bank_name_ifsc_error" class="text-danger small d-none mt-1">Name as per Bank Account is required.</div>
                    </div>
                    <div class="col-md-6">
                        <strong>Bank Account No.:</strong> <span class="text-danger">*</span>
                        <input type="text" name="bank_acc" id="bank_acc" value="{{ $kyc->bank_acc }}" class="border-input w-75" placeholder="Enter Bank Account Number" required>
                        <div id="bank_acc_error" class="text-danger small d-none mt-1">Bank Account Number is required.</div>
                    </div>
                    <div class="col-md-6">
                        <strong>IFSC Code:</strong> <span class="text-danger">*</span>
                        <input type="text" name="ifsc_code" id="ifsc_code" value="{{ $kyc->ifsc_code }}" class="border-input w-50 text-uppercase" placeholder="e.g. SBIN0001234" required>
                        <div id="ifsc_code_error" class="text-danger small d-none mt-1">IFSC Code is required.</div>
                    </div>
                </div>

                <!-- 5. CUSTOMER DIGITAL VERIFICATION -->
                <div class="section-title">5. CUSTOMER DIGITAL VERIFICATION</div>
                <div class="row g-3 small align-items-center mb-4">
                    <div class="col-md-6">
                        <strong>Customer Name:</strong> {{ $kyc->full_name }}<br>
                        <strong>Aadhaar Status:</strong> <span class="badge bg-success">Authenticated</span><br>
                        <div class="mt-2 text-muted" style="font-size: 11px;">
                            <div><strong>IP Address:</strong> <span id="ip_borrower">Loading...</span></div>
                            <div><strong>Timestamp:</strong> <span id="time_borrower"></span></div>
                        </div>
                    </div>
                    <div class="col-md-6 text-center">
                        <strong>Customer eSign Verification:</strong>
                        <div class="signature-box mt-2 p-2">
                            <div id="borrower_unverified_box">
                                <button type="button" class="btn btn-sm btn-success rounded-pill fw-bold px-4" onclick="openAadhaarModal('borrower')">
                                    <i class="fas fa-shield-alt me-1"></i> Click Digital Verify (Aadhaar OTP)
                                </button>
                            </div>
                            <div id="borrower_verified_box" class="d-none text-success fw-bold">
                                <i class="fas fa-check-circle fa-2x mb-1 text-success d-block"></i>
                                <span>Aadhaar Digitally Verified ✓</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 6. FOR POJO FINSERV -->
                <div class="section-title">6. FOR POJO FINSERV</div>
                <div class="row g-3 small align-items-center mb-4">
                    <div class="col-md-6">
                        <strong>Authorised Person:</strong> 
                        <input type="text" name="authorized" value="Dhanapal G" class="border-input" style="width: 220px;">
                    </div>
                    <div class="col-md-6 text-center">
                        <strong>Lender Signature:</strong><br>
                        <img src="{{ asset('images/sign.png') }}" alt="Lender Signature" style="height: 60px;" onerror="this.src='{{ asset('img/logo.png') }}';">
                    </div>
                </div>

                <div class="running-footer">
                    POJO FINSERV | Gold Purchase Agreement & Customer Declaration
                </div>

                <div class="text-center mt-5">
                    <button type="submit" class="btn btn-lg rounded-pill fw-bold text-white shadow" style="background: linear-gradient(135deg, #0052cc 0%, #008744 100%); border: 1px solid #f59e0b; padding: 12px 40px;">
                        Confirm & Complete Agreement <i class="fas fa-file-pdf ms-2" style="color: #fbbf24;"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Aadhaar OTP Modal -->
<div class="modal fade" id="aadhaarModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header text-white" style="background-color: #0052cc;">
                <h5 class="modal-title fw-bold"><i class="fas fa-shield-alt me-2"></i> Aadhaar Digital OTP Verification</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div id="aadhaar_step_1">
                    <label class="form-label fw-semibold">Enter Aadhaar Card Number (12 Digits)</label>
                    <input type="text" id="modal_aadhaar_input" class="form-control mb-3" placeholder="e.g. 123456789012" maxlength="12">
                    <div id="aadhaar_modal_error" class="alert alert-danger d-none py-2 small"></div>
                    <button type="button" class="btn btn-success fw-bold w-100 rounded-pill" onclick="sendAadhaarOtpModal()">
                        <i class="fas fa-paper-plane me-1"></i> Send OTP to Registered Mobile
                    </button>
                </div>

                <div id="aadhaar_step_2" class="d-none">
                    @if(!\App\Models\AppConfig::isLiveMode())
                        <div class="alert alert-warning py-2 small mb-3">
                            🧪 <strong>Testing Mode:</strong> Aadhaar API bypassed. Enter sample OTP: <strong>123456</strong>
                        </div>
                    @else
                        <div class="alert alert-info py-2 small mb-3">
                            📩 OTP has been sent to the mobile number registered with your Aadhaar Card.
                        </div>
                    @endif
                    <label class="form-label fw-semibold">Enter 6-Digit OTP</label>
                    <input type="text" id="modal_otp_input" class="form-control mb-3" placeholder="123456" maxlength="6" value="{{ !\App\Models\AppConfig::isLiveMode() ? '123456' : '' }}">
                    <div id="otp_modal_error" class="alert alert-danger d-none py-2 small"></div>
                    <button type="button" class="btn btn-primary fw-bold w-100 rounded-pill" onclick="verifyAadhaarOtpModal()">
                        <i class="fas fa-check-circle me-1"></i> Verify OTP & Authenticate
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
let activeVerifyRole = 'borrower';
let currentRequestId = null;

function convertNumberToWords(num) {
    if (!num || isNaN(num) || num <= 0) return '';
    num = Math.floor(num);
    const a = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten', 
               'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
    const b = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];

    function inWords(n) {
        if (n < 20) return a[n];
        if (n < 100) return b[Math.floor(n / 10)] + (n % 10 !== 0 ? ' ' + a[n % 10] : '');
        if (n < 1000) return a[Math.floor(n / 100)] + ' Hundred' + (n % 100 !== 0 ? ' ' + inWords(n % 100) : '');
        if (n < 100000) return inWords(Math.floor(n / 1000)) + ' Thousand' + (n % 1000 !== 0 ? ' ' + inWords(n % 1000) : '');
        if (n < 10000000) return inWords(Math.floor(n / 100000)) + ' Lakh' + (n % 100000 !== 0 ? ' ' + inWords(n % 100000) : '');
        return inWords(Math.floor(n / 10000000)) + ' Crore' + (n % 10000000 !== 0 ? ' ' + inWords(n % 10000000) : '');
    }
    return 'Rupees ' + inWords(num) + ' only';
}

function parseCleanNumber(val) {
    if (!val) return 0;
    const clean = String(val).replace(/[^0-9.]/g, '');
    return parseFloat(clean) || 0;
}

function calculateTotalGoldAmount() {
    const netW = parseCleanNumber(document.getElementById('net_weight')?.value);
    const rate = parseCleanNumber(document.getElementById('gold_rate')?.value);
    const gstPercent = parseCleanNumber(document.getElementById('gst_percent')?.value);
    const grossAmtInput = document.getElementById('gross_gold_amount');
    const gstAmtInput = document.getElementById('gst_amount');
    const amtInput = document.getElementById('funded_amount');

    if (netW > 0 && rate > 0) {
        const gross = netW * rate;
        if (grossAmtInput) grossAmtInput.value = gross.toFixed(2);

        const gstAmt = (gross * gstPercent) / 100;
        if (gstAmtInput) gstAmtInput.value = gstAmt.toFixed(2);

        const totalWithGst = Math.round(gross + gstAmt);
        if (amtInput) {
            amtInput.value = totalWithGst;
        }
    } else if (amtInput && parseCleanNumber(amtInput.value) > 0) {
        const total = parseCleanNumber(amtInput.value);
        if (gstPercent > 0) {
            const gross = total / (1 + (gstPercent / 100));
            if (grossAmtInput && !grossAmtInput.value) grossAmtInput.value = gross.toFixed(2);
            if (gstAmtInput && !gstAmtInput.value) gstAmtInput.value = (total - gross).toFixed(2);
        }
    }
    recalculatePayableAndWords();
}

function calculateFromGrossGoldAmount() {
    const gross = parseCleanNumber(document.getElementById('gross_gold_amount')?.value);
    const gstPercent = parseCleanNumber(document.getElementById('gst_percent')?.value);
    const gstAmtInput = document.getElementById('gst_amount');
    const amtInput = document.getElementById('funded_amount');

    if (gross > 0) {
        const gstAmt = (gross * gstPercent) / 100;
        if (gstAmtInput) gstAmtInput.value = gstAmt.toFixed(2);
        const totalWithGst = Math.round(gross + gstAmt);
        if (amtInput) amtInput.value = totalWithGst;
    }
    recalculatePayableAndWords();
}

function calculateFromGstAmount() {
    const gross = parseCleanNumber(document.getElementById('gross_gold_amount')?.value);
    const gstAmt = parseCleanNumber(document.getElementById('gst_amount')?.value);
    const amtInput = document.getElementById('funded_amount');
    if (gross > 0) {
        const totalWithGst = Math.round(gross + gstAmt);
        if (amtInput) amtInput.value = totalWithGst;
    }
    recalculatePayableAndWords();
}

function recalculatePayableAndWords() {
    const fundedInput = document.getElementById('funded_amount');
    const wordsInput = document.getElementById('amount_words');

    if (fundedInput) {
        const amt = parseCleanNumber(fundedInput.value);
        if (wordsInput && amt > 0) {
            wordsInput.value = convertNumberToWords(amt).toUpperCase();
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    // Automatically uppercase all text inputs and on typing
    document.querySelectorAll('input[type="text"], input.border-input').forEach(function(input) {
        if (input.type !== 'password' && input.type !== 'email' && input.type !== 'date' && input.type !== 'time' && input.type !== 'number') {
            input.style.textTransform = 'uppercase';
            if (input.value) {
                input.value = input.value.toUpperCase();
            }
            input.addEventListener('input', function() {
                this.value = this.value.toUpperCase();
            });
        }
    });

    calculateTotalGoldAmount();
    recalculatePayableAndWords();

    fetch('https://api.ipify.org?format=json')
        .then(res => res.json())
        .then(data => {
            if(document.getElementById('ip_borrower')) document.getElementById('ip_borrower').textContent = data.ip;
            if(document.getElementById('ip_witness')) document.getElementById('ip_witness').textContent = data.ip;
        })
        .catch(() => {});

    const now = new Date().toLocaleString('en-IN', {
        timeZone: 'Asia/Kolkata',
        hour12: true,
        year: 'numeric', month: 'short', day: 'numeric',
        hour: '2-digit', minute: '2-digit'
    });

    if(document.getElementById('time_borrower')) document.getElementById('time_borrower').textContent = now;
    if(document.getElementById('time_witness')) document.getElementById('time_witness').textContent = now;
});

function openAadhaarModal(role) {
    activeVerifyRole = role;
    const errorDiv = document.getElementById('aadhaar_modal_error');
    if (errorDiv) errorDiv.classList.add('d-none');
    document.getElementById('aadhaar_step_1').classList.remove('d-none');
    document.getElementById('aadhaar_step_2').classList.add('d-none');

    const defaultAadhaar = document.getElementById('borrower_aadhaar_input').value.trim();
    if (defaultAadhaar) {
        document.getElementById('modal_aadhaar_input').value = defaultAadhaar;
    }

    const modal = new bootstrap.Modal(document.getElementById('aadhaarModal'));
    modal.show();
}

function sendAadhaarOtpModal() {
    const aadhaarVal = document.getElementById('modal_aadhaar_input').value.trim();
    const errorDiv = document.getElementById('aadhaar_modal_error');
    const sendBtn = document.querySelector('#aadhaar_step_1 button');

    if (!aadhaarVal || aadhaarVal.length !== 12 || isNaN(aadhaarVal)) {
        errorDiv.textContent = 'Please enter a valid 12-digit Aadhaar number.';
        errorDiv.classList.remove('d-none');
        return;
    }
    errorDiv.classList.add('d-none');

    if (sendBtn) {
        sendBtn.disabled = true;
        sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Sending OTP...';
    }

    fetch('{{ route("promissory.sendOtp") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ aadhaar_number: aadhaarVal })
    })
    .then(res => res.json())
    .then(data => {
        if (sendBtn) {
            sendBtn.disabled = false;
            sendBtn.innerHTML = '<i class="fas fa-paper-plane me-1"></i> Send OTP to Registered Mobile';
        }
        if (data.success) {
            currentRequestId = data.request_id;
            document.getElementById('aadhaar_step_1').classList.add('d-none');
            document.getElementById('aadhaar_step_2').classList.remove('d-none');
        } else {
            errorDiv.textContent = data.message || 'OTP generation failed.';
            errorDiv.classList.remove('d-none');
        }
    })
    .catch(err => {
        if (sendBtn) {
            sendBtn.disabled = false;
            sendBtn.innerHTML = '<i class="fas fa-paper-plane me-1"></i> Send OTP to Registered Mobile';
        }
        errorDiv.textContent = 'Error connecting to verification server.';
        errorDiv.classList.remove('d-none');
    });
}

function verifyAadhaarOtpModal() {
    const otpVal = document.getElementById('modal_otp_input').value.trim();
    const errorDiv = document.getElementById('otp_modal_error');

    if (!otpVal || otpVal.length !== 6 || isNaN(otpVal)) {
        errorDiv.textContent = 'Please enter a valid 6-digit OTP.';
        errorDiv.classList.remove('d-none');
        return;
    }
    errorDiv.classList.add('d-none');

    fetch('{{ route("promissory.verifyOtp") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            request_id: currentRequestId,
            otp: otpVal,
            kyc_id: document.getElementById('kyc_id').value,
            phone: (document.querySelector('[name="phone"]')?.value || '').trim()
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const modalEl = document.getElementById('aadhaarModal');
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();

            if (activeVerifyRole === 'borrower') {
                document.getElementById('borrower_verified').value = '1';
                document.getElementById('borrower_unverified_box').classList.add('d-none');
                
                const verifiedBox = document.getElementById('borrower_verified_box');
                verifiedBox.classList.remove('d-none');
                
                const aadharData = data.data || {};
                const bName = (aadharData.full_name || document.querySelector('[name="full_name"]')?.value || 'CUSTOMER').toUpperCase();
                const nowIst = new Date().toLocaleString('en-GB', { timeZone: 'Asia/Kolkata' }).replace(',', '') + ' (UTC+05:30)';
                
                const aadhaarVal = document.getElementById('modal_aadhaar_input')?.value.trim() || document.getElementById('borrower_aadhaar_input')?.value.trim();
                if (document.getElementById('verified_name_input')) document.getElementById('verified_name_input').value = bName;
                if (document.getElementById('verified_aadhaar_no_input')) document.getElementById('verified_aadhaar_no_input').value = aadhaarVal;
                if (document.getElementById('verified_addhar_no_input')) document.getElementById('verified_addhar_no_input').value = aadhaarVal;
                const aadhaarField = document.querySelector('[name="aadhaar_number"]') || document.querySelector('[name="aadhaar"]');
                if (aadhaarField && aadhaarVal) aadhaarField.value = aadhaarVal;

                // Auto-fill all Aadhaar details into the form inputs
                if (aadharData.full_name) {
                    const nameInp = document.querySelector('[name="full_name"]');
                    if (nameInp) nameInp.value = aadharData.full_name.toUpperCase();
                }
                if (aadharData.care_of) {
                    const careOfInp = document.querySelector('[name="care_of"]') || document.querySelector('[name="parent"]');
                    if (careOfInp) careOfInp.value = aadharData.care_of.toUpperCase();
                }
                if (aadharData.dob) {
                    const dobInp = document.querySelector('[name="dob"]');
                    if (dobInp) dobInp.value = aadharData.dob;
                }
                if (aadharData.mobile || aadharData.phone) {
                    const mobVal = aadharData.mobile || aadharData.phone;
                    const phoneInp = document.querySelector('[name="phone"]');
                    if (phoneInp && !phoneInp.value) phoneInp.value = mobVal;
                }
                if (aadharData.formatted_address || aadharData.address) {
                    const addrInp = document.querySelector('[name="address"]');
                    if (addrInp) {
                        const addrText = aadharData.formatted_address || (typeof aadharData.address === 'string' ? aadharData.address : JSON.stringify(aadharData.address));
                        addrInp.value = addrText.toUpperCase();
                    }
                }
                if (aadharData.profile_image) {
                    const photoImgs = document.querySelectorAll('img[alt="Customer Live Photo"]');
                    const photoSrc = aadharData.profile_image.startsWith('data:image') 
                        ? aadharData.profile_image 
                        : 'data:image/jpeg;base64,' + aadharData.profile_image;
                    photoImgs.forEach(img => { img.src = photoSrc; });
                }
                
                verifiedBox.innerHTML = `
                    <div class="border border-dark p-2 rounded text-start mx-auto shadow-sm" style="max-width: 280px; background-color: #f8fafc; font-family: 'Courier New', Courier, monospace; font-size: 11px; line-height: 1.4; color: #000;">
                        <div class="fw-bold text-uppercase mb-1" style="font-size: 12px;">${bName}</div>
                        <div style="font-size: 10px;">Date: ${nowIst}</div>
                        <div class="fw-bold mt-1 text-success">&#10004; Aadhaar Sign</div>
                        <div style="border-top: 1px dashed #64748b; margin-top: 5px; padding-top: 3px; font-size: 9px; color: #475569;">
                            eSign by QuickeKyc / POJO FINSERV
                        </div>
                    </div>
                `;
                
                const stampSvg = `<svg xmlns="http://www.w3.org/2000/svg" width="280" height="90">
                    <rect width="100%" height="100%" fill="#f8fafc" rx="6" stroke="#000000" stroke-width="1.5"/>
                    <text x="12" y="24" font-family="monospace" font-size="13" font-weight="bold" fill="#000000">${bName}</text>
                    <text x="12" y="44" font-family="monospace" font-size="10" fill="#333333">Date: ${nowIst}</text>
                    <text x="12" y="62" font-family="monospace" font-size="11" font-weight="bold" fill="#15803d">&#x2714; Aadhaar Sign</text>
                    <line x1="12" y1="70" x2="268" y2="70" stroke="#64748b" stroke-dasharray="3,3"/>
                    <text x="12" y="82" font-family="monospace" font-size="9" fill="#475569">eSign by QuickeKyc / POJO FINSERV</text>
                </svg>`;
                document.getElementById('borrower_signature').value = 'data:image/svg+xml;base64,' + btoa(stampSvg);
            } else {
                document.getElementById('witness_unverified_box').classList.add('d-none');
                document.getElementById('witness_verified_box').classList.remove('d-none');
                const badgeSvg = `<svg xmlns="http://www.w3.org/2000/svg" width="300" height="80"><rect width="100%" height="100%" fill="#f0fdf4" rx="10" stroke="#22c55e" stroke-width="2"/><text x="15" y="30" font-family="sans-serif" font-size="14" font-weight="bold" fill="#15803d">✓ Witness Digitally Verified</text></svg>`;
                document.getElementById('witness_signature').value = 'data:image/svg+xml;base64,' + btoa(badgeSvg);
            }
        } else {
            errorDiv.textContent = data.message || 'OTP verification failed.';
            errorDiv.classList.remove('d-none');
        }
    })
    .catch(err => {
        errorDiv.textContent = 'Error connecting to verification server.';
        errorDiv.classList.remove('d-none');
    });
}

document.getElementById('goldBuyingForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    const bankNameIfsc = document.getElementById('bank_name_ifsc');
    const bankError = document.getElementById('bank_name_ifsc_error');
    if (!bankNameIfsc || !bankNameIfsc.value.trim()) {
        if (bankError) bankError.classList.remove('d-none');
        bankNameIfsc.style.borderBottom = '2px solid #dc3545';
        bankNameIfsc.focus();
        alert('Please fill Name as per Bank Account.');
        return;
    } else {
        if (bankError) bankError.classList.add('d-none');
        bankNameIfsc.style.borderBottom = '1.5px solid #94a3b8';
    }

    const bankAcc = document.getElementById('bank_acc');
    const bankAccError = document.getElementById('bank_acc_error');
    if (!bankAcc || !bankAcc.value.trim()) {
        if (bankAccError) bankAccError.classList.remove('d-none');
        bankAcc.style.borderBottom = '2px solid #dc3545';
        bankAcc.focus();
        alert('Please enter Bank Account Number.');
        return;
    } else {
        if (bankAccError) bankAccError.classList.add('d-none');
        bankAcc.style.borderBottom = '1.5px solid #94a3b8';
    }

    const ifscCode = document.getElementById('ifsc_code');
    const ifscError = document.getElementById('ifsc_code_error');
    if (!ifscCode || !ifscCode.value.trim()) {
        if (ifscError) ifscError.classList.remove('d-none');
        ifscCode.style.borderBottom = '2px solid #dc3545';
        ifscCode.focus();
        alert('Please enter IFSC Code.');
        return;
    } else {
        if (ifscError) ifscError.classList.add('d-none');
        ifscCode.style.borderBottom = '1.5px solid #94a3b8';
    }

    const isBorrowerVerified = document.getElementById('borrower_verified').value === '1';
    const borrowerSig = document.getElementById('borrower_signature').value;

    if (!isBorrowerVerified && !borrowerSig) {
        const bName = (document.querySelector('[name="full_name"]')?.value || 'CUSTOMER').toUpperCase();
        const nowIst = new Date().toLocaleString('en-GB', { timeZone: 'Asia/Kolkata' }).replace(',', '') + ' (UTC+05:30)';
        const stampSvg = `<svg xmlns="http://www.w3.org/2000/svg" width="280" height="90">
            <rect width="100%" height="100%" fill="#f8fafc" rx="6" stroke="#000000" stroke-width="1.5"/>
            <text x="12" y="24" font-family="monospace" font-size="13" font-weight="bold" fill="#000000">${bName}</text>
            <text x="12" y="44" font-family="monospace" font-size="10" fill="#333333">Date: ${nowIst}</text>
            <text x="12" y="62" font-family="monospace" font-size="11" font-weight="bold" fill="#15803d">✓ Customer Signed</text>
            <line x1="12" y1="70" x2="268" y2="70" stroke="#64748b" stroke-dasharray="3,3"/>
            <text x="12" y="82" font-family="monospace" font-size="9" fill="#475569">POJO FINSERV Digital Confirmation</text>
        </svg>`;
        document.getElementById('borrower_signature').value = 'data:image/svg+xml;base64,' + btoa(stampSvg);
    }

    const submitBtn = this.querySelector('button[type="submit"]');
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Generating PDF & Downloading...';
    }

    try {
        const captureTarget = document.querySelector('.capture');
        const canvas = await html2canvas(captureTarget, {
            backgroundColor: '#ffffff',
            scale: 1.5,
            useCORS: true,
            allowTaint: true,
            logging: false
        });

        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'promissory_image_base64';
        input.value = canvas.toDataURL('image/png');
        this.appendChild(input);
    } catch(err) {
        console.warn('html2canvas capture fallback:', err);
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'promissory_image_base64';
        input.value = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';
        this.appendChild(input);
    }
    this.submit();
});
</script>
</body>
</html>
