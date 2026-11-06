@extends('layouts.app')

@section('content')
<div class="container mt-5">
<div class="card shadow-lg border-0 rounded-4">

    {{-- HEADER --}}
    <div class="card-header bg-primary text-white text-center">
        <h4>{{ $category === 'selftransaction' ? 'Self Transaction' : ucfirst($category) }} Balance Report</h4>
    </div>

    <div class="card-body bg-light">

        {{-- ================= FILTER FORM ================= --}}
        <form method="GET"
              action="{{ route('report.category.balance', $category) }}"
              class="mb-4">

            <div class="row g-2 align-items-end">

                {{-- From Date --}}
                <div class="col-md-2 col-sm-6">
                    <label class="form-label fw-semibold mb-1" style="font-size:13px;">From Date</label>
                    <input type="date" name="from_date" class="form-control form-control-sm"
                           value="{{ request('from_date') }}">
                </div>

                {{-- To Date --}}
                <div class="col-md-2 col-sm-6">
                    <label class="form-label fw-semibold mb-1" style="font-size:13px;">To Date</label>
                    <input type="date" name="to_date" class="form-control form-control-sm"
                           value="{{ request('to_date') }}">
                </div>

                {{-- Ref No --}}
                <div class="col-md-2 col-sm-6">
                    <label class="form-label fw-semibold mb-1" style="font-size:13px;">Ref No</label>
                    <input type="text" name="ref_no" class="form-control form-control-sm"
                           placeholder="INV / LOA"
                           value="{{ request('ref_no') }}">
                </div>

                {{-- CR/DR --}}
                <div class="col-md-2 col-sm-6">
                    <label class="form-label fw-semibold mb-1" style="font-size:13px;">CR / DR</label>
                    <select name="cr_dr" class="form-select form-select-sm">
                        <option value="">All</option>
                        <option value="CR" {{ request('cr_dr') == 'CR' ? 'selected' : '' }}>Credit</option>
                        <option value="DR" {{ request('cr_dr') == 'DR' ? 'selected' : '' }}>Debit</option>
                    </select>
                </div>

                {{-- UTR No --}}
                <div class="col-md-2 col-sm-6">
                    <label class="form-label fw-semibold mb-1" style="font-size:13px;">UTR No</label>
                    <input type="text" name="utr_no" class="form-control form-control-sm"
                           placeholder="Enter UTR No"
                           value="{{ request('utr_no') }}">
                </div>

                {{-- Percentage --}}
                <div class="col-md-2 col-sm-6">
                    <label class="form-label fw-semibold mb-1" style="font-size:13px;">Percentage (%)</label>
                    <input type="number" name="percentage" class="form-control form-control-sm"
                           placeholder="e.g. 0.35" step="0.01"
                           value="{{ request('percentage') }}">
                </div>

                {{-- INVESTMENT SPECIFIC --}}
                @if($category === 'investment')
                <div class="col-md-3 col-sm-6">
                    <label class="form-label fw-semibold mb-1" style="font-size:13px;">Investor Name</label>
                    <input type="text" name="investor_name" class="form-control form-control-sm"
                           placeholder="Search investor name"
                           value="{{ request('investor_name') }}">
                </div>
                @endif

                {{-- LOAN SPECIFIC --}}
                @if($category === 'loan')
                <div class="col-md-3 col-sm-6">
                    <label class="form-label fw-semibold mb-1" style="font-size:13px;">Staff Name</label>
                    <input type="text" name="staff_name" class="form-control form-control-sm"
                           placeholder="Search staff name"
                           value="{{ request('staff_name') }}">
                </div>
                <div class="col-md-3 col-sm-6">
                    <label class="form-label fw-semibold mb-1" style="font-size:13px;">Bank Name</label>
                    <input type="text" name="bank_name" class="form-control form-control-sm"
                           placeholder="Search bank name"
                           value="{{ request('bank_name') }}">
                </div>
                @endif

            </div>

            {{-- Buttons below filters --}}
            <div class="row mt-2">
                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-search"></i> Filter
                    </button>
                    <button type="submit" name="export" value="excel" class="btn btn-success btn-sm">
                        <i class="bi bi-file-earmark-excel"></i> Excel
                    </button>
                    <a href="{{ route('report.category.balance', $category) }}"
                       class="btn btn-outline-danger btn-sm">
                        <i class="bi bi-x-circle"></i> Reset
                    </a>
                </div>
            </div>

        </form>

        {{-- ================= SUMMARY CARDS ================= --}}
        @if(count($reportData))
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="p-3 bg-success text-white text-center rounded">
                    <div>Total Credit</div>
                    <h4>₹{{ number_format($totalCredit, 2) }}</h4>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 bg-danger text-white text-center rounded">
                    <div>Total Debit</div>
                    <h4>₹{{ number_format($totalDebit, 2) }}</h4>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 bg-primary text-white text-center rounded">
                    <div>Total Interest</div>
                    <h4>₹{{ number_format($totalInterest, 2) }}</h4>
                </div>
            </div>
            {{-- ✅ Received Interest Summary Card (investment & loan only) --}}
            @if(in_array($category, ['investment', 'loan']))
            <div class="col-md-3">
                <div class="p-3 text-white text-center rounded"
                     style="background-color: #6f42c1;">
                    <div>Total Received Interest</div>
                    <h4>₹{{ number_format($totalReceivedInterest, 2) }}</h4>
                </div>
            </div>
            @endif
        </div>
        @endif

        {{-- ================= TABLE ================= --}}
        <div class="table-responsive">
        <table class="table table-bordered align-middle">

            <thead class="table-primary text-center">
            <tr>
                <th>Ref No</th>

                @if($category === 'investment')
                    <th>Investor Name</th>
                    <th>Percentage</th>
                    <th>No of Days</th>
                    <th>Interest</th>
                    <th>Short/Excess Commission</th>
                    <th style="background:#e8d5ff; color:#5a0096;">Received Interest</th>  {{-- ✅ NEW --}}
                @elseif($category === 'loan')
                    <th>Bank Name</th>
                    <th>Staff Name</th>
                    <th>Percentage</th>
                    <th>No of Days</th>
                    <th>Interest</th>
                    <th>Received Commission</th>
                    <th>Short/Excess</th>
                    <th style="background:#e8d5ff; color:#5a0096;">Received Interest</th>  {{-- ✅ NEW --}}
                @endif

                <th>Total Credit</th>
                <th>Total Debit</th>
                <th>Interest</th>
            </tr>
            </thead>

            <tbody>
            @forelse($reportData as $data)

            {{-- SUMMARY ROW --}}
            <tr class="table-info fw-bold">
                <td>{{ $data['ref_no'] }}</td>

                @if($category === 'investment')
                    <td>{{ $data['investor_name'] ?? '-' }}</td>
                    <td class="text-center">{{ $data['percentage'] }}%</td>
                    <td class="text-center">{{ $data['no_of_days'] }}</td>
                    <td class="text-end">₹{{ number_format($data['interest'], 2) }}</td>
                    <td class="text-end fw-bold {{ $data['short_excess'] >= 0 ? 'text-success' : 'text-danger' }}">
                        ₹{{ number_format($data['short_excess'], 2) }}
                    </td>
                    {{-- ✅ Received Interest for Investment --}}
                    <td class="text-end fw-bold" style="color:#5a0096;">
                        ₹{{ number_format($data['received_interest'], 2) }}
                        <br>
                        <small class="text-muted fw-normal" style="font-size:10px;">
                            (Debit - Credit)
                        </small>
                    </td>

                @elseif($category === 'loan')
                    <td>{{ $data['bank_name'] ?? '-' }}</td>
                    <td>{{ $data['staff_name'] ?? '-' }}</td>
                    <td class="text-center">{{ $data['percentage'] }}%</td>
                    <td class="text-center">{{ $data['no_of_days'] }}</td>
                    <td class="text-end">₹{{ number_format($data['interest'], 2) }}</td>
                    <td class="text-end">₹{{ number_format($data['received_commission'], 2) }}</td>
                    <td class="text-end fw-bold {{ $data['short_excess'] >= 0 ? 'text-success' : 'text-danger' }}">
                        ₹{{ number_format($data['short_excess'], 2) }}
                    </td>
                    {{-- ✅ Received Interest for Loan --}}
                    <td class="text-end fw-bold" style="color:#5a0096;">
                        ₹{{ number_format($data['received_interest'], 2) }}
                        <br>
                        <small class="text-muted fw-normal" style="font-size:10px;">
                            (Credit - Debit)
                        </small>
                    </td>
                @endif

                <td class="text-end text-success">₹{{ number_format($data['credit'], 2) }}</td>
                <td class="text-end text-danger">₹{{ number_format($data['debit'], 2) }}</td>
                <td class="text-end text-primary fw-bold">₹{{ number_format($data['interest'], 2) }}</td>
            </tr>

            {{-- DETAILS ROW --}}
            <tr>
                <td colspan="{{ $category === 'investment' ? 10 : ($category === 'loan' ? 12 : 4) }}" class="p-0">
                    <div class="p-3 bg-light">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="table-secondary">
                        <tr>
                            <th>Date</th>
                            <th>UTR</th>
                            <th>Remarks</th>
                            <th>Name</th>
                            <th>CR/DR</th>
                            <th class="text-end">Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data['details'] as $row)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($row->date)->format('d-m-Y') }}</td>
                            <td>{{ $row->utrno }}</td>
                            <td>{{ $row->remarks }}</td>
                            <td>{{ $row->name }}</td>
                            <td>
                                <span class="badge {{ $row->cr_dr == 'CR' ? 'bg-success' : 'bg-danger' }}">
                                    {{ $row->cr_dr }}
                                </span>
                            </td>
                            <td class="text-end">₹{{ number_format($row->amount, 2) }}</td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                    </div>
                </td>
            </tr>

            @empty
            <tr>
                <td colspan="12" class="text-center text-muted py-4">
                    <i class="fas fa-inbox fa-2x mb-2"></i><br>
                    No records found
                </td>
            </tr>
            @endforelse
            </tbody>

        </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-3 d-flex justify-content-end">
            {{ $reportData->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>

    </div>
</div>
</div>
@endsection