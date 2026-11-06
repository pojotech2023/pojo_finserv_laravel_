@extends('layouts.app')

@section('content')
<style>
    .remarks-cell {
        word-wrap: break-word;
        white-space: normal;
        max-width: 150px;
    }
    /* autocomplete wrapper: suggestion box positions relative to the cell */
    .autocomplete-wrapper {
        position: relative;
    }
    .suggestion-box {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        z-index: 2000;
        display: none;
        max-height: 180px;
        overflow-y: auto;
    }
    .suggestion-item {
        cursor: pointer;
    }
    
</style>

<div class="container mt-4">
   export.uncategorized.debit

   
 <form action="{{ route('bank.uncategorizedDebitReport') }}" method="GET" class="row g-2 mb-3">
    <div class="col-md-2">
        <label for="bank_name" class="form-label">Bank Name</label>
        <select name="bank_name" id="bank_name" class="form-select">
            <option value="">All Banks</option>
            <option value="AXIS" {{ request('bank_name') == 'AXIS' ? 'selected' : '' }}>AXIS BANK</option>
            <option value="HDFC" {{ request('bank_name') == 'HDFC' ? 'selected' : '' }}>HDFC BANK</option>
            <option value="YES BANK" {{ request('bank_name') == 'YES BANK' ? 'selected' : '' }}>YES BANK</option>
            <option value="ICICI" {{ request('bank_name') == 'ICICI' ? 'selected' : '' }}>ICICI BANK</option>
        </select>
    </div>
    <div class="col-md-3">
        <label for="from_date" class="form-label">From Date</label>
        <input type="date" name="from_date" id="from_date" class="form-control" value="{{ request('from_date') }}">
    </div>
    <div class="col-md-3">
        <label for="to_date" class="form-label">To Date</label>
        <input type="date" name="to_date" id="to_date" class="form-control" value="{{ request('to_date') }}">
    </div>
    <div class="col-md-2 align-self-end">
        <button type="submit" class="btn btn-primary w-100">
            Filter
        </button>
    </div>

    <div class="col-md-2 align-self-end">
        <a href="{{ route('export.uncategorized.debit', request()->all()) }}"
           class="btn btn-success w-100">
            Export Excel
        </a>
    </div>
   
</form>

    <div class="card shadow-sm mt-4">
        <div class="card-header bg-primary text-white fw-bold">
            Uncategorized Debit Bank Statements
        </div>


        <div class="card-body p-0">
 <table id="statements-table" class="table table-bordered table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Date</th>
                        <th>Bank</th>
                        <th>UTR No</th>
                        <th>Remarks</th>
                        <th>Name</th>
                        <th class="text-end">Amount</th>
                        <th>CR/DR</th>
                    </tr>
                </thead>
                <tbody>
@forelse($statements as $statement)

    <tr data-id="{{ $statement->id }}">
        <td>{{ \Carbon\Carbon::parse($statement->date)->format('d-m-Y') }}</td>
        <td><span class="badge bg-secondary font-monospace">{{ $statement->bank_name ?? 'N/A' }}</span></td>
        <td>{{ $statement->utrno }}</td>
        <td class="remarks-cell">{{ $statement->remarks }}</td>
        <td>{{ $statement->name }}</td>
        <td class="text-end">{{ number_format($statement->amount, 2) }}</td>
        <td>
            @if($statement->cr_dr == 'CR')
                <span class="badge bg-success">CR</span>
            @else
                <span class="badge bg-danger">DR</span>
            @endif
        </td>
       
       
@empty
    <tr>
        <td colspan="9" class="text-center text-muted">No bank statements found.</td>
    </tr>
@endforelse


                </tbody>
            </table>
        </div>
    </div>

   <div class="mt-3 d-flex justify-content-center">
    {{ $statements->links('pagination::bootstrap-5') }}
</div>
</div>

<!-- Add these in your Blade file inside <head> -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>


@endsection
