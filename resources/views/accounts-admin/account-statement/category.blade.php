@extends('layouts.app')

@section('content')
<style>
    .remarks-cell {
        word-wrap: break-word;
        white-space: normal;
        max-width: 150px;
    }
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
    .mismatch-row {
        background-color: #ffe5e5 !important;
        border-left: 5px solid red;
    }
    .filter-card {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 16px 20px;
        margin-bottom: 20px;
    }
</style>

<div class="container mt-4">
    <h3 class="mb-4">Categorized Bank Statement</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- ==================== FILTER FORM ==================== --}}
    {{-- ==================== FILTER FORM ==================== --}}
<div class="filter-card">
    <form method="GET" action="{{ url('/categorized') }}" id="filter-form">

        @if(request('action_type'))
            <input type="hidden" name="action_type" value="{{ request('action_type') }}">
        @endif
        @if(request('old_action_type'))
            <input type="hidden" name="old_action_type" value="{{ request('old_action_type') }}">
        @endif

        <div class="row g-2 align-items-end">

            {{-- Name --}}
            <div class="col-md-3 col-sm-6">
                <label class="form-label fw-semibold mb-1" style="font-size:13px;">Name</label>
                <input type="text"
                       name="name"
                       value="{{ request('name') }}"
                       class="form-control form-control-sm"
                       placeholder="Search by name">
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

        {{-- Buttons Row BELOW filters --}}
        <div class="row mt-2">
            <div class="col-12 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="bi bi-search"></i> Filter
                </button>
                <a href="{{ url('/categorized' . (request('old_action_type') ? '?old_action_type=' . request('old_action_type') : (request('action_type') ? '?action_type=' . request('action_type') : ''))) }}"
                   class="btn btn-outline-danger btn-sm">
                    <i class="bi bi-x-circle"></i> Clear
                </a>
            </div>
        </div>

    </form>
</div>
{{-- ==================== END FILTER FORM ==================== --}}
    

    <div class="card shadow-sm mt-2">
        <div class="card-header bg-primary text-white fw-bold">
            Imported Bank Statements
        </div>

        <div class="card-body p-0">
            <table id="statements-table" class="table table-bordered table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Date</th>
                        <th>UTR No</th>
                        <th>Remarks</th>
                        <th>Name</th>
                        <th>REF NO</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($statements as $statement)
                        @php
                            $isMismatch = in_array($statement->conform_ref_no, $highlight ?? []);
                        @endphp
                        <tr data-id="{{ $statement->id }}"
                            @if($isMismatch) style="background-color: #f8d7da;" @endif>
                            <td>{{ \Carbon\Carbon::parse($statement->date)->format('d-m-Y') }}</td>
                            <td>{{ $statement->utrno }}</td>
                            <td class="remarks-cell">{{ $statement->remarks }}</td>
                            <td>{{ $statement->name }}</td>
                            <td>
                                <span class="fw-bold">{{ $statement->conform_ref_no }}</span>
                                @if(!empty($statement->conform_ref_no) && str_starts_with($statement->conform_ref_no, 'INV'))
                                    @php
                                        $invRecord = \App\Models\Investor::where('ref_no', $statement->conform_ref_no)
                                            ->orWhere('statement_id', $statement->id)
                                            ->first();
                                    @endphp
                                    @if($invRecord && $invRecord->interest_status === 'pending')
                                        <span class="badge bg-warning text-dark d-block mt-1" style="font-size:0.75rem;">
                                            Interest Pending: ₹{{ number_format($invRecord->interest_amount, 2) }}
                                        </span>
                                    @endif
                                @endif
                            </td>
                            <td>
                                <div class="action-view-{{ $statement->id }}">
                                    @if(!empty($statement->conform_ref_no))
                                        <a href="{{ route('bankstatement.view', $statement->conform_ref_no) }}"
                                           class="btn btn-sm btn-primary">
                                            View Details
                                        </a>
                                        <a href="{{ route('bankstatement.uncategorized', $statement->conform_ref_no) }}"
                                           class="btn btn-warning btn-sm">
                                            Uncategorized
                                        </a>
                                    @else
                                        <span class="text-muted">No Ref No</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-3">No bank statements found.</td>
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

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.view-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            let statementId = this.dataset.id;
            let actionType = this.dataset.action;

            if (!actionType) {
                alert("No action assigned for this statement.");
                return;
            }

            let loader = document.createElement('div');
            loader.id = 'page-loader';
            loader.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(255,255,255,0.7);z-index:9999;';
            loader.innerHTML = `
                <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);">
                    <div class="spinner-border text-primary" style="width:3rem;height:3rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="mt-2 fw-bold text-primary">Redirecting...</div>
                </div>`;
            document.body.appendChild(loader);

            let url = '';
            switch (actionType) {
                case 'INVESTMENT':        url = `/bank-statement/set-action/${statementId}?action_type=${encodeURIComponent(actionType)}`; break;
                case 'LOAN':              url = `/bank-statement/showloan/${statementId}?action_type=${encodeURIComponent(actionType)}`; break;
                case 'EXCESS':            url = `/bank-statement/excess/${statementId}?action_type=${encodeURIComponent(actionType)}`; break;
                case 'EXPANSES':          url = `/bank-statement/expanses/${statementId}?action_type=${encodeURIComponent(actionType)}`; break;
                case 'SELF TRANSACTION':  url = `/bank-statement/set-action/${statementId}?action_type=${encodeURIComponent(actionType)}`; break;
                case 'INVESTMENT RETURN': url = `/bank-statement/showreturn/${statementId}?action_type=${encodeURIComponent(actionType)}`; break;
                case 'LOAN RETURN':       url = `/bank-statement/showloanreturn/${statementId}?action_type=${encodeURIComponent(actionType)}`; break;
                case 'EXCESS RETURN':     url = `/bank-statement/showexcessreturn/${statementId}?action_type=${encodeURIComponent(actionType)}`; break;
                default:                  url = `/bank-statement/showaction/${statementId}?action_type=${encodeURIComponent(actionType)}`; break;
            }

            setTimeout(() => { window.location.href = url; }, 200);
        });
    });
});

$(document).on('submit', '.refno-form', function(e) {
    e.preventDefault();
    let form = $(this);
    let id = form.data('id');

    $.ajax({
        url: "/bank-statement/save-refno/" + id,
        method: "POST",
        data: form.serialize(),
        success: function(response) {
            if (response.success) {
                alert(response.message);
                $('tr[data-id="' + response.cr_id + '"]').fadeOut();
                if (response.dr_id) {
                    $('tr[data-id="' + response.dr_id + '"]').fadeOut();
                }
            }
        }
    });
});
</script>

@endsection