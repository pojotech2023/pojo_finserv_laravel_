@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Bar -->
    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
        <div>
            <h2 class="fw-bold text-dark mb-1"><i class="fas fa-id-card text-primary me-2"></i> Full Customer Details & KYC Report</h2>
            <p class="text-muted small mb-0">Detailed breakdown of customer information, document uploads, and loan promissory note.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('kyc.index') }}" class="btn btn-secondary fw-bold rounded-pill">
                <i class="fas fa-arrow-left me-1"></i> Back to Customer List
            </a>
            <a href="{{ route('kyc.download', $kyc->id) }}" class="btn btn-danger fw-bold rounded-pill" target="_blank">
                <i class="fas fa-file-pdf me-1"></i> Download PDF
            </a>
        </div>
    </div>

    <!-- PROFILE & KEY DETAILS CARD -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="row align-items-center g-4">
                <div class="col-md-2 text-center">
                    @php
                        $pImg = null;
                        if (!empty($kyc->profile_image)) {
                            if (str_starts_with($kyc->profile_image, 'data:image')) {
                                $pImg = $kyc->profile_image;
                            } elseif (str_starts_with($kyc->profile_image, '/9j/') || str_starts_with($kyc->profile_image, 'iVBOR') || strlen($kyc->profile_image) > 100) {
                                $pImg = 'data:image/jpeg;base64,' . $kyc->profile_image;
                            } elseif (file_exists(storage_path('app/public/' . $kyc->profile_image))) {
                                $pImg = asset('storage/' . $kyc->profile_image);
                            }
                        }
                        if (!$pImg && !empty($kyc->eye_blink_image)) {
                            $pImg = asset($kyc->eye_blink_image);
                        }
                    @endphp
                    @if($pImg)
                    <img src="{{ $pImg }}" class="rounded-circle shadow border border-3 border-primary" style="width: 120px; height: 120px; object-fit: cover;" alt="Profile Photo">
                    @else
                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto border" style="width: 100px; height: 100px; font-size: 2.5rem; color: #94a3b8;">
                        <i class="fas fa-user"></i>
                    </div>
                    @endif
                </div>

                <div class="col-md-10">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="text-muted small fw-semibold d-block">Full Name & Service</label>
                            <span class="fs-5 fw-bold text-dark">{{ $kyc->full_name ?? 'N/A' }}</span>
                            <div class="mt-1">
                                @if(($kyc->service_type ?? 'takeover') === 'sell_gold')
                                    <span class="badge bg-warning text-dark px-2 py-1"><i class="fas fa-coins me-1"></i> Sell Your Gold</span>
                                @else
                                    <span class="badge bg-primary px-2 py-1"><i class="fas fa-hand-holding-usd me-1"></i> Gold Take Over</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted small fw-semibold d-block">Phone / Mobile</label>
                            <span class="fs-5 fw-bold text-primary"><i class="fas fa-phone-alt me-1"></i> {{ $kyc->phone ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted small fw-semibold d-block">Referral Name</label>
                            <span class="badge bg-primary fs-6 px-3 py-2">{{ $kyc->referel_name ?? 'Direct' }}</span>
                        </div>

                        <div class="col-md-4">
                            <label class="text-muted small fw-semibold d-block">Aadhaar Number</label>
                            <span class="fw-bold text-dark">{{ $kyc->aadhaar_number ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted small fw-semibold d-block">PAN Number</label>
                            <span class="fw-bold text-dark">{{ $kyc->pan_number ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted small fw-semibold d-block">Care Of</label>
                            <span class="fw-semibold text-dark">{{ $kyc->care_of ?? 'N/A' }}</span>
                        </div>

                        <div class="col-md-4">
                            <label class="text-muted small fw-semibold d-block">Loan Amount</label>
                            <span class="fs-5 fw-bold text-success">₹{{ number_format((float)($kyc->amount ?? 0)) }}</span>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted small fw-semibold d-block">Repayment Date & Time</label>
                            <span class="fw-semibold text-dark">{{ $kyc->repayment_date ?? 'N/A' }} ({{ $kyc->repayment_time ?? 'N/A' }})</span>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted small fw-semibold d-block">Registration Date</label>
                            <span class="fw-semibold text-dark">{{ $kyc->created_at ? $kyc->created_at->format('d-m-Y H:i A') : 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="my-4">

            @if(($kyc->service_type ?? 'takeover') === 'sell_gold')
                <h5 class="fw-bold text-dark mb-3"><i class="fas fa-coins text-warning me-2"></i> Gold Purchase Details</h5>
                <div class="row g-3 bg-light p-3 rounded-3 border mb-4">
                    <div class="col-md-4">
                        <label class="text-muted small fw-semibold d-block">Gold Article / Ornaments</label>
                        <span class="fw-bold text-dark">{{ $kyc->gold_article ?? 'Gold Ornaments' }}</span>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small fw-semibold d-block">Gross Weight</label>
                        <span class="fw-bold text-dark">{{ $kyc->gross_weight ?? 'N/A' }} grams</span>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small fw-semibold d-block">Net Gold Weight</label>
                        <span class="fw-bold text-dark">{{ $kyc->net_weight ?? 'N/A' }} grams</span>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small fw-semibold d-block">Gold Rate (per gram)</label>
                        <span class="fw-bold text-dark">₹ {{ number_format((float)($kyc->gold_rate ?? 0)) }}</span>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small fw-semibold d-block">Total Purchase Amount</label>
                        <span class="fw-bold text-success fs-5">₹ {{ number_format((float)($kyc->amount ?? 0)) }}</span>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small fw-semibold d-block">Payment Mode</label>
                        <span class="badge bg-success fs-6">{{ $kyc->payment_mode ?? 'Bank Transfer' }}</span>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small fw-semibold d-block">Transaction / UTR No.</label>
                        <span class="fw-bold text-dark">{{ $kyc->utr_ref ?? 'N/A' }}</span>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small fw-semibold d-block">Name as per Bank A/c</label>
                        <span class="fw-bold text-dark">{{ $kyc->bank_name_ifsc ?? $kyc->full_name }}</span>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small fw-semibold d-block">Bank Account No.</label>
                        <span class="fw-bold text-dark">{{ $kyc->bank_acc ?? 'N/A' }}</span>
                    </div>
                </div>
            @endif

            <div>
                <label class="text-muted small fw-semibold d-block mb-1">Customer Address</label>
                @php 
                    $address = json_decode($kyc->address, true); 
                @endphp
                <div class="p-3 bg-light rounded-3 border text-dark fw-semibold">
                    @if(is_array($address))
                        {{ ($address['house'] ?? '') . ', ' . ($address['street'] ?? '') . ', ' . ($address['vtc'] ?? '') . ', ' . ($address['dist'] ?? '') . ' ' . ($address['post'] ?? '') . ', ' . ($address['state'] ?? '') . ', ' . ($address['country'] ?? '') . ' - ' . $kyc->zip }}
                    @else
                        {{ $kyc->address ?? 'N/A' }}
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- DOCUMENT & ATTACHMENT GALLERY -->
    <h4 class="fw-bold text-dark mb-3"><i class="fas fa-folder-open text-primary me-2"></i> Uploaded Customer Documents</h4>
    
    <div class="row g-4 mb-5">
        <!-- Aadhaar Front -->
        <div class="col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-3">
                <h6 class="fw-bold text-dark mb-2"><i class="fas fa-address-card text-primary me-1"></i> Aadhaar Front</h6>
                @if($kyc->aadhar_front)
                    @php $afExt = strtolower(pathinfo($kyc->aadhar_front, PATHINFO_EXTENSION)); @endphp
                    @if(in_array($afExt, ['jpg', 'jpeg', 'png', 'webp']))
                        <a href="{{ asset('storage/' . $kyc->aadhar_front) }}" target="_blank">
                            <img src="{{ asset('storage/' . $kyc->aadhar_front) }}" class="img-fluid rounded-3 border" style="max-height: 200px; width: 100%; object-fit: cover;">
                        </a>
                    @elseif($afExt === 'pdf')
                        <a href="{{ asset('storage/' . $kyc->aadhar_front) }}" target="_blank" class="btn btn-outline-danger fw-bold d-flex flex-column align-items-center justify-content-center p-3 rounded-3">
                            <i class="fas fa-file-pdf fa-2x mb-1 text-danger"></i>
                            <span>View Aadhaar Front (PDF)</span>
                        </a>
                    @endif
                @else
                <p class="text-muted small">Not Uploaded</p>
                @endif
            </div>
        </div>

        <!-- Aadhaar Back -->
        <div class="col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-3">
                <h6 class="fw-bold text-dark mb-2"><i class="fas fa-address-card text-primary me-1"></i> Aadhaar Back</h6>
                @if($kyc->aadhar_back)
                    @php $abExt = strtolower(pathinfo($kyc->aadhar_back, PATHINFO_EXTENSION)); @endphp
                    @if(in_array($abExt, ['jpg', 'jpeg', 'png', 'webp']))
                        <a href="{{ asset('storage/' . $kyc->aadhar_back) }}" target="_blank">
                            <img src="{{ asset('storage/' . $kyc->aadhar_back) }}" class="img-fluid rounded-3 border" style="max-height: 200px; width: 100%; object-fit: cover;">
                        </a>
                    @elseif($abExt === 'pdf')
                        <a href="{{ asset('storage/' . $kyc->aadhar_back) }}" target="_blank" class="btn btn-outline-danger fw-bold d-flex flex-column align-items-center justify-content-center p-3 rounded-3">
                            <i class="fas fa-file-pdf fa-2x mb-1 text-danger"></i>
                            <span>View Aadhaar Back (PDF)</span>
                        </a>
                    @endif
                @else
                <p class="text-muted small">Not Uploaded</p>
                @endif
            </div>
        </div>

        <!-- PAN Card -->
        <div class="col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-3">
                <h6 class="fw-bold text-dark mb-2"><i class="fas fa-id-badge text-primary me-1"></i> PAN Card</h6>
                @if($kyc->pan_card_image)
                    @php $panExt = strtolower(pathinfo($kyc->pan_card_image, PATHINFO_EXTENSION)); @endphp
                    @if(in_array($panExt, ['jpg', 'jpeg', 'png']))
                        <a href="{{ asset('storage/' . $kyc->pan_card_image) }}" target="_blank">
                            <img src="{{ asset('storage/' . $kyc->pan_card_image) }}" class="img-fluid rounded-3 border" style="max-height: 200px; width: 100%; object-fit: cover;">
                        </a>
                    @elseif($panExt === 'pdf')
                        <a href="{{ asset('storage/' . $kyc->pan_card_image) }}" target="_blank" class="btn btn-outline-primary fw-bold mt-2">
                            <i class="fas fa-file-pdf me-1"></i> View PAN PDF
                        </a>
                    @endif
                @else
                <p class="text-muted small">Not Uploaded</p>
                @endif
            </div>
        </div>

        <!-- Cheque Leaf -->
        <div class="col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-3">
                <h6 class="fw-bold text-dark mb-2"><i class="fas fa-money-check text-primary me-1"></i> Cheque Leaf</h6>
                @if($kyc->cheque)
                    @php $chqExt = strtolower(pathinfo($kyc->cheque, PATHINFO_EXTENSION)); @endphp
                    @if(in_array($chqExt, ['jpg', 'jpeg', 'png', 'webp']))
                        <a href="{{ asset('storage/' . $kyc->cheque) }}" target="_blank">
                            <img src="{{ asset('storage/' . $kyc->cheque) }}" class="img-fluid rounded-3 border" style="max-height: 200px; width: 100%; object-fit: cover;">
                        </a>
                    @elseif($chqExt === 'pdf')
                        <a href="{{ asset('storage/' . $kyc->cheque) }}" target="_blank" class="btn btn-outline-primary fw-bold d-flex flex-column align-items-center justify-content-center p-3 rounded-3">
                            <i class="fas fa-file-pdf fa-2x mb-1 text-primary"></i>
                            <span>View Cheque PDF</span>
                        </a>
                    @endif
                @else
                <p class="text-muted small">Not Uploaded</p>
                @endif
            </div>
        </div>

        <!-- Eye Blink Photo -->
        <div class="col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-3">
                <h6 class="fw-bold text-dark mb-2"><i class="fas fa-eye text-primary me-1"></i> Eye Blink Verification Photo</h6>
                @if($kyc->eye_blink_image)
                <a href="{{ asset($kyc->eye_blink_image) }}" target="_blank">
                    <img src="{{ asset($kyc->eye_blink_image) }}" class="img-fluid rounded-3 border" style="max-height: 200px; width: 100%; object-fit: cover;">
                </a>
                @else
                <p class="text-muted small">Not Uploaded</p>
                @endif
            </div>
        </div>

        <!-- Promissory Image -->
        <div class="col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-3">
                <h6 class="fw-bold text-dark mb-2"><i class="fas fa-file-signature text-primary me-1"></i> Digital Promissory Note</h6>
                @if($kyc->promissory_image)
                <a href="{{ asset('storage/' . $kyc->promissory_image) }}" target="_blank">
                    <img src="{{ asset('storage/' . $kyc->promissory_image) }}" class="img-fluid rounded-3 border" style="max-height: 200px; width: 100%; object-fit: cover;">
                </a>
                <div class="mt-2">
                    <a href="{{ route('admin.promissory.download', $kyc->id) }}" target="_blank" class="btn btn-success btn-sm fw-bold w-100">
                        <i class="fas fa-download me-1"></i> Download Stamped Note
                    </a>
                </div>
                @else
                <p class="text-muted small">Not Generated</p>
                @endif
            </div>
        </div>
    </div>

    <!-- GOLD LOAN PLEDGE RECEIPTS -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
        <h5 class="fw-bold text-dark mb-3"><i class="fas fa-receipt text-warning me-2"></i> Gold Loan Pledge Bills</h5>
        <div class="row g-3">
            @if ($kyc->loanUploads && $kyc->loanUploads->count())
                @foreach ($kyc->loanUploads as $index => $loan)
                    @php
                        $path = $loan->loan_bill;
                        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                    @endphp
                    <div class="col-md-4">
                        <div class="border rounded-3 p-3 bg-light">
                            <h6 class="fw-bold text-dark mb-2">Loan Pledge Bill #{{ $index + 1 }}</h6>
                            @if(in_array($ext, ['jpg', 'jpeg', 'png']))
                                <a href="{{ asset('storage/' . $path) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $path) }}" class="img-fluid rounded border" style="max-height: 150px; width: 100%; object-fit: cover;">
                                </a>
                            @elseif($ext === 'pdf')
                                <a href="{{ asset('storage/' . $path) }}" target="_blank" class="btn btn-outline-primary btn-sm fw-bold w-100">
                                    <i class="fas fa-file-pdf me-1"></i> View Pledge Bill PDF
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <p class="text-muted small mb-0">No gold loan pledge bills uploaded for this customer.</p>
            @endif
        </div>
    </div>
</div>
@endsection
