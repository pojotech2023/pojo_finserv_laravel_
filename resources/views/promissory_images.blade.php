@extends('layouts.app')

@section('content')
<div class="container-fluid py-2">
    <!-- Header Title & Quick Actions -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 pb-3 border-bottom">
        <div>
            <h2 class="fw-bold text-dark mb-1">
                <i class="fas fa-users-cog text-primary me-2"></i> Customer Applications
            </h2>
            <p class="text-muted small mb-0">Manage customer applications submitted via Apply Now (Gold Take Over & Sell Your Gold) for <strong>POJO FINSERV</strong>.</p>
        </div>
        <div class="mt-3 mt-md-0 d-flex flex-wrap gap-2">
            <a href="{{ route('kyc.export') }}" class="btn btn-success fw-bold shadow-sm d-inline-flex align-items-center gap-2">
                <i class="fas fa-file-excel"></i> Export All Excel
            </a>
            <a href="{{ url('/customerlogin?service=takeover') }}" target="_blank" class="btn btn-primary fw-bold shadow-sm d-inline-flex align-items-center gap-2">
                <i class="fas fa-hand-holding-usd"></i> Gold Take Over Form
            </a>
            <a href="{{ url('/customerlogin?service=sell_gold') }}" target="_blank" class="btn btn-warning fw-bold text-dark shadow-sm d-inline-flex align-items-center gap-2">
                <i class="fas fa-coins"></i> Sell Gold Form
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm py-3 px-4 mb-4" role="alert">
        <div class="d-flex align-items-center">
            <i class="fas fa-check-circle fs-4 me-3 text-success"></i>
            <div>
                <strong>Success!</strong> {{ session('success') }}
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- OVERVIEW STAT CARDS -->
    <div class="row g-3 mb-4">
        <!-- Card 1: Total Customer Applications -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="border-left: 5px solid #0052cc !important; background: #ffffff;">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small text-uppercase fw-bold">Customer Applications</span>
                            <h3 class="fw-bold text-dark mb-1 mt-1">{{ $totalCustomers ?? $kycs->total() }}</h3>
                            <div class="small text-muted">
                                <span class="badge bg-primary me-1"><i class="fas fa-hand-holding-usd me-1"></i> Takeover: {{ $totalTakeover ?? 0 }}</span>
                                <span class="badge bg-warning text-dark"><i class="fas fa-coins me-1"></i> Sell Gold: {{ $totalSellGold ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3 text-primary">
                            <i class="fas fa-users fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Contact Enquiries -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="border-left: 5px solid #008744 !important; background: #ffffff;">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small text-uppercase fw-bold">Contact Enquiries</span>
                            <h3 class="fw-bold text-dark mb-1 mt-1">{{ $totalEnquiries ?? 0 }}</h3>
                            <a href="{{ route('admin.contact.enquiries') }}" class="small text-success fw-semibold text-decoration-none">
                                View website messages <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                        <div class="rounded-circle bg-success bg-opacity-10 p-3 text-success">
                            <i class="fas fa-envelope-open-text fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3: Live API Hits Counter -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="border-left: 5px solid #f59e0b !important; background: #ffffff;">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small text-uppercase fw-bold">Live API Hit Counts</span>
                            <div class="d-flex gap-3 mt-1">
                                <div>
                                    <span class="small text-muted d-block">Aadhaar Hits</span>
                                    <span class="fs-4 fw-bold text-dark">{{ $aadhaarHits ?? 0 }}</span>
                                </div>
                                <div class="border-start ps-3">
                                    <span class="small text-muted d-block">PAN Hits</span>
                                    <span class="fs-4 fw-bold text-dark">{{ $panHits ?? 0 }}</span>
                                </div>
                            </div>
                            <form action="{{ route('admin.resetApiHits') }}" method="POST" class="d-inline mt-1" onsubmit="return confirm('Reset Aadhaar and PAN API hit counters to 0?');">
                                @csrf
                                <button type="submit" class="btn btn-link p-0 text-secondary small text-decoration-none" style="font-size: 11px;">
                                    <i class="fas fa-redo-alt me-1"></i> Reset counters
                                </button>
                            </form>
                        </div>
                        <div class="rounded-circle bg-warning bg-opacity-10 p-3 text-warning">
                            <i class="fas fa-chart-line fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 4: Current System Mode -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="border-left: 5px solid {{ $isLive ? '#10b981' : '#f59e0b' }} !important; background: #ffffff;">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small text-uppercase fw-bold">Current System Mode</span>
                            <div class="mt-1">
                                @if($isLive)
                                    <span class="badge bg-success fs-6 px-3 py-1.5"><i class="fas fa-satellite-dish fa-spin me-1"></i> LIVE MODE</span>
                                    <div class="text-muted small mt-1" style="font-size: 11px;">Real APIs & Google Drive enabled</div>
                                @else
                                    <span class="badge bg-warning text-dark fs-6 px-3 py-1.5"><i class="fas fa-flask me-1"></i> TESTING MODE</span>
                                    <div class="text-muted small mt-1" style="font-size: 11px;">Sample OTP: 123456 | APIs bypassed</div>
                                @endif
                            </div>
                            <form action="{{ route('admin.toggleApiMode') }}" method="POST" class="d-inline mt-2 d-block">
                                @csrf
                                <button type="submit" class="btn btn-sm {{ $isLive ? 'btn-outline-secondary' : 'btn-outline-primary' }} rounded-pill px-2.5 py-0.5 mt-1 fw-bold" style="font-size: 11px;">
                                    {{ $isLive ? 'Switch to Test Mode' : 'Switch to Live Mode' }}
                                </button>
                            </form>
                        </div>
                        <div class="rounded-circle p-3 {{ $isLive ? 'bg-success bg-opacity-10 text-success' : 'bg-warning bg-opacity-10 text-warning' }}">
                            <i class="fas {{ $isLive ? 'fa-bolt' : 'fa-vial' }} fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FILTER & SEARCH CARD -->
    <div class="card border-0 shadow-sm rounded-4 mb-4" style="background: #ffffff;">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold text-dark mb-0"><i class="fas fa-filter text-primary me-2"></i> Filter & Search Applications</h5>
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('kyc.index') }}" class="btn {{ !request('service_type') ? 'btn-primary' : 'btn-outline-secondary' }}">
                        All ({{ $totalCustomers ?? $kycs->total() }})
                    </a>
                    <a href="{{ route('kyc.index', ['service_type' => 'takeover']) }}" class="btn {{ request('service_type') === 'takeover' ? 'btn-primary' : 'btn-outline-secondary' }}">
                        Gold Take Over ({{ $totalTakeover ?? 0 }})
                    </a>
                    <a href="{{ route('kyc.index', ['service_type' => 'sell_gold']) }}" class="btn {{ request('service_type') === 'sell_gold' ? 'btn-warning text-dark' : 'btn-outline-secondary' }}">
                        Sell Your Gold ({{ $totalSellGold ?? 0 }})
                    </a>
                </div>
            </div>
            
            <form method="GET" action="{{ route('kyc.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-semibold text-secondary small">Search Name / Phone / Aadhaar / PAN</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search keyword...">
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold text-secondary small">Service Type</label>
                    <select name="service_type" class="form-select">
                        <option value="">All Services</option>
                        <option value="takeover" {{ request('service_type') === 'takeover' ? 'selected' : '' }}>Gold Take Over</option>
                        <option value="sell_gold" {{ request('service_type') === 'sell_gold' ? 'selected' : '' }}>Sell Your Gold</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold text-secondary small">Referral Name</label>
                    <input type="text" name="referel_name" value="{{ request('referel_name') }}" class="form-control" placeholder="Referral name">
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold text-secondary small">Repayment From</label>
                    <input type="date" name="repayment_from" value="{{ request('repayment_from') }}" class="form-control">
                </div>

                <div class="col-md-3 d-flex gap-2 align-items-end">
                    <button type="submit" class="btn btn-primary w-100 fw-bold">
                        <i class="fas fa-search me-1"></i> Filter
                    </button>
                    <a href="{{ route('kyc.index') }}" class="btn btn-outline-secondary fw-bold">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- CUSTOMER DETAILS TABLE CARD -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header py-3 px-4 d-flex justify-content-between align-items-center" style="background: linear-gradient(90deg, #072348 0%, #003b95 100%);">
            <h5 class="fw-bold mb-0 text-white"><i class="fas fa-list-alt me-2 text-warning"></i> Registered Customer Applications</h5>
            <span class="badge bg-light text-dark px-3 py-2 fs-6 fw-bold">Total: {{ $kycs->total() }}</span>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-center">
                    <thead class="table-light" style="font-size: 0.88rem; text-transform: uppercase;">
                        <tr>
                            <th class="py-3">#</th>
                            <th class="py-3 text-start">Customer Info</th>
                            <th class="py-3">Aadhaar / PAN</th>
                            <th class="py-3">Amount & Repayment</th>
                            <th class="py-3">Aadhaar Documents</th>
                            <th class="py-3">Profile Photo</th>
                            <th class="py-3">PDF</th>
                            <th class="py-3">Final Stamped</th>
                            <th class="py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 0.92rem;">
                        @forelse($kycs as $index => $kyc)
                        <tr>
                            <td class="fw-bold text-secondary">
                                {{ ($kycs->currentPage() - 1) * $kycs->perPage() + $index + 1 }}
                            </td>
                            <td class="text-start">
                                <div class="fw-bold text-dark fs-6">{{ $kyc->full_name ?? 'N/A' }}</div>
                                <div class="small text-muted"><i class="fas fa-phone me-1"></i> {{ $kyc->phone ?? 'N/A' }}</div>
                                <div class="mt-1 d-flex flex-wrap gap-1">
                                    @if(($kyc->service_type ?? 'takeover') === 'sell_gold')
                                        <span class="badge bg-warning text-dark"><i class="fas fa-coins me-1"></i> Sell Your Gold</span>
                                    @else
                                        <span class="badge bg-primary"><i class="fas fa-hand-holding-usd me-1"></i> Gold Take Over</span>
                                    @endif
                                    @if($kyc->referel_name)
                                        <span class="badge bg-light text-dark border">Ref: {{ $kyc->referel_name }}</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="small fw-semibold text-dark"><i class="fas fa-fingerprint text-primary me-1"></i> {{ $kyc->aadhaar_number ?? 'N/A' }}</div>
                                <div class="small text-muted"><i class="fas fa-id-card text-secondary me-1"></i> PAN: {{ $kyc->pan_number ?? 'N/A' }}</div>
                            </td>
                            <td>
                                <div class="fw-bold text-success fs-6">₹{{ number_format((float) ($kyc->amount ?? 0)) }}</div>
                                <div class="small text-muted">{{ $kyc->repayment_date ? date('d-m-Y', strtotime($kyc->repayment_date)) : 'N/A' }}</div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-1 align-items-center">
                                    @if($kyc->aadhar_front)
                                        @php $afExt = strtolower(pathinfo($kyc->aadhar_front, PATHINFO_EXTENSION)); @endphp
                                        @if($afExt === 'pdf')
                                            <a href="{{ asset('storage/'.$kyc->aadhar_front) }}" target="_blank" class="btn btn-outline-danger btn-sm p-1 d-inline-flex flex-column align-items-center justify-content-center rounded shadow-sm" style="width: 50px; height: 35px; text-decoration: none;" title="Aadhaar Front (PDF)">
                                                <i class="fas fa-file-pdf text-danger" style="font-size: 14px;"></i>
                                                <span style="font-size: 8pt; line-height: 1;" class="fw-bold text-danger mt-1">PDF</span>
                                            </a>
                                        @else
                                            <a href="{{ asset('storage/'.$kyc->aadhar_front) }}" target="_blank" title="Aadhaar Front Image">
                                                <img src="{{ asset('storage/'.$kyc->aadhar_front) }}" class="rounded border shadow-sm" style="width: 50px; height: 35px; object-fit: cover;" onerror="this.parentElement.innerHTML='<span class=\'badge bg-secondary p-1\'>Doc</span>';">
                                            </a>
                                        @endif
                                    @endif
                                    @if($kyc->aadhar_back)
                                        @php $abExt = strtolower(pathinfo($kyc->aadhar_back, PATHINFO_EXTENSION)); @endphp
                                        @if($abExt === 'pdf')
                                            <a href="{{ asset('storage/'.$kyc->aadhar_back) }}" target="_blank" class="btn btn-outline-danger btn-sm p-1 d-inline-flex flex-column align-items-center justify-content-center rounded shadow-sm" style="width: 50px; height: 35px; text-decoration: none;" title="Aadhaar Back (PDF)">
                                                <i class="fas fa-file-pdf text-danger" style="font-size: 14px;"></i>
                                                <span style="font-size: 8pt; line-height: 1;" class="fw-bold text-danger mt-1">PDF</span>
                                            </a>
                                        @else
                                            <a href="{{ asset('storage/'.$kyc->aadhar_back) }}" target="_blank" title="Aadhaar Back Image">
                                                <img src="{{ asset('storage/'.$kyc->aadhar_back) }}" class="rounded border shadow-sm" style="width: 50px; height: 35px; object-fit: cover;" onerror="this.parentElement.innerHTML='<span class=\'badge bg-secondary p-1\'>Doc</span>';">
                                            </a>
                                        @endif
                                    @endif
                                </div>
                            </td>
                            <td>
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
                                <img src="{{ $pImg }}" class="rounded-circle border shadow-sm" style="width: 42px; height: 42px; object-fit: cover;">
                                @else
                                <span class="text-muted small">No photo</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('kyc.download', $kyc->id) }}" class="btn btn-outline-danger btn-sm rounded-pill fw-bold" target="_blank">
                                    <i class="fas fa-file-pdf"></i> PDF
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('admin.promissory.download', $kyc->id) }}" class="btn btn-outline-success btn-sm rounded-pill fw-bold" target="_blank">
                                    <i class="fas fa-stamp"></i> Final
                                </a>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('kyc.show', $kyc->id) }}" class="btn btn-primary btn-sm rounded-pill px-2.5" title="View Full Details">
                                        <i class="fas fa-eye me-1"></i> View
                                    </a>
                                    <form action="{{ route('kyc.destroy', $kyc->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure want to delete this customer record?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm rounded-pill px-2.5" title="Delete Customer">
                                            <i class="fas fa-trash-alt me-1"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="py-5 text-muted">
                                <i class="fas fa-folder-open fs-2 mb-2 d-block text-secondary"></i>
                                No customer records found matching the filter.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-white py-3 px-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
            <span class="text-muted small">Showing {{ $kycs->firstItem() ?? 0 }} to {{ $kycs->lastItem() ?? 0 }} of {{ $kycs->total() }} entries</span>
            <div>
                {!! $kycs->links('pagination::bootstrap-5') !!}
            </div>
        </div>
    </div>
</div>
@endsection
