@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Bank Statement Entries</h2>

    @php
        use Carbon\Carbon;
        $groupedStatements = $bankStatements->groupBy('conform_ref_no');
    @endphp

    @foreach($groupedStatements as $refNo => $statements)

        @php
            $debitTotal  = $statements->where('cr_dr', 'DR')->sum('amount');
            $creditTotal = $statements->where('cr_dr', 'CR')->sum('amount');
            $minDebitDate  = $statements->where('cr_dr', 'DR')->min('date');
            $maxCreditDate = $statements->where('cr_dr', 'CR')->max('date');

            $daysDiff = 0;
            if ($minDebitDate && $maxCreditDate) {
                $daysDiff = Carbon::parse($minDebitDate)->diffInDays(Carbon::parse($maxCreditDate)) + 1;
            }

            $currentCategory = $category ?? null;
            $interestPercent = 0;
            $totalInterest   = 0;
            $totalAmount     = 0;
            $balanceAmount   = 0;
            $baseAmount      = 0;
            $baseLabel       = '';
            $totalLabel      = '';
            $balanceLabel    = '';

            if ($currentCategory === 'INVESTMENT' && isset($investor) && $investor) {
                $interestPercent = $investor->percentage ?? 0;
                $baseAmount      = $creditTotal;
                $baseLabel       = 'Credit Amount';
                $totalLabel      = 'Total Amount (Credit + Interest)';
                $balanceLabel    = 'Balance Amount (Total - Debit)';
                if ($interestPercent > 0) {
                    $totalInterest = ($interestPercent / 100) * $creditTotal * $daysDiff;
                    $totalAmount   = $creditTotal + $totalInterest;
                    $balanceAmount = $totalAmount - $debitTotal;
                }
            } elseif ($currentCategory === 'LOAN') {
                $currentLoan     = $loan ?? null;
                $interestPercent = $currentLoan->percentage ?? 0;
                $baseAmount      = $debitTotal;
                $baseLabel       = 'Debit Amount';
                $totalLabel      = 'Total Amount (Debit + Interest)';
                $balanceLabel    = 'Balance Amount (Credit - Total)';
                if ($interestPercent > 0) {
                    $totalInterest = ($interestPercent / 100) * $debitTotal * $daysDiff;
                    $totalAmount   = $debitTotal + $totalInterest;
                    $balanceAmount = $creditTotal - $totalAmount;
                }
            } else {
                $baseAmount   = $debitTotal;
                $baseLabel    = 'Debit Amount';
                $totalLabel   = 'Total Amount (Debit + Interest)';
                $balanceLabel = 'Balance Amount (Credit - Total)';
                if ($interestPercent > 0) {
                    $totalInterest = ($interestPercent / 100) * $debitTotal * $daysDiff;
                    $totalAmount   = $debitTotal + $totalInterest;
                    $balanceAmount = $creditTotal - $totalAmount;
                }
            }
        @endphp

        {{-- ===== EXPANSES ===== --}}
        @if($currentCategory === 'EXPANSES')
        <div class="card mb-4">
            <div class="card-header bg-warning">
                <h4>Reference No: {{ $refNo }}</h4>
                <span class="badge bg-warning text-dark">EXPANSES</span>
            </div>
            <div class="card-body">
                <h5>Bank Statement Details</h5>
                @foreach($statements as $statement)
                    <div class="row mb-2">
                        <div class="col-md-3"><strong>Date:</strong></div>
                        <div class="col-md-3">{{ \Carbon\Carbon::parse($statement->date)->format('d-m-Y') }}</div>
                        <div class="col-md-3"><strong>CR/DR:</strong></div>
                        <div class="col-md-3">
                            <span class="badge {{ $statement->cr_dr === 'CR' ? 'bg-success' : 'bg-danger' }}">{{ $statement->cr_dr }}</span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3"><strong>UTR No:</strong></div>
                        <div class="col-md-3">{{ $statement->utrno }}</div>
                        <div class="col-md-3"><strong>Amount:</strong></div>
                        <div class="col-md-3">₹{{ number_format($statement->amount, 2) }}</div>
                    </div>
                @endforeach
                <hr>
                <h5>Expense Details</h5>
                @if($extraData)
                    <div class="row mb-2">
                        <div class="col-md-4"><strong>Expense Type:</strong></div>
                        <div class="col-md-8">{{ $extraData->expense_type ?? '-' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4"><strong>Amount:</strong></div>
                        <div class="col-md-8">₹{{ number_format($extraData->amount, 2) }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4"><strong>Remark:</strong></div>
                        <div class="col-md-8">{{ $extraData->expanses_remark ?? '-' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4"><strong>Document:</strong></div>
                        <div class="col-md-8">
                            @if($extraData->document)
                                <a href="{{ asset('storage/' . $extraData->document) }}" target="_blank" class="btn btn-sm btn-primary">View Document</a>
                            @else -
                            @endif
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning">No expense details found.</div>
                @endif
            </div>
        {{-- ===== OD INTEREST ===== --}}
        @elseif(in_array($currentCategory, ['OD INTEREST', 'OD']) || \Illuminate\Support\Str::startsWith($refNo, 'OD'))
        @php
            $odBalance = $creditTotal - $debitTotal;
        @endphp
        <div class="card mb-4">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center flex-wrap" style="background: linear-gradient(135deg, #6f42c1, #4e2a84) !important;">
                <div>
                    <h4 class="mb-1 text-white">Reference No: <span>{{ $refNo }}</span></h4>
                    <span class="badge bg-light text-dark" style="font-size: 0.85rem; font-weight: 600;">OD INTEREST</span>
                </div>
            </div>
            <div class="card-body">

                {{-- Summary Section --}}
                <div class="row mb-4 p-3 bg-light rounded border shadow-sm">
                    <div class="col-md-4 mb-2 mb-md-0">
                        <label class="form-label text-muted fw-bold mb-1">Credited Amount</label>
                        <div class="fs-4 fw-bold text-success">₹{{ number_format($creditTotal, 2) }}</div>
                    </div>
                    <div class="col-md-4 mb-2 mb-md-0">
                        <label class="form-label text-muted fw-bold mb-1">Debited Amount</label>
                        <div class="fs-4 fw-bold text-danger">₹{{ number_format($debitTotal, 2) }}</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted fw-bold mb-1">Balance Amount (Credit - Debit)</label>
                        <div class="fs-4 fw-bold {{ $odBalance >= 0 ? 'text-success' : 'text-danger' }}">
                            ₹{{ number_format($odBalance, 2) }}
                        </div>
                    </div>
                </div>

                {{-- Credited Entries --}}
                <h5 class="mb-3 text-success"><i class="bi bi-arrow-down-left-circle"></i> Credited Entries</h5>
                <div class="table-responsive mb-4">
                    <table class="table table-bordered table-striped table-sm align-middle">
                        <thead class="table-light">
                            <tr><th>Date</th><th>UTR No</th><th>Remarks</th><th>Name</th><th class="text-end">Amount</th></tr>
                        </thead>
                        <tbody>
                            @forelse($statements->where('cr_dr', 'CR') as $credit)
                            <tr>
                                <td><input type="date" class="form-control form-control-sm" value="{{ $credit->date ? \Carbon\Carbon::parse($credit->date)->format('Y-m-d') : '' }}" readonly></td>
                                <td><input type="text" class="form-control form-control-sm" value="{{ $credit->utrno }}" readonly></td>
                                <td><textarea class="form-control form-control-sm" rows="2" readonly>{{ $credit->remarks }}</textarea></td>
                                <td><input type="text" class="form-control form-control-sm" value="{{ $credit->name }}" readonly></td>
                                <td><input type="text" class="form-control form-control-sm text-end fw-bold text-success" value="₹{{ number_format($credit->amount, 2) }}" readonly></td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-muted">No credited entries found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Debited Entries --}}
                <h5 class="mb-3 text-danger"><i class="bi bi-arrow-up-right-circle"></i> Debited Entries</h5>
                <div class="table-responsive mb-3">
                    <table class="table table-bordered table-striped table-sm align-middle">
                        <thead class="table-light">
                            <tr><th>Date</th><th>UTR No</th><th>Remarks</th><th>Name</th><th class="text-end">Amount</th></tr>
                        </thead>
                        <tbody>
                            @forelse($statements->where('cr_dr', 'DR') as $debit)
                            <tr>
                                <td><input type="date" class="form-control form-control-sm" value="{{ $debit->date ? \Carbon\Carbon::parse($debit->date)->format('Y-m-d') : '' }}" readonly></td>
                                <td><input type="text" class="form-control form-control-sm" value="{{ $debit->utrno }}" readonly></td>
                                <td><textarea class="form-control form-control-sm" rows="2" readonly>{{ $debit->remarks }}</textarea></td>
                                <td><input type="text" class="form-control form-control-sm" value="{{ $debit->name }}" readonly></td>
                                <td><input type="text" class="form-control form-control-sm text-end fw-bold text-danger" value="₹{{ number_format($debit->amount, 2) }}" readonly></td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-muted">No debited entries found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

        {{-- ===== OTHER CATEGORIES ===== --}}
        @else
        <div class="card mb-4">
            <div class="card-header">
                <h4>Reference No: <span class="text-primary">{{ $refNo ?? 'N/A' }}</span></h4>
                @if($currentCategory)
                    <span class="badge bg-info">{{ $currentCategory }}</span>
                @endif
                <p class="mb-0 mt-1">Days difference between debit date and credit date: {{ $daysDiff }} day(s)</p>
            </div>
            <div class="card-body">

                <form id="interestForm-{{ $refNo }}">
                    @csrf
                    <input type="hidden" id="category-{{ $refNo }}"   value="{{ $currentCategory }}" />
                    <input type="hidden" id="rawDebit-{{ $refNo }}"   value="{{ $debitTotal }}" />
                    <input type="hidden" id="rawCredit-{{ $refNo }}"  value="{{ $creditTotal }}" />

                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Interest Percentage (%)</label>
                        <div class="col-sm-4">
                            <input type="number" step="0.01"
                                   id="interestPercentage-{{ $refNo }}"
                                   class="form-control interest-percentage"
                                   placeholder="Enter interest %"
                                   value="{{ $interestPercent > 0 ? $interestPercent : '' }}" />
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">{{ $baseLabel }}</label>
                        <div class="col-sm-4">
                            <input type="text" id="baseAmount-{{ $refNo }}"
                                   value="{{ number_format($baseAmount, 2) }}"
                                   class="form-control" readonly />
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Days Difference</label>
                        <div class="col-sm-4">
                            <input type="text" id="daysDiff-{{ $refNo }}"
                                   value="{{ $daysDiff }}" class="form-control" readonly />
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">{{ $totalLabel }}</label>
                        <div class="col-sm-4">
                            <input type="text" id="totalAmount-{{ $refNo }}"
                                   class="form-control"
                                   value="{{ $totalAmount > 0 ? number_format($totalAmount, 2) : '' }}"
                                   readonly />
                        </div>
                    </div>

                    @if($currentCategory === 'INVESTMENT')
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Debited Amount</label>
                        <div class="col-sm-4">
                            <input type="text" id="otherAmount-{{ $refNo }}"
                                   value="{{ number_format($debitTotal, 2) }}"
                                   class="form-control" readonly />
                        </div>
                    </div>
                    @else
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Credited Amount</label>
                        <div class="col-sm-4">
                            <input type="text" id="otherAmount-{{ $refNo }}"
                                   value="{{ number_format($creditTotal, 2) }}"
                                   class="form-control" readonly />
                        </div>
                    </div>
                    @endif

                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">{{ $balanceLabel }}</label>
                        <div class="col-sm-4">
                            <input type="text" id="balanceAmount-{{ $refNo }}"
                                   class="form-control"
                                   value="{{ $totalAmount > 0 ? number_format($balanceAmount, 2) : '' }}"
                                   readonly />
                        </div>
                    </div>

                    {{-- ✅ LOAN SECTION --}}
                    @if($currentCategory === 'LOAN' && isset($loan) && $loan)
                        @php
                            $loanInterestStatus = $loan->interest_status ?? 'pending';
                            $simpleBalance = $creditTotal - $debitTotal;
                        @endphp

                        {{-- Simple Balance: Credit - Debit --}}
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label fw-bold">
                                 Calculate Interest (Credit - Debit)
                            </label>
                            <div class="col-sm-4">
                                <input type="text"
                                       class="form-control fw-bold {{ $simpleBalance >= 0 ? 'text-success' : 'text-danger' }}"
                                       value="₹{{ number_format($simpleBalance, 2) }}"
                                       readonly />
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <div class="col-sm-8 d-flex align-items-center gap-3 flex-wrap">

                                <!-- <button type="button" class="btn btn-primary"
                                        onclick="calculateLoanInterest('{{ $refNo }}')">
                                    <i class="bi bi-calculator"></i> Calculate Interest
                                </button> -->

                                <div id="balanceDisplay-{{ $refNo }}" style="display:none; align-items:center; gap:6px;">
                                    <span class="fw-bold">Balance (Credit - Total):</span>
                                    <span class="badge bg-secondary fs-6" id="balanceBadge-{{ $refNo }}">₹0.00</span>
                                </div>

                                @if($loanInterestStatus === 'accepted')
                                    <span class="badge bg-success fs-6 px-3 py-2">
                                        <i class="bi bi-check-circle-fill"></i> Accepted
                                    </span>
                                    <a href="{{ route('loan.interest.status', $loan->id) }}" class="btn btn-outline-success btn-sm">
                                        View Interest Status
                                    </a>
                                @elseif($loanInterestStatus === 'rejected')
                                    <span class="badge bg-danger fs-6 px-3 py-2">
                                        <i class="bi bi-x-circle-fill"></i> Rejected
                                    </span>
                                    <a href="{{ route('loan.interest.status', $loan->id) }}" class="btn btn-outline-danger btn-sm">
                                        View Interest Status
                                    </a>
                                @else
                                    <form method="POST" action="{{ route('loan.interest.accept', $loan->id) }}" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="interest_amount" id="hiddenInterest-{{ $refNo }}" value="0">
                                        <input type="hidden" name="balance_amount"  id="hiddenBalance-{{ $refNo }}"  value="0">
                                        <input type="hidden" name="debit_amount"    value="{{ $debitTotal }}">
                                        <input type="hidden" name="credit_amount"   value="{{ $creditTotal }}">
                                        <button type="submit" class="btn btn-success"
                                                onclick="fillHiddenValues('{{ $refNo }}'); return true;">
                                            <i class="bi bi-check-lg"></i> Accept
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('loan.interest.reject', $loan->id) }}" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-danger">
                                            <i class="bi bi-x-lg"></i> Reject
                                        </button>
                                    </form>
                                    <a href="{{ route('loan.interest.status', $loan->id) }}" class="btn btn-outline-secondary btn-sm">
                                        <i class="bi bi-eye"></i> View Status Page
                                    </a>
                                @endif

                            </div>
                        </div>
                    @endif

                </form>

                {{-- Credited Entries --}}
                <h5>Credited Entries</h5>
                <div class="table-responsive mb-3">
                    <table class="table table-bordered table-striped table-sm align-middle">
                        <thead class="table-light">
                            <tr><th>Date</th><th>UTR No</th><th>Remarks</th><th>Name</th><th>Amount</th></tr>
                        </thead>
                        <tbody>
                            @foreach($statements->where('cr_dr', 'CR') as $credit)
                            <tr>
                                <td><input type="date"   class="form-control form-control-sm" value="{{ $credit->date ? \Carbon\Carbon::parse($credit->date)->format('Y-m-d') : '' }}" readonly></td>
                                <td><input type="text"   class="form-control form-control-sm" value="{{ $credit->utrno }}" readonly></td>
                                <td><textarea class="form-control form-control-sm" rows="2" readonly>{{ $credit->remarks }}</textarea></td>
                                <td><input type="text"   class="form-control form-control-sm" value="{{ $credit->name }}" readonly></td>
                                <td><input type="number" class="form-control form-control-sm text-end" value="{{ $credit->amount }}" readonly></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Debited Entries --}}
                <h5>Debited Entries</h5>
                <div class="table-responsive mb-3">
                    <table class="table table-bordered table-striped table-sm align-middle">
                        <thead class="table-light">
                            <tr><th>Date</th><th>UTR No</th><th>Remarks</th><th>Name</th><th>Amount</th></tr>
                        </thead>
                        <tbody>
                            @foreach($statements->where('cr_dr', 'DR') as $debit)
                            <tr>
                                <td><input type="date"   class="form-control form-control-sm" value="{{ $debit->date ? \Carbon\Carbon::parse($debit->date)->format('Y-m-d') : '' }}" readonly></td>
                                <td><input type="text"   class="form-control form-control-sm" value="{{ $debit->utrno }}" readonly></td>
                                <td><textarea class="form-control form-control-sm" rows="2" readonly>{{ $debit->remarks }}</textarea></td>
                                <td><input type="text"   class="form-control form-control-sm" value="{{ $debit->name }}" readonly></td>
                                <td><input type="number" class="form-control form-control-sm text-end" value="{{ $debit->amount }}" readonly></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($currentCategory === 'INVESTMENT')
                    <h5 class="mt-4">Investor Details</h5>
                    @if($investor)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr><th style="width:30%">Investor Name</th><td>{{ $investor->name }}</td></tr>
                                    <tr><th>Interest %</th><td>{{ $investor->percentage }}%</td></tr>
                                    <tr><th>Investment Duration</th><td>{{ $investor->investment_duration }}</td></tr>
                                    <tr><th>Interest Amount</th><td>₹{{ number_format($totalInterest ?? 0, 2) }}</td></tr>
                                    <tr><th>Total Amount (Credit + Interest)</th><td>₹{{ number_format($totalAmount ?? 0, 2) }}</td></tr>
                                    <tr><th>Debited Amount</th><td>₹{{ number_format($debitTotal ?? 0, 2) }}</td></tr>
                                    <tr class="table-success">
                                        <th>Balance Amount (Total - Debit)</th>
                                        <td><strong>₹{{ number_format($balanceAmount ?? 0, 2) }}</strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        @include('bankstatement.partials.investment', ['investor' => $investor])
                    @else
                        <p class="text-muted">No investor details found.</p>
                    @endif
                @endif

                @if($currentCategory === 'LOAN')
                    @include('bankstatement.partials.loan', ['loan' => $loan])
                @endif

                @if($currentCategory === 'EXCESS')
                    @include('bankstatement.partials.excess', ['extraData' => $extraData ?? null])
                @endif

            </div>
        </div>
        @endif

    @endforeach

    @if($groupedStatements->isEmpty())
        <p>No bank statement entries found.</p>
    @endif
</div>

<script>
function calculateLoanInterest(refId) {
    let interestPercent = parseFloat(document.getElementById('interestPercentage-' + refId).value) || 0;
    let debitAmount     = parseFloat(document.getElementById('rawDebit-'  + refId).value) || 0;
    let creditAmount    = parseFloat(document.getElementById('rawCredit-' + refId).value) || 0;
    let days            = parseFloat(document.getElementById('daysDiff-'  + refId).value) || 0;

    let totalInterest = (interestPercent / 100) * debitAmount * days;
    let totalAmount   = debitAmount + totalInterest;

    // ✅ Balance = Credit - (Debit + Interest)
    let balanceAmount = creditAmount - totalAmount;

    document.getElementById('totalAmount-'   + refId).value = totalAmount.toFixed(2);
    document.getElementById('balanceAmount-' + refId).value = balanceAmount.toFixed(2);

    let balanceDisplay = document.getElementById('balanceDisplay-' + refId);
    let balanceBadge   = document.getElementById('balanceBadge-'   + refId);
    if (balanceDisplay && balanceBadge) {
        balanceBadge.textContent  = '₹' + balanceAmount.toFixed(2);
        balanceBadge.className    = balanceAmount >= 0 ? 'badge bg-success fs-6' : 'badge bg-danger fs-6';
        balanceDisplay.style.display     = 'inline-flex';
        balanceDisplay.style.alignItems  = 'center';
        balanceDisplay.style.gap         = '6px';
    }

    let hi = document.getElementById('hiddenInterest-' + refId);
    let hb = document.getElementById('hiddenBalance-'  + refId);
    if (hi) hi.value = totalInterest.toFixed(2);
    if (hb) hb.value = balanceAmount.toFixed(2);
}

function fillHiddenValues(refId) {
    let debitAmount  = parseFloat(document.getElementById('rawDebit-'  + refId).value) || 0;
    let creditAmount = parseFloat(document.getElementById('rawCredit-' + refId).value) || 0;
    let totalEl      = document.getElementById('totalAmount-'   + refId);
    let balanceEl    = document.getElementById('balanceAmount-' + refId);
    let totalAmount  = totalEl   ? parseFloat(totalEl.value.replace(/,/g,''))   || 0 : debitAmount;
    let balance      = balanceEl ? parseFloat(balanceEl.value.replace(/,/g,'')) || 0 : (creditAmount - debitAmount);
    let interest     = totalAmount - debitAmount;
    let hi = document.getElementById('hiddenInterest-' + refId);
    let hb = document.getElementById('hiddenBalance-'  + refId);
    if (hi) hi.value = interest.toFixed(2);
    if (hb) hb.value = balance.toFixed(2);
}

document.querySelectorAll('.interest-percentage').forEach(function(input) {
    input.addEventListener('input', function() {
        let refId    = this.id.replace('interestPercentage-', '');
        let category = document.getElementById('category-' + refId)?.value;
        if (category === 'LOAN') return;

        let interestPercent = parseFloat(this.value) || 0;
        let baseAmount  = parseFloat(document.getElementById('baseAmount-'  + refId).value.replace(/,/g,'')) || 0;
        let otherAmount = parseFloat(document.getElementById('otherAmount-' + refId).value.replace(/,/g,'')) || 0;
        let days        = parseFloat(document.getElementById('daysDiff-'    + refId).value) || 0;
        let totalInterest = (interestPercent / 100) * baseAmount * days;
        let totalAmount   = baseAmount + totalInterest;
        let balanceAmount = (category === 'INVESTMENT') ? totalAmount - otherAmount : otherAmount - totalAmount;
        document.getElementById('totalAmount-'   + refId).value = totalAmount.toFixed(2);
        document.getElementById('balanceAmount-' + refId).value = balanceAmount.toFixed(2);
    });
});
</script>
@endsection