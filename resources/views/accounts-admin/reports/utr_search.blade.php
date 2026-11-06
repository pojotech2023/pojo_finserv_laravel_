@extends('layouts.app')

@section('content')
<div class="container mt-5">
<div class="card shadow-lg border-0 rounded-4">

    {{-- HEADER --}}
    <div class="card-header bg-dark text-white text-center">
        <h4><i class="fas fa-search me-2"></i>UTR Number Search</h4>
    </div>

    <div class="card-body bg-light">

        {{-- ===== SEARCH FORM ===== --}}
        <form method="GET" action="{{ route('report.utr.search') }}" class="row g-2 mb-4">
            <div class="col-md-4">
                <label class="form-label fw-semibold">UTR Number</label>
                <input type="text"
                       name="utr_no"
                       class="form-control form-control-lg"
                       placeholder="Enter UTR No to search..."
                       value="{{ $utrNo ?? '' }}"
                       autofocus>
            </div>
            <div class="col-md-2 align-self-end">
                <button type="submit" class="btn btn-dark btn-lg w-100">
                    <i class="fas fa-search me-1"></i> Search
                </button>
            </div>

            {{-- Download button - only show when result exists --}}
            @if(!empty($reportData))
            <div class="col-md-2 align-self-end">
                <button type="submit"
                        name="export"
                        value="excel"
                        class="btn btn-success btn-lg w-100">
                    <i class="fas fa-file-excel me-1"></i> Download
                </button>
            </div>
            @endif
        </form>

        {{-- ===== NOT FOUND ===== --}}
        @if($utrNo && !$utrRecord)
        <div class="alert alert-warning text-center">
            <i class="fas fa-exclamation-triangle me-2"></i>
            UTR No <strong>{{ $utrNo }}</strong> - எந்த record-உம் கிடைக்கவில்லை.
        </div>
        @endif

        {{-- ===== RESULTS ===== --}}
        @if(!empty($reportData))

        {{-- UTR Record Found Banner --}}
        <div class="alert alert-info mb-3">
            <div class="row">
                <div class="col-md-3">
                    <small class="text-muted">UTR Searched</small>
                    <div class="fw-bold">{{ $utrNo }}</div>
                </div>
                <div class="col-md-3">
                    <small class="text-muted">Conform Ref No</small>
                    <div class="fw-bold">{{ $reportData['ref_no'] }}</div>
                </div>
                <div class="col-md-3">
                    <small class="text-muted">Category</small>
                    <div class="fw-bold">{{ ucfirst($reportData['category']) }}</div>
                </div>
                <div class="col-md-3">
                    <small class="text-muted">Total Transactions</small>
                    <div class="fw-bold">{{ $reportData['details']->count() }}</div>
                </div>
            </div>
        </div>

        {{-- ===== SUMMARY CARDS ===== --}}
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="p-3 bg-success text-white text-center rounded shadow-sm">
                    <small>Total Credit</small>
                    <h5 class="mb-0">₹{{ number_format($totalCredit, 2) }}</h5>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 bg-danger text-white text-center rounded shadow-sm">
                    <small>Total Debit</small>
                    <h5 class="mb-0">₹{{ number_format($totalDebit, 2) }}</h5>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 bg-warning text-dark text-center rounded shadow-sm">
                    <small>Total Interest</small>
                    <h5 class="mb-0">₹{{ number_format($totalInterest, 2) }}</h5>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 {{ $totalProfit >= 0 ? 'bg-primary' : 'bg-secondary' }} text-white text-center rounded shadow-sm">
                    <small>Profit / Loss</small>
                    <h5 class="mb-0">₹{{ number_format($totalProfit, 2) }}</h5>
                </div>
            </div>
        </div>

        {{-- ===== CALCULATED DETAILS TABLE ===== --}}
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-calculator me-2"></i>Calculation Summary — {{ $reportData['ref_no'] }}
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered mb-0">
                    <tbody>
                        @if($reportData['category'] === 'investment')
                        <tr>
                            <th class="bg-light" width="30%">Investor Name</th>
                            <td>{{ $reportData['investor_name'] ?? '-' }}</td>
                            <th class="bg-light" width="30%">Percentage</th>
                            <td>{{ $reportData['percentage'] }}%</td>
                        </tr>
                        <tr>
                            <th class="bg-light">No of Days</th>
                            <td>{{ $reportData['no_of_days'] }}</td>
                            <th class="bg-light">Interest</th>
                            <td class="text-warning fw-bold">₹{{ number_format($reportData['interest'], 2) }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Total Credit</th>
                            <td class="text-success fw-bold">₹{{ number_format($reportData['credit'], 2) }}</td>
                            <th class="bg-light">Total Debit</th>
                            <td class="text-danger fw-bold">₹{{ number_format($reportData['debit'], 2) }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Short / Excess Commission</th>
                            <td colspan="3" class="fw-bold fs-5 {{ $reportData['short_excess'] >= 0 ? 'text-success' : 'text-danger' }}">
                                ₹{{ number_format($reportData['short_excess'], 2) }}
                                <span class="badge {{ $reportData['short_excess'] >= 0 ? 'bg-success' : 'bg-danger' }} ms-2">
                                    {{ $reportData['short_excess'] >= 0 ? 'Excess' : 'Short' }}
                                </span>
                            </td>
                        </tr>

                        @elseif($reportData['category'] === 'loan')
                        <tr>
                            <th class="bg-light" width="30%">Bank Name</th>
                            <td>{{ $reportData['bank_name'] ?? '-' }}</td>
                            <th class="bg-light" width="30%">Staff Name</th>
                            <td>{{ $reportData['staff_name'] ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Customer Name</th>
                            <td>{{ $reportData['customer_name'] ?? '-' }}</td>
                            <th class="bg-light">Percentage</th>
                            <td>{{ $reportData['percentage'] }}%</td>
                        </tr>
                        <tr>
                            <th class="bg-light">No of Days</th>
                            <td>{{ $reportData['no_of_days'] }}</td>
                            <th class="bg-light">Interest</th>
                            <td class="text-warning fw-bold">₹{{ number_format($reportData['interest'], 2) }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Total Credit</th>
                            <td class="text-success fw-bold">₹{{ number_format($reportData['credit'], 2) }}</td>
                            <th class="bg-light">Total Debit</th>
                            <td class="text-danger fw-bold">₹{{ number_format($reportData['debit'], 2) }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Received Commission</th>
                            <td class="fw-bold">₹{{ number_format($reportData['received_commission'], 2) }}</td>
                            <th class="bg-light">Short / Excess</th>
                            <td class="fw-bold {{ $reportData['short_excess'] >= 0 ? 'text-success' : 'text-danger' }}">
                                ₹{{ number_format($reportData['short_excess'], 2) }}
                                <span class="badge {{ $reportData['short_excess'] >= 0 ? 'bg-success' : 'bg-danger' }} ms-2">
                                    {{ $reportData['short_excess'] >= 0 ? 'Excess' : 'Short' }}
                                </span>
                            </td>
                        </tr>

                        @else
                        <tr>
                            <th class="bg-light" width="30%">Total Credit</th>
                            <td class="text-success fw-bold">₹{{ number_format($reportData['credit'], 2) }}</td>
                            <th class="bg-light" width="30%">Total Debit</th>
                            <td class="text-danger fw-bold">₹{{ number_format($reportData['debit'], 2) }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Profit / Loss</th>
                            <td colspan="3" class="fw-bold fs-5 {{ $reportData['profit'] >= 0 ? 'text-success' : 'text-danger' }}">
                                ₹{{ number_format($reportData['profit'], 2) }}
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ===== ALL TRANSACTIONS TABLE ===== --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-secondary text-white">
                <i class="fas fa-list me-2"></i>All Transactions for Ref No: {{ $reportData['ref_no'] }}
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle mb-0">
                        <thead class="table-dark text-center">
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>UTR No</th>
                                <th>Conform Ref No</th>
                                <th>Remarks</th>
                                <th>Name</th>
                                <th>CR / DR</th>
                                <th class="text-end">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $sl = 1; $crTotal = 0; $drTotal = 0; @endphp
                            @foreach($reportData['details'] as $row)
                            <tr class="{{ $row->utr_no == $utrNo ? 'table-warning' : '' }}">
                                <td class="text-center text-muted">{{ $sl++ }}</td>
                                <td>{{ \Carbon\Carbon::parse($row->date)->format('d-m-Y') }}</td>
                                <td>
                                    {{ $row->utr_no }}
                                    @if($row->utr_no == $utrNo)
                                        <span class="badge bg-warning text-dark ms-1">Searched</span>
                                    @endif
                                </td>
                                <td><span class="badge bg-info text-dark">{{ $row->conform_ref_no }}</span></td>
                                <td>{{ $row->remarks }}</td>
                                <td>{{ $row->name }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $row->cr_dr == 'CR' ? 'bg-success' : 'bg-danger' }}">
                                        {{ $row->cr_dr }}
                                    </span>
                                </td>
                                <td class="text-end {{ $row->cr_dr == 'CR' ? 'text-success' : 'text-danger' }} fw-semibold">
                                    ₹{{ number_format($row->amount, 2) }}
                                    @php
                                        if($row->cr_dr == 'CR') $crTotal += $row->amount;
                                        else $drTotal += $row->amount;
                                    @endphp
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-dark">
                            <tr>
                                <th colspan="6" class="text-end">Totals:</th>
                                <th class="text-center text-success">CR: ₹{{ number_format($crTotal, 2) }}</th>
                                <th class="text-end text-danger">DR: ₹{{ number_format($drTotal, 2) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        @endif

    </div>
</div>
</div>
@endsection