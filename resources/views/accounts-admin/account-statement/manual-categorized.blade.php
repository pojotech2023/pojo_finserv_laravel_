@extends('layouts.app')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=Syne:wght@600;700;800&display=swap" rel="stylesheet">

<style>
    :root {
        --bg: #f0f4ff;
        --surface: #ffffff;
        --surface2: #f8faff;
        --border: #e2e8f0;
        --accent: #4361ee;
        --accent-light: #eef1fd;
        --accent2: #7209b7;
        --danger: #e63946;
        --success: #10b981;
        --warning: #f59e0b;
        --text: #1e293b;
        --muted: #64748b;
        --font-display: 'Syne', sans-serif;
        --font-body: 'DM Sans', sans-serif;
    }

    body { background: var(--bg) !important; font-family: var(--font-body); color: var(--text); }
    .page-wrapper { padding: 1.75rem 2rem; max-width: 1600px; margin: 0 auto; }

    .page-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 1.75rem; padding-bottom: 1.25rem; border-bottom: 2px solid var(--border);
    }
    .page-title {
        font-family: var(--font-display); font-size: 1.65rem; font-weight: 800;
        background: linear-gradient(135deg, var(--accent) 0%, var(--accent2) 100%);
        -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        background-clip: text; margin: 0;
    }
    .page-subtitle { color: var(--muted); font-size: 0.82rem; margin-top: 3px; }

    .custom-alert {
        border: none; border-radius: 10px; padding: 0.85rem 1.1rem;
        margin-bottom: 1.25rem; display: flex; align-items: center;
        gap: 0.6rem; font-size: 0.88rem; font-weight: 500;
    }
    .custom-alert.success { background: #ecfdf5; border-left: 4px solid var(--success); color: #065f46; }
    .custom-alert.error   { background: #fff1f2; border-left: 4px solid var(--danger);  color: #9f1239; }

    .btn-ghost {
        background: #fff; border: 1px solid var(--border); color: var(--muted);
        border-radius: 8px; padding: 0.45rem 1rem; font-size: 0.82rem; font-weight: 500;
        transition: all 0.2s; text-decoration: none; display: inline-flex; align-items: center; gap: 0.4rem;
        cursor: pointer; box-shadow: 0 1px 2px rgba(0,0,0,0.04);
    }
    .btn-ghost:hover { border-color: var(--accent); color: var(--accent); background: var(--accent-light); }

    .btn-primary-custom {
        background: var(--accent); color: #fff; border: none; border-radius: 8px;
        padding: 0.45rem 1.1rem; font-size: 0.82rem; font-weight: 600;
        transition: all 0.2s; cursor: pointer; display: inline-flex; align-items: center; gap: 0.4rem;
        box-shadow: 0 2px 8px rgba(67,97,238,0.22); text-decoration: none;
    }
    .btn-primary-custom:hover { background: #3451d1; color: #fff; }

    .filter-bar {
        display: flex; gap: 0.75rem; flex-wrap: wrap; align-items: center;
        background: var(--surface2); padding: 1rem 1.25rem;
        border-radius: 12px; border: 1px solid var(--border); margin-bottom: 1.5rem;
    }
    .filter-bar .form-control, .filter-bar .form-select {
        background: #fff !important; border: 1px solid var(--border) !important;
        border-radius: 8px !important; color: var(--text) !important;
        font-size: 0.84rem; padding: 0.45rem 0.9rem; height: auto;
        box-shadow: 0 1px 2px rgba(0,0,0,0.04);
    }
    .filter-bar .form-control:focus, .filter-bar .form-select:focus {
        border-color: var(--accent) !important;
        box-shadow: 0 0 0 3px rgba(67,97,238,0.1) !important; outline: none;
    }

    .main-card {
        background: var(--surface); border: 1px solid var(--border); border-radius: 16px;
        overflow: hidden; box-shadow: 0 2px 8px rgba(67,97,238,0.06);
    }
    .card-header-custom {
        background: linear-gradient(135deg, #4361ee 0%, #7209b7 100%);
        padding: 1rem 1.5rem; display: flex; align-items: center; justify-content: space-between;
    }
    .card-header-custom h6 { font-family: var(--font-display); font-size: 1rem; font-weight: 700; color: #fff; margin: 0; }

    .custom-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .custom-table thead th {
        background: var(--surface2); color: var(--muted);
        font-size: 0.71rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.07em;
        padding: 0.85rem 1rem; border-bottom: 2px solid var(--border); white-space: nowrap;
    }
    .custom-table tbody tr { transition: background 0.12s; }
    .custom-table tbody tr:hover td { background: #f5f7ff; }
    .custom-table tbody tr:nth-child(even) td { background: #fafbff; }
    .custom-table tbody tr:nth-child(even):hover td { background: #f0f3ff; }
    .custom-table tbody td {
        padding: 0.8rem 1rem; border-bottom: 1px solid #f1f5f9;
        font-size: 0.84rem; color: var(--text); vertical-align: middle;
    }
    .custom-table tbody tr:last-child td { border-bottom: none; }

    /* Category pills */
    .badge-cat {
        border-radius: 5px; padding: 0.22rem 0.6rem; font-size: 0.71rem; font-weight: 700;
        display: inline-block;
    }
    .badge-cat-INVESTMENT { background:#ede9fe; color:#6d28d9; border:1px solid #c4b5fd; }
    .badge-cat-LOAN       { background:#fff1f2; color:var(--danger); border:1px solid #fecdd3; }
    .badge-cat-EXPANSES   { background:#fff7ed; color:#c2410c; border:1px solid #fdba74; }
    .badge-cat-EXCESS     { background:#ecfdf5; color:#059669; border:1px solid #a7f3d0; }
    .badge-cat-OTHER      { background:#f1f5f9; color:#475569; border:1px solid #cbd5e1; }

    .badge-cr { background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; border-radius: 5px; padding: 0.22rem 0.6rem; font-size: 0.71rem; font-weight: 700; }
    .badge-dr { background: #fff1f2; color: var(--danger); border: 1px solid #fecdd3; border-radius: 5px; padding: 0.22rem 0.6rem; font-size: 0.71rem; font-weight: 700; }

    .tbl-btn {
        background: #fff; border: 1px solid var(--border); border-radius: 6px;
        padding: 0.28rem 0.6rem; font-size: 0.74rem; font-weight: 500; color: var(--muted);
        cursor: pointer; transition: all 0.13s; text-decoration: none;
        display: inline-flex; align-items: center; gap: 0.25rem;
        box-shadow: 0 1px 2px rgba(0,0,0,0.04);
    }
    .tbl-btn.danger-btn { background: #fff1f2; border-color: #fecdd3; color: var(--danger); }
    .tbl-btn.danger-btn:hover { background: #ffe4e6; border-color: var(--danger); }

    .note-cell { max-width: 180px; white-space: normal; word-break: break-word; font-size: 0.78rem; color: var(--muted); }

    .pagination .page-link {
        background: #fff !important; border: 1px solid var(--border) !important;
        color: var(--muted) !important; border-radius: 7px !important;
        margin: 0 2px; font-size: 0.82rem; padding: 0.38rem 0.72rem; transition: all 0.13s;
    }
    .pagination .page-item.active .page-link { background: var(--accent) !important; border-color: var(--accent) !important; color: #fff !important; }
    .pagination .page-link:hover { border-color: var(--accent) !important; color: var(--accent) !important; background: var(--accent-light) !important; }

    ::-webkit-scrollbar { width: 5px; height: 5px; }
    ::-webkit-scrollbar-track { background: #f1f5f9; }
    ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
    ::-webkit-scrollbar-thumb:hover { background: var(--accent); }
</style>

<div class="page-wrapper">

    {{-- Page Header --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">Manual Categorized</h1>
            <p class="page-subtitle">Transactions manually classified by staff</p>
        </div>
        <a href="{{ route('bank.statement') }}" class="btn-ghost">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
            </svg>
            Back to Statements
        </a>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
    <div class="custom-alert success">
        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" viewBox="0 0 16 16">
            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Filter Bar --}}
    <form method="GET" action="{{ route('bank.statement.manualCategorizedList') }}" class="filter-bar">
        <select name="manual_category" class="form-select" style="max-width:160px;">
            <option value="">All Categories</option>
            @foreach(['INVESTMENT','LOAN','EXPANSES','EXCESS','OTHER'] as $cat)
                <option value="{{ $cat }}" {{ request('manual_category') == $cat ? 'selected' : '' }}>
                    {{ $cat }}
                </option>
            @endforeach
        </select>
        <input type="text"  name="name"      class="form-control" placeholder="Filter by name…"
               value="{{ request('name') }}" style="max-width:200px;">
        <input type="date"  name="from_date" class="form-control" value="{{ request('from_date') }}"
               title="From Date" style="max-width:160px;">
        <input type="date"  name="to_date"   class="form-control" value="{{ request('to_date') }}"
               title="To Date" style="max-width:160px;">
        <input type="text"  name="utrno"     class="form-control" placeholder="UTR No…"
               value="{{ request('utrno') }}" style="max-width:200px;">
        <button type="submit" class="btn-primary-custom">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" viewBox="0 0 16 16">
                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.099zm-5.242 1.156a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11z"/>
            </svg>
            Filter
        </button>
        <a href="{{ route('bank.statement.manualCategorizedList') }}" class="btn-ghost">Clear</a>
    </form>

    {{-- Main Card --}}
    <div class="main-card">
        <div class="card-header-custom">
            <h6>
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor"
                     style="margin-right:6px;vertical-align:-2px;" viewBox="0 0 16 16">
                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                </svg>
                Manually Categorized Transactions
            </h6>
            <span style="color:rgba(255,255,255,0.75);font-size:0.8rem;">
                {{ $statements->total() }} record(s)
            </span>
        </div>

        <div style="overflow-x:auto;">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>UTR No</th>
                        <th>Remarks</th>
                        <th>Name</th>
                        <th style="text-align:right;">Amount</th>
                        <th style="text-align:center;">CR/DR</th>
                        <th style="text-align:center;">Category</th>
                        <th>Note</th>
                        <th>Categorized At</th>
                        <th style="text-align:center;">Restore</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($statements as $stmt)
                    <tr>
                        <td style="white-space:nowrap;font-size:0.8rem;">
                            {{ \Carbon\Carbon::parse($stmt->date)->format('d-m-Y') }}
                        </td>
                        <td style="font-family:monospace;font-size:0.78rem;color:var(--muted);">
                            {{ $stmt->utrno ?? '—' }}
                        </td>
                        <td style="max-width:160px;white-space:normal;word-break:break-word;font-size:0.8rem;">
                            {{ $stmt->remarks ?? '—' }}
                        </td>
                        <td style="font-weight:500;">{{ $stmt->name }}</td>
                        <td style="text-align:right;font-variant-numeric:tabular-nums;font-weight:600;">
                            {{ number_format($stmt->amount, 2) }}
                        </td>
                        <td style="text-align:center;">
                            @if($stmt->cr_dr == 'CR')
                                <span class="badge-cr">CR</span>
                            @else
                                <span class="badge-dr">DR</span>
                            @endif
                        </td>
                        <td style="text-align:center;">
                            <span class="badge-cat badge-cat-{{ $stmt->manual_category }}">
                                {{ $stmt->manual_category }}
                            </span>
                        </td>
                        <td class="note-cell">{{ $stmt->manual_note ?: '—' }}</td>
                        <td style="white-space:nowrap;font-size:0.78rem;color:var(--muted);">
                            {{ $stmt->manual_categorized_at
                                ? \Carbon\Carbon::parse($stmt->manual_categorized_at)->format('d-m-Y H:i')
                                : '—' }}
                        </td>
                        <td style="text-align:center;">
                            {{-- Restore = remove manual category, show back in main list --}}
                            <form method="POST"
                                  action="{{ route('bank.statement.removeManualCategory', $stmt->id) }}"
                                  onsubmit="return confirm('Restore this record to the main Bank Statement list?');">
                                @csrf
                                <button type="submit" class="tbl-btn danger-btn" title="Restore to main list">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M11.534 7h3.932a.25.25 0 0 1 .192.41l-1.966 2.36a.25.25 0 0 1-.384 0l-1.966-2.36a.25.25 0 0 1 .192-.41zm-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9z"/>
                                        <path fill-rule="evenodd" d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A6.002 6.002 0 0 1 13.917 7H12.9A5.002 5.002 0 0 0 8 3zM3.1 9a5.002 5.002 0 0 0 8.757 2.182.5.5 0 1 1 .771.636A6.002 6.002 0 0 1 2.083 9H3.1z"/>
                                    </svg>
                                    Restore
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" style="text-align:center;padding:3rem 1rem;color:var(--muted);font-size:0.88rem;">
                            No manually categorized records found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-3 d-flex justify-content-end">
        {{ $statements->links('pagination::bootstrap-5') }}
    </div>

</div>

@endsection