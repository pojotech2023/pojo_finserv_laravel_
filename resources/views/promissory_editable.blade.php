<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UNSECURED LOAN PROMISSORY NOTE & SAME-DAY FUNDING AGREEMENT - POJO FINSERV</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.6/dist/signature_pad.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <style>
        body {
            background-color: #f8fafc !important;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #1e293b;
        }

        .form-wrapper {
            max-width: 950px;
            margin: 30px auto;
            padding: 40px;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            border: 1px solid #e2e8f0;
        }

        .border-input {
            border: none;
            border-bottom: 1.5px solid #94a3b8;
            background: transparent;
            padding: 2px 8px;
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

        .header-logo {
            max-height: 85px;
            width: auto;
        }

        .section-title {
            font-size: 15px;
            font-weight: 800;
            color: #0052cc;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 4px;
            margin-top: 24px;
            margin-bottom: 12px;
        }

        .meta-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px;
            font-size: 12px;
        }

        .signature-box {
            min-height: 80px;
            border: 2px dashed #cbd5e1;
            border-radius: 8px;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .signature-preview-img {
            max-height: 70px;
            width: auto;
            display: none;
        }

        .running-footer {
            margin-top: 40px;
            padding-top: 15px;
            border-top: 1px solid #cbd5e1;
            text-align: center;
            font-size: 12px;
            font-weight: 700;
            color: #475569;
        }
    </style>
</head>
<body class="bg-light">

<div class="container py-4">
    <div class="form-wrapper">
        <div class="capture p-3 bg-white">
            <!-- Header Section -->
            <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                <div class="d-flex align-items-center gap-3">
                    <img src="{{ asset('img/logo.png') }}" alt="POJO FINSERV Logo" class="header-logo" onerror="this.src='{{ asset('images/logo.png') }}';">
                    <div>
                        <h4 class="fw-bold mb-0 text-dark" style="color: #0052cc !important;">POJO FINSERV</h4>
                        <div class="fw-bold text-uppercase text-secondary small">UNSECURED LOAN PROMISSORY NOTE & SAME-DAY FUNDING AGREEMENT</div>
                        <div class="text-muted small" style="font-size: 11px;">For Gold Loan Transfer / Takeover Funding</div>
                    </div>
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
                @if($kyc->eye_blink_image || $kyc->profile_image)
                <div class="meta-box text-center">
                    <img src="{{ $profileImgSrc }}" alt="Customer Live Photo" style="height: 85px; width: 85px; object-fit: cover; border-radius: 8px; border: 2px solid #0052cc;" onerror="this.src='{{ asset('img/about.jpg') }}';">
                    <div class="mt-2 text-start" style="font-size: 11px; line-height: 1.3;">
                        <div><strong>IP:</strong> <span id="ip_pto">Loading...</span></div>
                        <div><strong>Date/Time:</strong> <span id="time_pto"></span></div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Agreement Details Header -->
            <div class="row g-2 mb-3 bg-light p-3 rounded-3 border">
                <div class="col-md-6">
                    <strong>Agreement / PN No.:</strong> 
                    <input type="text" name="pn_no" value="PN-{{ $kyc->id }}-{{ date('Ymd') }}" class="border-input" style="width: 180px;">
                </div>
                <div class="col-md-6 text-md-end">
                    <strong>Date:</strong> 
                    <input type="date" name="sign_date" value="{{ date('Y-m-d') }}" class="border-input" style="width: 160px;">
                </div>
                <div class="col-md-6">
                    <strong>Place:</strong> 
                    <input type="text" name="place" value="COIMBATORE" class="border-input" style="width: 180px;">
                </div>
                <div class="col-md-6 text-md-end">
                    <strong>Branch:</strong> 
                    <input type="text" name="branch" value="ERODE" class="border-input" style="width: 160px;">
                </div>
            </div>

            <form method="POST" action="{{ route('promissory.store') }}" enctype="multipart/form-data" id="promissoryForm">
                @csrf
                <input type="hidden" name="kyc_id" id="kyc_id" value="{{ $kyc->id }}">
                <input type="hidden" name="borrower_signature" id="borrower_signature">
                <input type="hidden" name="witness_signature" id="witness_signature">
                <input type="hidden" name="borrower_verified" id="borrower_verified" value="0">
                <input type="hidden" name="verified_name" id="verified_name_input" value="{{ $kyc->verified_name ?? $kyc->full_name }}">
                <input type="hidden" name="verified_aadhaar_no" id="verified_aadhaar_no_input" value="{{ $kyc->verified_aadhaar_no ?? $kyc->aadhaar_number }}">
                <input type="hidden" name="verified_addhar_no" id="verified_addhar_no_input" value="{{ $kyc->verified_addhar_no ?? $kyc->aadhaar_number }}">

                <!-- 1. PARTIES -->
                <div class="section-title">1. PARTIES</div>
                <p class="small">
                    This Agreement and Promissory Note (“Document”) is made between <strong>POJO FINSERV</strong> (“Lender”) and the person identified below (“Borrower”). The Borrower requests temporary unsecured funding solely to facilitate closure/transfer of an existing gold loan and agrees to repay the amount lawfully due in accordance with this Document and the applicable loan terms.
                </p>

                <!-- 2. BORROWER DETAILS -->
                <div class="section-title">2. BORROWER DETAILS</div>
                <div class="row g-2 small mb-3">
                    <div class="col-md-6">
                        <strong>Full Name:</strong> 
                        <input type="text" name="full_name" value="{{ $kyc->full_name }}" class="border-input w-75">
                    </div>
                    <div class="col-md-6">
                        <strong>Father Name:</strong> 
                        <input type="text" name="parent" value="{{ $kyc->care_of }}" class="border-input w-75">
                    </div>
                    <div class="col-md-6">
                        <strong>Date of Birth:</strong> 
                        <input type="text" name="dob" value="{{ $kyc->dob ?? '22/11/2001' }}" class="border-input" style="width: 160px;">
                    </div>
                    <div class="col-md-6">
                        <strong>Contact Mobile:</strong> 
                        <input type="text" name="phone" value="{{ $kyc->phone }}" class="border-input" style="width: 180px;">
                    </div>
                    <div class="col-md-6">
                        <strong>PAN:</strong> 
                        <input type="text" name="pan_number" value="{{ $kyc->pan_number }}" class="border-input" style="width: 180px;">
                    </div>
                    <div class="col-md-6">
                        <strong>Aadhaar / KYC Reference:</strong> 
                        <input type="text" name="aadhaar" id="borrower_aadhaar_input" value="{{ $kyc->aadhaar_number }}" class="border-input" style="width: 200px;">
                    </div>
                    <div class="col-md-6">
                        <strong>Bank A/c No.:</strong> 
                        <input type="text" name="bank_acc" value="{{ old('bank_acc', $kyc->bank_acc ?? '') }}" class="border-input" style="width: 200px;" placeholder="Bank A/c No." required>
                    </div>
                    <div class="col-md-6">
                        <strong>IFSC Code:</strong> 
                        <input type="text" name="ifsc_code" value="{{ old('ifsc_code', $kyc->ifsc_code ?? '') }}" class="border-input" style="width: 160px;" placeholder="IFSC Code" required>
                    </div>
                    <div class="col-md-6">
                        <strong>Name as per Bank Account:</strong> 
                        <input type="text" name="bank_name_ifsc" id="bank_name_ifsc" value="{{ old('bank_name_ifsc', $kyc->bank_name_ifsc ?? '') }}" class="border-input" style="width: 220px;" placeholder="Name in Bank A/c" required>
                        <div id="bank_name_ifsc_error" class="text-danger fw-bold small mt-1 d-none">⚠️ Please fill Name as per Bank Account.</div>
                    </div>
                    <div class="col-12">
                        @php
                            $rawAddress = $kyc->address;
                            $address = is_string($rawAddress) && json_decode($rawAddress, true) ? json_decode($rawAddress, true) : $rawAddress;
                        @endphp
                        <strong>Address:</strong> 
                        <input type="text" name="address" value="@if(is_array($address)){{ ($address['house'] ?? '') . ', ' . ($address['street'] ?? '') . ', ' . ($address['vtc'] ?? '') . ', ' . ($address['dist'] ?? '') . ' ' . ($address['post'] ?? '') . ', ' . ($address['state'] ?? '') . ', ' . ($address['country'] ?? '') . ' ' . ($kyc->zip ?? '') }}@else{{ $address . ' ' . ($kyc->zip ?? '') }}@endif" class="border-input w-100">
                    </div>
                </div>

                <!-- 3. EXISTING GOLD LOAN -->
                <div class="section-title">3. EXISTING GOLD LOAN</div>
                <div class="row g-2 small mb-3">
                    <div class="col-md-6">
                        <strong>Existing Bank/NBFC:</strong> 
                        <input type="text" name="existing_bank" value="{{ old('existing_bank', $kyc->existing_bank ?? '') }}" class="border-input w-75" placeholder="Existing Bank/NBFC Name" required>
                    </div>
                    <div class="col-md-6">
                        <strong>Gold Loan A/c No.:</strong> 
                        <input type="text" name="existing_loan_acc" value="{{ old('existing_loan_acc', $kyc->existing_loan_acc ?? '') }}" class="border-input w-50" placeholder="Gold Loan A/c No. (Optional)">
                    </div>
                    <div class="col-md-6">
                        <strong>Outstanding / Closure Amount (₹):</strong> 
                        <input type="number" name="amount" id="closure_amount" value="{{ $kyc->amount }}" class="border-input" style="width: 160px;">
                    </div>
                    <div class="col-md-6">
                        <strong>Purpose:</strong> 
                        <label class="me-3" style="cursor: pointer;"><input type="radio" name="purpose" value="Transfer" {{ strtoupper($kyc->purpose ?? 'Transfer') === 'TRANSFER' ? 'checked' : '' }}> Transfer</label>
                        <label class="me-3" style="cursor: pointer;"><input type="radio" name="purpose" value="Internal Replace" {{ strtoupper($kyc->purpose ?? '') === 'INTERNAL REPLACE' ? 'checked' : '' }}> Internal Replace</label>
                    </div>
                </div>

                <!-- 4. FUNDING & DISBURSEMENT -->
                <div class="section-title">4. FUNDING & DISBURSEMENT</div>
                <div class="row g-2 small mb-3">
                    <div class="col-md-6">
                        <strong>Principal Funded (₹):</strong> 
                        <input type="number" name="funded_amount" id="funded_amount" value="{{ $kyc->amount }}" class="border-input" style="width: 160px;" oninput="recalculatePayableAndWords()">
                    </div>
                    <div class="col-md-6">
                        <strong>Amount in Words:</strong> 
                        <input type="text" name="amount_words" id="amount_words" value="" class="border-input w-75" readonly>
                    </div>
                    <div class="col-md-6">
                        <strong>Disbursement Date:</strong> 
                        <input type="date" name="disbursement_date" value="{{ date('Y-m-d') }}" class="border-input" style="width: 160px;">
                    </div>
                    <div class="col-md-6">
                        <strong>Disbursement Time:</strong> 
                        <input type="time" name="disbursement_time" value="10:00" class="border-input" style="width: 140px;">
                    </div>
                    <div class="col-md-6">
                        <strong>Mode:</strong> 
                        <label class="me-2"><input type="checkbox" name="mode[]" value="Bank Transfer" checked> Bank Transfer</label>
                        <label class="me-2"><input type="checkbox" name="mode[]" value="UPI"> UPI</label>
                        <label><input type="checkbox" name="mode[]" value="Direct to Existing Lender"> Direct to Existing Lender</label>
                    </div>
                    <div class="col-md-6">
                        <strong>UTR / Transaction Reference:</strong> 
                        <input type="text" name="utr_ref" value="{{ old('utr_ref', $kyc->utr_ref ?? '') }}" class="border-input" style="width: 220px;" placeholder="Transaction Ref / UTR No. (Optional)">
                    </div>
                </div>

                <!-- 5. PROMISE TO PAY / SAME-DAY REPAYMENT -->
                <div class="section-title">5. PROMISE TO PAY / SAME-DAY REPAYMENT</div>
                <p class="small mb-2">
                    For value received, the Borrower unconditionally promises to pay POJO FINSERV the principal amount funded together with the applicable interest/finance charge and other lawful dues under the agreed loan terms. The facility is intended for same-day completion and repayment.
                </p>
                <div class="row g-2 small mb-2">
                    <div class="col-md-6">
                        <strong>Repayment Date:</strong> 
                        <input type="date" name="repayment_date" value="{{ date('Y-m-d') }}" class="border-input" style="width: 160px;">
                    </div>
                    <div class="col-md-6">
                        <strong>Repayment Time:</strong> 
                        <input type="time" name="repayment_time" value="{{ $kyc->repayment_time ?? '20:00' }}" class="border-input" style="width: 140px;">
                    </div>
                </div>
                <div class="small text-muted mb-2">
                    <div>
                        <strong>Applicable Rate / Finance Charge:</strong> 
                        @php
                            $rateVal = (!empty($kyc->interest_rate) && (float)$kyc->interest_rate > 0 && (float)$kyc->interest_rate != 0.1) 
                                ? number_format((float)$kyc->interest_rate, 2) 
                                : '0.50';
                        @endphp
                        <span class="badge bg-warning text-dark fs-6 px-2 py-1 mx-1 border border-warning shadow-sm">
                            <input type="number" step="0.01" name="interest_rate" id="interest_rate" value="{{ $rateVal }}" class="border-0 bg-transparent text-center fw-bold text-danger p-0" style="width: 55px;" oninput="recalculatePayableAndWords()"> %
                        </span> interest applied.
                    </div>
                    <div class="mt-1 fw-medium text-dark">
                        Zero charges for loan processing at DSA Code and fund to be return same day (loan Amount to be >= disbursement Amount else charges applicable)
                    </div>
                </div>
                <div class="small">
                    <strong>Total Amount Payable (₹):</strong> 
                    <input type="number" name="total_payable" id="total_payable" value="" class="border-input fw-bold text-success" style="width: 180px;">
                </div>

                <!-- 6. USE OF FUNDS -->
                <div class="section-title">6. USE OF FUNDS</div>
                <p class="small mb-2">
                    The Borrower shall use the funds only for the stated existing gold-loan transfer. The Borrower shall not divert the funds for any other purpose and shall provide transfer proof when requested by the Lender.
                </p>

                <!-- 7. INTEREST / PRICING -->
                <div class="section-title">7. INTEREST / PRICING</div>
                <p class="small mb-2">
                    The applicable interest/finance charge, effective rate and all other charges shall be disclosed to the Borrower before disbursement and shall be calculated and collected only to the extent permitted by applicable law and the agreed loan terms.
                </p>

                <!-- 8. BORROWER'S UNDERTAKINGS -->
                <div class="section-title">8. BORROWER'S UNDERTAKINGS</div>
                <ol class="small mb-2" style="padding-left: 18px;">
                    <li>The Borrower confirms that all information and KYC documents provided are true and correct.</li>
                    <li>The Borrower shall complete the existing gold-loan closure/transfer promptly after receipt of funds.</li>
                    <li>The Borrower shall repay POJO FINSERV the total amount lawfully due on the agreed repayment date.</li>
                    <li>The Borrower shall immediately inform POJO FINSERV of any event that may prevent same-day completion or repayment.</li>
                    <li>The Borrower shall keep POJO FINSERV informed of changes to address, mobile number or other material KYC information.</li>
                    <li>The Borrower shall retain and provide transaction/closure proof as required.</li>
                </ol>

                <!-- 9. BORROWER DIGITAL VERIFICATION -->
                <div class="section-title">9. BORROWER DIGITAL VERIFICATION</div>
                <div class="row g-3 small align-items-center mb-4">
                    <div class="col-md-6">
                        <strong>Borrower Name:</strong> 
                        <input type="text" name="signature_name" value="{{ $kyc->full_name }}" class="border-input w-75">
                        <div class="mt-2 text-muted" style="font-size: 11px;">
                            <div><strong>IP:</strong> <span id="ip_borrower">Loading...</span></div>
                            <div><strong>Date/Time:</strong> <span id="time_borrower"></span></div>
                        </div>
                    </div>
                    <div class="col-md-6 text-center">
                        <strong>Borrower Digital Verification:</strong>
                        <div class="signature-box mt-2 p-2" id="borrower_verify_container">
                            <div id="borrower_unverified_box">
                                <button type="button" class="btn btn-sm btn-success rounded-pill fw-bold px-4" onclick="openAadhaarModal('borrower')">
                                    <i class="fas fa-shield-alt me-1"></i> Click Digital Verify (Aadhaar OTP)
                                </button>
                            </div>
                            <div id="borrower_verified_box" class="d-none text-success fw-bold">
                                <i class="fas fa-check-circle fa-2x mb-1 text-success d-block"></i>
                                <span>Aadhaar Digitally Verified ✓</span>
                                <div class="text-muted small mt-1" id="borrower_verified_info"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 10. WITNESS (REFERRER PERSON) -->
                <div class="section-title">10. WITNESS (REFERRER PERSON)</div>
                <p class="small text-muted mb-2">
                    I, the undersigned, confirm that I have referred the above borrower. Digital verification for witness is optional.
                </p>
                <div class="row g-3 small align-items-center mb-4">
                    <div class="col-md-5">
                        <strong>Witness / Referrer Name:</strong> 
                        <input type="text" name="witness1" value="{{ $kyc->referel_name }}" class="border-input w-75">
                    </div>
                    <div class="col-md-3 text-muted" style="font-size: 11px;">
                        <div><strong>IP:</strong> <span id="ip_witness">Loading...</span></div>
                        <div><strong>Date/Time:</strong> <span id="time_witness"></span></div>
                    </div>
                    <div class="col-md-4 text-end">
                        <div id="witness_unverified_box" class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-sm btn-outline-success rounded-pill fw-bold px-3" onclick="openAadhaarModal('witness')">
                                <i class="fas fa-shield-alt me-1"></i> Verify via Aadhaar
                            </button>
                            <button type="button" id="witness_sign_btn" class="btn btn-sm btn-outline-secondary rounded-pill fw-bold px-3" onclick="openSignaturePad('witness')">
                                <i class="fas fa-pen me-1"></i> Sign
                            </button>
                        </div>
                        <div id="witness_verified_box" class="d-none text-success fw-bold">
                            <span>&#10004; Witness Digitally Verified</span>
                        </div>
                        <img id="witness_signature_preview" class="border rounded p-1 bg-white mt-1" style="display: none; max-height: 40px; margin-left: auto;">
                    </div>
                </div>

                <!-- 11. FOR POJO FINSERV -->
                <div class="section-title">11. FOR POJO FINSERV</div>
                <div class="row g-3 small align-items-center mb-4">
                    <div class="col-md-6">
                        <strong>Authorised Signatory:</strong> 
                        <input type="text" name="authorized" value="Dhanapal G" class="border-input" style="width: 220px;">
                    </div>
                    <div class="col-md-6 text-center">
                        <strong>Lender Signature:</strong><br>
                        <img src="{{ asset('images/sign.png') }}" alt="Lender Signature" style="height: 60px;" onerror="this.src='{{ asset('img/logo.png') }}';">
                    </div>
                </div>

                <!-- Running Footer -->
                <div class="running-footer">
                    POJO FINSERV | Unsecured Same-Day Gold Loan Transfer Funding
                </div>

                <div class="text-center mt-5">
                    <button type="submit" class="btn btn-lg rounded-pill fw-bold text-white shadow" style="background: linear-gradient(135deg, #0052cc 0%, #008744 100%); border: 1px solid #f59e0b; padding: 12px 40px;">
                        Agree & Submit Documents <i class="fas fa-file-pdf ms-2" style="color: #fbbf24;"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Aadhaar Digital Verification Modal -->
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

<!-- Signature Pad Modal (For Witness Optional Sign) -->
<div class="modal fade" id="signatureModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header text-white" style="background-color: #0052cc;">
                <h5 class="modal-title fw-bold"><i class="fas fa-signature me-2"></i> Provide Signature</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center bg-light p-4">
                <p class="text-muted small mb-2">Please sign clearly inside the white box below.</p>
                <div class="border rounded-3 bg-white d-inline-block shadow-sm overflow-hidden" style="max-width: 100%;">
                    <canvas id="modalCanvas" width="750" height="350" style="touch-action: none; cursor: crosshair;"></canvas>
                </div>
            </div>
            <div class="modal-footer bg-white d-flex justify-content-between">
                <button type="button" class="btn btn-outline-danger fw-bold rounded-pill px-4" onclick="clearModalPad()">
                    <i class="fas fa-eraser me-1"></i> Clear
                </button>
                <button type="button" class="btn btn-success fw-bold rounded-pill px-4" onclick="saveModalSignature()">
                    <i class="fas fa-check me-1"></i> Save Signature
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
let currentRole = null;
let modalPad = null;
let currentRequestId = null;
let activeVerifyRole = 'borrower';

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

function recalculatePayableAndWords() {
    const fundedInput = document.getElementById('funded_amount');
    const wordsInput = document.getElementById('amount_words');
    const payableInput = document.getElementById('total_payable');
    const rateInput = document.getElementById('interest_rate');

    if (fundedInput) {
        const amt = parseFloat(String(fundedInput.value || '').replace(/[^0-9.]/g, '')) || 0;
        const rate = parseFloat(String(rateInput?.value || '0.50').replace(/[^0-9.]/g, '')) || 0;
        if (wordsInput && amt > 0) {
            wordsInput.value = convertNumberToWords(amt).toUpperCase();
        }
        if (payableInput) {
            const interestAmt = (amt * rate) / 100;
            const payable = Math.round(amt + interestAmt);
            payableInput.value = payable;
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

    recalculatePayableAndWords();

    const canvas = document.getElementById('modalCanvas');
    if (canvas) {
        modalPad = new SignaturePad(canvas, {
            penColor: 'rgb(0, 0, 0)'
        });
    }

    // Fetch IP address
    fetch('https://api.ipify.org?format=json')
        .then(res => res.json())
        .then(data => {
            if(document.getElementById('ip_borrower')) document.getElementById('ip_borrower').textContent = data.ip;
            if(document.getElementById('ip_witness')) document.getElementById('ip_witness').textContent = data.ip;
            if(document.getElementById('ip_pto')) document.getElementById('ip_pto').textContent = data.ip;
        })
        .catch(() => {});

    // Set local time
    const now = new Date().toLocaleString('en-IN', {
        timeZone: 'Asia/Kolkata',
        hour12: true,
        year: 'numeric', month: 'short', day: 'numeric',
        hour: '2-digit', minute: '2-digit'
    });

    if(document.getElementById('time_borrower')) document.getElementById('time_borrower').textContent = now;
    if(document.getElementById('time_witness')) document.getElementById('time_witness').textContent = now;
    if(document.getElementById('time_pto')) document.getElementById('time_pto').textContent = now;
});

/* ------------ Aadhaar Modal Verification ------------------------- */
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
            phone: document.querySelector('[name="phone"]')?.value.trim() || ''
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
                const bName = (aadharData.full_name || document.querySelector('[name="full_name"]')?.value || 'BORROWER').toUpperCase();
                const nowIst = new Date().toLocaleString('en-GB', { timeZone: 'Asia/Kolkata' }).replace(',', '') + ' (UTC+05:30)';
                
                const aadhaarVal = document.getElementById('modal_aadhaar_input').value.trim() || document.getElementById('borrower_aadhaar_input').value.trim();
                if (document.getElementById('verified_name_input')) document.getElementById('verified_name_input').value = bName;
                if (document.getElementById('verified_aadhaar_no_input')) document.getElementById('verified_aadhaar_no_input').value = aadhaarVal;
                if (document.getElementById('verified_addhar_no_input')) document.getElementById('verified_addhar_no_input').value = aadhaarVal;
                if (document.getElementById('borrower_aadhaar_input') && aadhaarVal) document.getElementById('borrower_aadhaar_input').value = aadhaarVal;

                // Auto-fill all Aadhaar details into the form inputs
                if (aadharData.full_name) {
                    const nameInp = document.querySelector('[name="full_name"]');
                    if (nameInp) nameInp.value = aadharData.full_name.toUpperCase();
                }
                if (aadharData.care_of) {
                    const parentInp = document.querySelector('[name="parent"]');
                    if (parentInp) parentInp.value = aadharData.care_of.toUpperCase();
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
                
                // SVG stamp Data URI for canvas PDF capture
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

/* ------------ Signature Pad Code ------------------------- */
function openSignaturePad(role) {
    currentRole = role;
    const modalEl = document.getElementById('signatureModal');
    const modal = new bootstrap.Modal(modalEl);
    if (modalPad) {
        modalPad.clear();
    }
    modal.show();
}

function clearModalPad() {
    if (modalPad) {
        modalPad.clear();
    }
}

function saveModalSignature() {
    if (!modalPad || modalPad.isEmpty()) {
        alert('Please draw your signature before saving.');
        return;
    }
    const dataURL = modalPad.toDataURL('image/png');
    if (currentRole) {
        document.getElementById(`${currentRole}_signature`).value = dataURL;
        const img = document.getElementById(`${currentRole}_signature_preview`);
        img.src = dataURL;
        img.style.display = 'block';
        const btn = document.getElementById(`${currentRole}_sign_btn`);
        if (btn) btn.style.display = 'none';
    }
    const modalEl = document.getElementById('signatureModal');
    const modal = bootstrap.Modal.getInstance(modalEl);
    if (modal) modal.hide();
}

/* ------------ Form Submit Code ------------------------- */
document.getElementById('promissoryForm').addEventListener('submit', async function (e) {
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

    const isBorrowerVerified = document.getElementById('borrower_verified').value === '1';
    const borrowerSig = document.getElementById('borrower_signature').value;

    if (!isBorrowerVerified && !borrowerSig) {
        const bName = (document.querySelector('[name="full_name"]')?.value || 'BORROWER').toUpperCase();
        const nowIst = new Date().toLocaleString('en-GB', { timeZone: 'Asia/Kolkata' }).replace(',', '') + ' (UTC+05:30)';
        const stampSvg = `<svg xmlns="http://www.w3.org/2000/svg" width="280" height="90">
            <rect width="100%" height="100%" fill="#f8fafc" rx="6" stroke="#000000" stroke-width="1.5"/>
            <text x="12" y="24" font-family="monospace" font-size="13" font-weight="bold" fill="#000000">${bName}</text>
            <text x="12" y="44" font-family="monospace" font-size="10" fill="#333333">Date: ${nowIst}</text>
            <text x="12" y="62" font-family="monospace" font-size="11" font-weight="bold" fill="#15803d">✓ Borrower Signed</text>
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
