@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Loan Interest Status - All Records</h2>
        <div class="d-flex gap-2">

            {{-- ✅ Export dropdown with status options --}}
            <div class="dropdown">
    <button class="btn btn-success dropdown-toggle" type="button" data-bs-toggle="dropdown">
        <i class="bi bi-file-earmark-excel"></i> Export Excel
    </button>
    <ul class="dropdown-menu">
        <li>
            <a class="dropdown-item"
               href="{{ route('loan.interest.list.export') }}?export_status=all&{{ http_build_query(request()->except('_token')) }}">
                📋 All Loans
            </a>
        </li>
        <li>
            <a class="dropdown-item"
               href="{{ route('loan.interest.list.export') }}?export_status=pending&{{ http_build_query(request()->except('_token')) }}">
                🟡 Pending Only
            </a>
        </li>
        <li>
            <a class="dropdown-item"
               href="{{ route('loan.interest.list.export') }}?export_status=accepted&{{ http_build_query(request()->except('_token')) }}">
                🟢 Accepted Only
            </a>
        </li>
        <li>
            <a class="dropdown-item"
               href="{{ route('loan.interest.list.export') }}?export_status=rejected&{{ http_build_query(request()->except('_token')) }}">
                🔴 Rejected Only
            </a>
        </li>
    </ul>
</div>

            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ===== FILTER FORM ===== --}}
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body bg-light rounded">
            <form method="GET" action="{{ request()->url() }}" id="filter-form">
                <div class="row g-2 align-items-end">

                    {{-- Name --}}
                   <div class="col-md-2 col-sm-6">
    <label class="form-label fw-semibold mb-1" style="font-size:13px;">Customer Name</label>
    <input type="text"
           name="customer_name"
           value="{{ request('customer_name') }}"
           class="form-control form-control-sm"
           placeholder="Search customer name">
