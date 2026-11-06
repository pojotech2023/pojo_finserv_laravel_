@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Loan Interest Status</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('loan.interest.list') }}" class="btn btn-outline-primary">
                <i class="bi bi-list-ul"></i> All Loans Interest List
            </a>
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
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @php
        $status      = $loan->interest_status ?? 'pending';
        $statusColor = match($status) {
            'accepted' => 'success',
            'rejected' => 'danger',
            default    => 'warning',
        };
        $statusIcon = match($status) {
            'accepted' => 'bi-check-circle-fill',
            'rejected' => 'bi-x-circle-fill',
            default    => 'bi-clock-fill',
        };
    @endphp

    {{-- ===== STATUS HEADER ===== --}}
    <div class="card mb-4 border-{{ $statusColor }}">
        <div class="card-header bg-{{ $statusColor }} {{ $status === 'pending' ? 'text-dark' : 'text-white' }} d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bi {{ $statusIcon }} me-2"></i>
                Interest Status: <span class="text-uppercase fw-bold">{{ $status }}</span>
            </h5>
            <span class="badge bg-light text-dark">Ref No: {{ $conformRefNo ?? 'N/A' }}</span>
        </div>

        <div class="card-body">

            {{-- Loan Details --}}
            <h5 class="mb-3">Loan Details</h5>
            <div class="table-responsive mb-4">
                <table class="table table-bordered table-sm">
                    <tbody>
                        <tr><th style="width:35%">Loan ID</th><td>{{ $loan->id }}</td></tr>
                        <tr><th>Bank Name</th><td>{{ $loan->bank_name ?? '-' }}</td></tr>
                        <tr><th>Staff Name</th><td>{{ $loan->staff->staff_name ?? $loan->staff_name ?? '-' }}</td></tr>
                        <tr><th>Customer Name</th><td>{{ $loan->customer_name ?? '-' }}</td></tr>
                        <tr><th>Interest %</th><td>{{ $loan->percentage ?? '0' }}%</td></tr>
                        <tr><th>Conform Ref No</th><td>{{ $conformRefNo ?? '-' }}</td></tr>
                        <tr><th>Loan Date</th><td>{{ $loan->created_at ? \Carbon\Carbon::parse($loan->created_at)->format('d-m-Y') : '-' }}</td></tr>
                    </tbody>
                </table>
            </div>

            {{-- Interest Calculation Summary --}}
            @if($interestSummary)
            <h5 class="mb-3">Interest Calculation Summary</h5>
            <div class="table-responsive mb-4">
                <table class="table table-bordered table-sm">
                    <tbody>
                        <tr>
                            <th style="width:40%">Debit Amount (Loan Given)</th>
                            <td class="text-danger fw-bold">₹{{ number_format($interestSummary['debit_total'], 2) }}</td>
                        </tr>
                        <tr>
                            <th>Credit Amount (Received)</th>
                            <td class="text-success fw-bold">₹{{ number_format($interestSummary['credit_total'], 2) }}</td>
                        </tr>
                        <tr>
                            <th>Simple Balance (Credit - Debit)</th>
                            <td class="fw-bold {{ $interestSummary['simple_balance'] >= 0 ? 'text-success' : 'text-danger' }}">
                                ₹{{ number_format($interestSummary['simple_balance'], 2) }}
                            </td>
                        </tr>
                        <tr><th>Days Difference</th><td>{{ $interestSummary['days_diff'] }} day(s)</td></tr>
                        <tr><th>Interest % Per Day</th><td>{{ $interestSummary['interest_percent'] }}%</td></tr>
                        <tr>
                            <th>Interest Amount</th>
                            <td>
                                @if($status === 'accepted' && $loan->interest_amount > 0)
                                    ₹{{ number_format($loan->interest_amount, 2) }}
                                    <small class="text-muted">(saved at accept)</small>
                                @else
                                    ₹{{ number_format($interestSummary['total_interest'], 2) }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Total Amount (Debit + Interest)</th>
                            <td>₹{{ number_format($interestSummary['total_amount'], 2) }}</td>
                        </tr>

                        @if($status === 'accepted')
                        <tr class="table-success">
                            <th>Balance Amount (Cleared)</th>
                            <td>
                                <strong>₹{{ number_format($loan->balance_amount > 0 ? $loan->balance_amount : $interestSummary['balance_amount'], 2) }}</strong>
                                <span class="badge bg-success ms-2"><i class="bi bi-check-circle-fill"></i> No Pending Interest</span>
                            </td>
                        </tr>
                        @elseif($status === 'rejected')
                        <tr class="table-danger">
                            <th>Balance Amount</th>
                            <td>
                                <strong>₹{{ number_format($interestSummary['balance_amount'], 2) }}</strong>
                                <span class="badge bg-danger ms-2">Rejected</span>
                            </td>
                        </tr>
                        @else
                        <tr class="table-warning">
                            <th>Pending Interest (Balance)</th>
                            <td>
                                <strong>₹{{ number_format($interestSummary['balance_amount'], 2) }}</strong>
                                <span class="badge bg-warning text-dark ms-2"><i class="bi bi-clock-fill"></i> Pending</span>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            @endif

            {{-- ✅ Action History + Reason Display --}}
            @if($loan->interest_updated_at || $loan->interest_remark)
            <div class="alert alert-{{ $statusColor }} {{ $status === 'pending' ? 'text-dark' : '' }} mb-4">
                <div class="d-flex align-items-start gap-2">
                    <i class="bi bi-info-circle mt-1"></i>
                    <div>
                        @if($loan->interest_updated_at)
                        <div>
                            Interest was <strong>{{ $status }}</strong> on
                            <strong>{{ \Carbon\Carbon::parse($loan->interest_updated_at)->format('d-m-Y H:i') }}</strong>
                        </div>
                        @endif
                        @if($loan->interest_remark)
                        <div class="mt-2 p-2 rounded" style="background:rgba(0,0,0,0.06);">
                            <i class="bi bi-chat-quote me-1"></i>
                            <strong>Reason:</strong> {{ $loan->interest_remark }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            {{-- ✅ Pending: Accept/Reject with reason popup --}}
            @if($status === 'pending')
            <div class="d-flex gap-3 mt-3 mb-4">
                {{-- Accept Button → popup --}}
                <button type="button" class="btn btn-success btn-lg px-4"
                    onclick="openAcceptModal(
                        {{ $loan->id }},
                        '{{ route('loan.interest.accept', $loan->id) }}',
                        '{{ $interestSummary['total_interest'] ?? 0 }}',
                        '{{ $interestSummary['balance_amount'] ?? 0 }}',
                        '{{ $interestSummary['debit_total'] ?? 0 }}',
                        '{{ $interestSummary['credit_total'] ?? 0 }}'
                    )">
                    <i class="bi bi-check-lg me-2"></i> Accept Interest
                </button>

                {{-- Reject Button → popup --}}
                <button type="button" class="btn btn-danger btn-lg px-4"
                    onclick="openRejectModal({{ $loan->id }}, '{{ route('loan.interest.reject', $loan->id) }}')">
                    <i class="bi bi-x-lg me-2"></i> Reject Interest
                </button>
            </div>
            @endif

        </div>
    </div>

    {{-- ===== ALL BANK STATEMENTS ===== --}}
    @if($allStatements->isNotEmpty())
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Bank Statement Entries (Ref No: {{ $conformRefNo }})</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-sm align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>UTR No</th>
                            <th>Name</th>
                            <th>Remarks</th>
                            <th>CR/DR</th>
                            <th class="text-end">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allStatements->sortBy('date') as $i => $stmt)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $stmt->date ? \Carbon\Carbon::parse($stmt->date)->format('d-m-Y') : '-' }}</td>
                            <td>{{ $stmt->utrno ?? '-' }}</td>
                            <td>{{ $stmt->name ?? '-' }}</td>
                            <td>{{ $stmt->remarks ?? '-' }}</td>
                            <td>
                                <span class="badge {{ $stmt->cr_dr === 'CR' ? 'bg-success' : 'bg-danger' }}">
                                    {{ $stmt->cr_dr }}
                                </span>
                            </td>
                            <td class="text-end">₹{{ number_format($stmt->amount, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light fw-bold">
                        <tr>
                            <td colspan="6" class="text-end">Total Debit:</td>
                            <td class="text-end text-danger">₹{{ number_format($interestSummary['debit_total'] ?? 0, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="6" class="text-end">Total Credit:</td>
                            <td class="text-end text-success">₹{{ number_format($interestSummary['credit_total'] ?? 0, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    @endif

    {{-- ===== ALL LOANS LINKED TO THIS REF NO ===== --}}
    @if($allLoans->isNotEmpty())
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">All Loans - Ref No: {{ $conformRefNo }}</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-sm align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Loan ID</th>
                            <th>Bank Name</th>
                            <th>Staff Name</th>
                            <th>Customer Name</th>
                            <th class="text-center">Interest %</th>
                            <th class="text-end">Interest Amt</th>
                            <th class="text-end">Balance Amt</th>
                            <th class="text-center">Status</th>
                            <th>Reason</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allLoans as $i => $loanItem)
                        @php
                            $lStatus   = $loanItem->interest_status ?? 'pending';
                            $rowClass  = $loanItem->id === $loan->id ? 'table-primary' : '';
                            $lColor    = match($lStatus) { 'accepted' => 'success', 'rejected' => 'danger', default => 'warning' };
                        @endphp
                        <tr class="{{ $rowClass }}">
                            <td>{{ $i + 1 }}</td>
                            <td>
                                {{ $loanItem->id }}
                                @if($loanItem->id === $loan->id)
                                    <span class="badge bg-primary ms-1">Current</span>
                                @endif
                            </td>
                            <td>{{ $loanItem->bank_name ?? '-' }}</td>
                            <td>{{ $loanItem->staff->staff_name ?? $loanItem->staff_name ?? '-' }}</td>
                            <td>{{ $loanItem->customer_name ?? '-' }}</td>
                            <td class="text-center">{{ $loanItem->percentage ?? '0' }}%</td>
                            <td class="text-end">
                                @if($lStatus === 'accepted' && $loanItem->interest_amount > 0)
                                    ₹{{ number_format($loanItem->interest_amount, 2) }}
                                @elseif($interestSummary)
                                    ₹{{ number_format($interestSummary['total_interest'], 2) }}
                                @else -
                                @endif
                            </td>
                            <td class="text-end fw-bold">
                                @if($lStatus === 'accepted')
                                    <span class="text-success">₹{{ number_format($loanItem->balance_amount, 2) }}</span>
                                @elseif($interestSummary)
                                    <span class="{{ $interestSummary['balance_amount'] >= 0 ? 'text-success' : 'text-danger' }}">
                                        ₹{{ number_format($interestSummary['balance_amount'], 2) }}
                                    </span>
                                @else -
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-{{ $lColor }} {{ $lStatus === 'pending' ? 'text-dark' : '' }}">
                                    {{ strtoupper($lStatus) }}
                                </span>
                            </td>
                            {{-- ✅ Reason column --}}
                            <td>
                                @if($loanItem->interest_remark)
                                    <span class="text-muted small">{{ $loanItem->interest_remark }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('loan.interest.status', $loanItem->id) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> View
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

</div>

{{-- ✅ Accept Reason Modal --}}
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
                            placeholder="Enter reason for accepting..." required></textarea>
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

{{-- ✅ Reject Reason Modal --}}
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
                            placeholder="Enter reason for rejecting..." required></textarea>
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