</div>

                    {{-- ✅ Staff Name --}}
                    <div class="col-md-2 col-sm-6">
                        <label class="form-label fw-semibold mb-1" style="font-size:13px;">Staff Name</label>
                        <input type="text"
                               name="staff_name"
                               value="{{ request('staff_name') }}"
                               class="form-control form-control-sm"
                               placeholder="Search staff name">
                    </div>

                    {{-- UTR No --}}
                    <div class="col-md-2 col-sm-6">
                        <label class="form-label fw-semibold mb-1" style="font-size:13px;">UTR No</label>
                        <input type="text"
                               name="utrno"
                               value="{{ request('utrno') }}"
                               class="form-control form-control-sm"
                               placeholder="Search UTR no">
                    </div>

                    {{-- Conform Ref No --}}
                    <div class="col-md-2 col-sm-6">
                        <label class="form-label fw-semibold mb-1" style="font-size:13px;">Ref No</label>
                        <input type="text"
                               name="conform_ref_no"
                               value="{{ request('conform_ref_no') }}"
                               class="form-control form-control-sm"
                               placeholder="Search ref no">
                    </div>

                    {{-- From Date --}}
                    <div class="col-md-2 col-sm-6">
                        <label class="form-label fw-semibold mb-1" style="font-size:13px;">From Date</label>
                        <input type="date"
                               name="from_date"
                               value="{{ request('from_date') }}"
                               class="form-control form-control-sm">
                    </div>

                    {{-- To Date --}}
                    <div class="col-md-2 col-sm-6">
                        <label class="form-label fw-semibold mb-1" style="font-size:13px;">To Date</label>
                        <input type="date"
                               name="to_date"
                               value="{{ request('to_date') }}"
                               class="form-control form-control-sm">
                    </div>

                </div>

                {{-- Buttons --}}
                <div class="row mt-2">
                    <div class="col-12 d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="bi bi-search"></i> Filter
                        </button>
                        <a href="{{ request()->url() }}" class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-x-circle"></i> Clear
                        </a>
                    </div>
                </div>

            </form>
        </div>
    </div>
    {{-- ===== END FILTER FORM ===== --}}

    @php
        $totalCount    = count($loanInterestData);
       $pendingCount  = collect($loanInterestData)->filter(fn($row) =>
        !in_array($row['status'] ?? '', ['accepted', 'rejected']) &&
        $row['balance_amount'] < 0
    )->count();
        $acceptedCount = collect($loanInterestData)->where('status', 'accepted')->count();
        $rejectedCount = collect($loanInterestData)->where('status', 'rejected')->count();
    @endphp

    {{-- ===== SUMMARY CARDS ===== --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center border-secondary">
                <div class="card-body">
                    <h3 class="mb-0">{{ $totalCount }}</h3>
                    <p class="mb-0 text-muted">Total Loans</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-warning">
                <div class="card-body">
                    <h3 class="mb-0 text-warning">{{ $pendingCount }}</h3>
                    <p class="mb-0 text-muted">Pending</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-success">
                <div class="card-body">
                    <h3 class="mb-0 text-success">{{ $acceptedCount }}</h3>
                    <p class="mb-0 text-muted">Accepted</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-danger">
                <div class="card-body">
                    <h3 class="mb-0 text-danger">{{ $rejectedCount }}</h3>
                    <p class="mb-0 text-muted">Rejected</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== FILTER TABS ===== --}}
    <ul class="nav nav-tabs mb-3" id="statusTab">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#all">
                All <span class="badge bg-secondary">{{ $totalCount }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#pending">
                Pending <span class="badge bg-warning text-dark">{{ $pendingCount }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#accepted">
                Accepted <span class="badge bg-success">{{ $acceptedCount }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#rejected">
                Rejected <span class="badge bg-danger">{{ $rejectedCount }}</span>
            </a>
        </li>
    </ul>

    <div class="tab-content">

        {{-- ALL TAB --}}
        <div class="tab-pane fade show active" id="all">
            @include('bankstatement.partials.loan_interest_table', [
                'data'    => $loanInterestData,
                'tabName' => 'all'
            ])
        </div>

        {{-- PENDING TAB --}}
        <div class="tab-pane fade" id="pending">
            @php
              $pendingData = collect($loanInterestData)->filter(fn($row) =>
    !in_array($row['status'] ?? '', ['accepted', 'rejected']) &&
    $row['balance_amount'] < 0
)->values()->toArray();
            @endphp
            @include('bankstatement.partials.loan_interest_table', [
                'data'    => $pendingData,
                'tabName' => 'pending'
            ])
        </div>

        {{-- ACCEPTED TAB --}}
        <div class="tab-pane fade" id="accepted">
            @php $acceptedData = collect($loanInterestData)->where('status', 'accepted')->values()->toArray(); @endphp
            @include('bankstatement.partials.loan_interest_table', [
                'data'    => $acceptedData,
                'tabName' => 'accepted'
            ])
        </div>

        {{-- REJECTED TAB --}}
        <div class="tab-pane fade" id="rejected">
            @php $rejectedData = collect($loanInterestData)->where('status', 'rejected')->values()->toArray(); @endphp
            @include('bankstatement.partials.loan_interest_table', [
                'data'    => $rejectedData,
                'tabName' => 'rejected'
            ])
        </div>

    </div>
</div>

{{-- ✅ Accept Reason Popup --}}
<div class="modal fade" id="acceptReasonModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-success">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="bi bi-check-circle me-2"></i>Accept Interest — Add Reason</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="acceptReasonForm" method="POST">
                @csrf
                @method('PATCH')
                <input type="hidden" name="interest_amount" id="accept_interest_amount">
                <input type="hidden" name="balance_amount"  id="accept_balance_amount">
                <input type="hidden" name="debit_amount"    id="accept_debit_amount">
                <input type="hidden" name="credit_amount"   id="accept_credit_amount">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Reason <span class="text-danger">*</span></label>
                        <textarea name="interest_remark" class="form-control" rows="3"
                            placeholder="Enter reason for accepting this interest..." required></textarea>
                    </div>
                    <div class="alert alert-success py-2 mb-0" id="accept_summary_box" style="font-size:0.85rem;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success px-4">
                        <i class="bi bi-check-lg me-1"></i> Confirm Accept
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ✅ Reject Reason Popup --}}
<div class="modal fade" id="rejectReasonModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-danger">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="bi bi-x-circle me-2"></i>Reject Interest — Add Reason</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="rejectReasonForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Reason <span class="text-danger">*</span></label>
                        <textarea name="interest_remark" class="form-control" rows="3"
                            placeholder="Enter reason for rejecting this interest..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger px-4">
                        <i class="bi bi-x-lg me-1"></i> Confirm Reject
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openAcceptModal(loanId, acceptUrl, interestAmt, balanceAmt, debitAmt, creditAmt) {
    document.getElementById('acceptReasonForm').action = acceptUrl;
    document.getElementById('accept_interest_amount').value = interestAmt;
    document.getElementById('accept_balance_amount').value  = balanceAmt;
    document.getElementById('accept_debit_amount').value    = debitAmt;
    document.getElementById('accept_credit_amount').value   = creditAmt;
    document.getElementById('accept_summary_box').innerHTML =
        '💰 Interest: ₹' + parseFloat(interestAmt).toLocaleString('en-IN', {minimumFractionDigits:2}) +
        ' &nbsp;|&nbsp; Balance: ₹' + parseFloat(balanceAmt).toLocaleString('en-IN', {minimumFractionDigits:2});
    new bootstrap.Modal(document.getElementById('acceptReasonModal')).show();
}

function openRejectModal(loanId, rejectUrl) {
    document.getElementById('rejectReasonForm').action = rejectUrl;
    new bootstrap.Modal(document.getElementById('rejectReasonModal')).show();
}
</script>
@endsection