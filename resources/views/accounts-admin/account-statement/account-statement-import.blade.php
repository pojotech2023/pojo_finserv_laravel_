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

    html, body { 
        background: var(--bg) !important; 
        font-family: var(--font-body); 
        color: var(--text); 
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    .page-wrapper { 
        padding: 0.15rem 0 1rem 0 !important; 
        max-width: 100% !important; 
        width: 100% !important; 
        margin: 0 !important; 
    }
    .glass-card {
        width: 100% !important;
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
    }
    .glass-card-body {
        width: 100% !important;
        padding: 0 !important;
    }
    .main-card {
        width: 100% !important;
    }
    .filter-bar {
        width: 100% !important;
    }

    /* ── Page Header ── */
    .page-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 0.75rem; padding-bottom: 0.5rem; border-bottom: 1.5px solid var(--border);
    }
    .page-title {
        font-family: var(--font-display); font-size: 1.35rem; font-weight: 800;
        background: linear-gradient(135deg, var(--accent) 0%, var(--accent2) 100%);
        -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        background-clip: text; margin: 0;
    }
    .page-subtitle { color: var(--muted); font-size: 0.76rem; margin-top: 2px; margin-bottom: 0; }

    /* ── Alert ── */
    .custom-alert {
        border: none; border-radius: 8px; padding: 0.5rem 0.85rem;
        margin-bottom: 0.75rem; display: flex; align-items: center;
        gap: 0.5rem; font-size: 0.82rem; font-weight: 500;
    }
    .custom-alert.success { background: #ecfdf5; border-left: 4px solid var(--success); color: #065f46; }
    .custom-alert.error   { background: #fff1f2; border-left: 4px solid var(--danger);  color: #9f1239; }

    /* ── Buttons ── */
    .btn-primary-custom {
        background: var(--accent); color: #fff; border: none; border-radius: 7px;
        padding: 0.35rem 0.85rem; font-size: 0.78rem; font-weight: 600;
        transition: all 0.2s; cursor: pointer; display: inline-flex; align-items: center; gap: 0.35rem;
        box-shadow: 0 2px 6px rgba(67,97,238,0.2);
    }
    .btn-primary-custom:hover { background: #3451d1; color: #fff; }

    .btn-ghost {
        background: #fff; border: 1px solid var(--border); color: var(--muted);
        border-radius: 7px; padding: 0.35rem 0.75rem; font-size: 0.78rem; font-weight: 500;
        transition: all 0.2s; text-decoration: none; display: inline-flex; align-items: center; gap: 0.35rem;
        cursor: pointer; box-shadow: 0 1px 2px rgba(0,0,0,0.04);
    }
    .btn-ghost:hover { border-color: var(--accent); color: var(--accent); background: var(--accent-light); }

    .btn-danger-sm {
        background: #fff1f2; color: var(--danger); border: 1px solid #fecdd3;
        border-radius: 6px; padding: 0.25rem 0.55rem; font-size: 0.72rem; font-weight: 600;
        cursor: pointer; transition: all 0.15s; display: inline-flex; align-items: center; gap: 0.3rem;
    }
    .btn-danger-sm:hover { background: #ffe4e6; border-color: var(--danger); }

    /* ── Filter Bar ── */
    .filter-bar {
        display: flex; gap: 0.45rem; flex-wrap: wrap; align-items: center;
        background: var(--surface2); padding: 0.5rem 0.75rem;
        border-radius: 10px; border: 1px solid var(--border); margin-bottom: 0.75rem;
    }
    .filter-bar .form-control {
        background: #fff !important; border: 1px solid var(--border) !important;
        border-radius: 7px !important; color: var(--text) !important;
        font-size: 0.78rem; padding: 0.28rem 0.55rem; height: 30px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.04);
    }
    .filter-bar .form-control::placeholder { color: #94a3b8; }
    .filter-bar .form-control:focus {
        border-color: var(--accent) !important;
        box-shadow: 0 0 0 2px rgba(67,97,238,0.12) !important; outline: none;
    }

    /* ── Card ── */
    .main-card {
        background: var(--surface); border: 1px solid var(--border); border-radius: 12px;
        overflow: hidden; box-shadow: 0 2px 8px rgba(67,97,238,0.06);
    }
    .card-header-custom {
        background: linear-gradient(135deg, #4361ee 0%, #7209b7 100%);
        padding: 0.55rem 0.9rem; display: flex; align-items: center; justify-content: space-between;
    }
    .card-header-custom h6 { font-family: var(--font-display); font-size: 0.9rem; font-weight: 700; color: #fff; margin: 0; }

    /* ── Table ── */
    .custom-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .custom-table thead th {
        background: var(--surface2); color: var(--muted);
        font-size: 0.69rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;
        padding: 0.4rem 0.45rem; border-bottom: 2px solid var(--border); white-space: nowrap;
    }
    .custom-table tbody tr { transition: background 0.12s; }
    .custom-table tbody tr:hover td { background: #f5f7ff; }
    .custom-table tbody tr:nth-child(even) td { background: #fafbff; }
    .custom-table tbody tr:nth-child(even):hover td { background: #f0f3ff; }
    .custom-table tbody td {
        padding: 0.35rem 0.45rem; border-bottom: 1px solid #f1f5f9;
        font-size: 0.78rem; color: var(--text); vertical-align: middle;
    }
    .custom-table tbody tr:last-child td { border-bottom: none; }

    /* ── Badges ── */
    .badge-cr { background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; border-radius: 4px; padding: 0.15rem 0.42rem; font-size: 0.68rem; font-weight: 700; }
    .badge-dr { background: #fff1f2; color: var(--danger); border: 1px solid #fecdd3; border-radius: 4px; padding: 0.15rem 0.42rem; font-size: 0.68rem; font-weight: 700; }
    .badge-type { background: var(--accent-light); color: var(--accent); border: 1px solid #c7d2fe; border-radius: 4px; padding: 0.15rem 0.42rem; font-size: 0.68rem; font-weight: 600; }

    /* ── Inline table buttons ── */
    .tbl-btn {
        background: #fff; border: 1px solid var(--border); border-radius: 5px;
        padding: 0.18rem 0.42rem; font-size: 0.72rem; font-weight: 600; color: var(--muted);
        cursor: pointer; transition: all 0.13s; text-decoration: none;
        display: inline-flex; align-items: center; justify-content: center; gap: 0.2rem;
        box-shadow: 0 1px 2px rgba(0,0,0,0.04);
        height: 26px; line-height: 1.2;
    }
    .tbl-btn:hover { border-color: var(--accent); color: var(--accent); background: var(--accent-light); }
    .tbl-btn.warn:hover { border-color: var(--warning); color: var(--warning); background: #fffbeb; }
    .tbl-btn.success-btn { background: #ecfdf5; border-color: #a7f3d0; color: #059669; }
    .tbl-btn.success-btn:hover { background: #d1fae5; }
    .tbl-btn.danger-btn { background: #fff1f2; border-color: #fecdd3; color: var(--danger); }
    .tbl-btn.danger-btn:hover { background: #ffe4e6; border-color: var(--danger); }
    .tbl-btn.primary-btn { background: var(--accent-light); border-color: #c7d2fe; color: var(--accent); }
    .tbl-btn.primary-btn:hover { background: #dde4fd; }

    /* ── Inline forms ── */
    .refno-input, .action-select-input {
        background: #fff !important; border: 1px solid var(--border) !important;
        border-radius: 6px !important; color: var(--text) !important;
        font-size: 0.75rem; padding: 0.2rem 0.35rem; height: 26px; min-height: 26px;
    }
    .refno-input:focus, .action-select-input:focus {
        border-color: var(--accent) !important;
        box-shadow: 0 0 0 2px rgba(67,97,238,0.1) !important; outline: none;
    }

    /* ── Remarks cell ── */
    .remarks-cell {
        font-size: 0.74rem;
        line-height: 1.35;
        color: #334155;
        word-break: break-word;
        white-space: normal;
        min-width: 260px;
    }

    /* ── Autocomplete ── */
    .autocomplete-wrapper { position: relative; }
    .suggestion-box {
        position: absolute; top: 100%; left: 0; right: 0; z-index: 2000;
        display: none; max-height: 180px; overflow-y: auto;
        background: #fff; border: 1px solid var(--border); border-radius: 8px;
        box-shadow: 0 4px 16px rgba(67,97,238,0.12);
    }
    .suggestion-item {
        cursor: pointer; padding: 0.5rem 0.9rem; font-size: 0.83rem;
        border-bottom: 1px solid #f1f5f9; color: var(--text); transition: background 0.12s;
    }
    .suggestion-item:last-child { border-bottom: none; }
    .suggestion-item:hover, .suggestion-item.active { background: var(--accent-light); color: var(--accent); }

    /* ── Loader ── */
    #page-loader {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(255,255,255,0.82); backdrop-filter: blur(4px);
        z-index: 9999; display: flex; align-items: center; justify-content: center;
    }
    .loader-inner { text-align: center; }
    .loader-inner .spinner-border { width: 2.75rem; height: 2.75rem; color: var(--accent); }
    .loader-inner p { margin-top: 0.75rem; font-weight: 600; color: var(--accent); font-size: 0.9rem; }

    /* ── Pagination ── */
    .pagination .page-link {
        background: #fff !important; border: 1px solid var(--border) !important;
        color: var(--muted) !important; border-radius: 7px !important;
        margin: 0 2px; font-size: 0.82rem; padding: 0.38rem 0.72rem; transition: all 0.13s;
    }
    .pagination .page-item.active .page-link { background: var(--accent) !important; border-color: var(--accent) !important; color: #fff !important; }
    .pagination .page-link:hover { border-color: var(--accent) !important; color: var(--accent) !important; background: var(--accent-light) !important; }

    /* ── Soft Delete confirm overlay ── */
    .delete-confirm-overlay {
        position: fixed; inset: 0; background: rgba(15,23,42,0.35); backdrop-filter: blur(3px);
        z-index: 10000; display: flex; align-items: center; justify-content: center;
        opacity: 0; pointer-events: none; transition: opacity 0.2s;
    }
    .delete-confirm-overlay.show { opacity: 1; pointer-events: all; }
    .delete-confirm-box {
        background: #fff; border-radius: 16px; padding: 2rem 2.25rem;
        max-width: 400px; width: 90%; box-shadow: 0 20px 60px rgba(67,97,238,0.18);
        transform: translateY(16px); transition: transform 0.2s; border-top: 4px solid var(--danger);
    }
    .delete-confirm-overlay.show .delete-confirm-box { transform: translateY(0); }
    .delete-confirm-box h5 { font-family: var(--font-display); font-weight: 700; font-size: 1.05rem; color: var(--danger); margin-bottom: 0.6rem; }
    .delete-confirm-box p { color: var(--muted); font-size: 0.87rem; margin-bottom: 1.4rem; }
    .delete-confirm-actions { display: flex; gap: 0.75rem; justify-content: flex-end; }

    /* ── Danger Panel ── */
    .danger-panel {
        border: 1px solid #fecdd3; border-radius: 10px; overflow: hidden;
        margin-bottom: 0.75rem; box-shadow: 0 2px 6px rgba(230,57,70,0.06); background: #fff;
    }
    .danger-panel-header {
        background: linear-gradient(135deg, #fff1f2 0%, #ffe4e6 100%);
        border-bottom: 1px solid #fecdd3; padding: 0.45rem 0.85rem;
        display: flex; align-items: center; justify-content: space-between;
        cursor: pointer; color: var(--danger); font-size: 0.78rem; font-weight: 700;
        font-family: var(--font-display); letter-spacing: 0.01em;
        transition: background 0.2s; user-select: none;
    }
    .danger-panel-header:hover { background: #ffe4e6; }
    .danger-panel-body { padding: 0.85rem 1rem; background: #fff; }

    .btn-danger-custom {
        background: linear-gradient(135deg, #e63946 0%, #c1121f 100%);
        color: #fff; border: none; border-radius: 8px; padding: 0.5rem 1.2rem;
        font-size: 0.82rem; font-weight: 700; cursor: pointer;
        display: inline-flex; align-items: center; gap: 0.4rem;
        box-shadow: 0 2px 8px rgba(230,57,70,0.25); transition: all 0.2s; font-family: var(--font-body);
    }
    .btn-danger-custom:hover {
        background: linear-gradient(135deg, #c1121f 0%, #a50010 100%);
        box-shadow: 0 4px 14px rgba(230,57,70,0.35); transform: translateY(-1px);
    }

    .info-note {
        display: flex; align-items: center; gap: 0.4rem; color: #9f1239;
        background: #fff1f2; border: 1px solid #fecdd3; border-radius: 8px;
        padding: 0.55rem 0.9rem; font-size: 0.78rem; font-weight: 500;
        margin-top: 1rem; margin-bottom: 0;
    }

    .toggle-icon { transition: transform 0.3s ease; flex-shrink: 0; }
    .danger-panel-header[aria-expanded="true"] .toggle-icon { transform: rotate(180deg); }

    ::-webkit-scrollbar { width: 5px; height: 5px; }
    ::-webkit-scrollbar-track { background: #f1f5f9; }
    ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
    ::-webkit-scrollbar-thumb:hover { background: var(--accent); }
</style>

<div class="page-wrapper">

    {{-- Page Header --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">Bank Statements</h1>
            <p class="page-subtitle">Import, review and categorise transaction records</p>
        </div>
        <a href="{{ route('account.statement.trashed') }}" class="btn-ghost">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" viewBox="0 0 16 16">
                <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5z"/>
            </svg>
            Deleted Records
        </a>
    </div>

    <div class="glass-card">
        <div class="glass-card-body">

            {{-- Import Bank Statement Card --}}
            <div class="card mb-2 border-0 shadow-sm rounded-3" style="background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%); border: 1px solid #c7d2fe !important;">
                <div class="card-body py-2 px-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="fw-bold text-dark" style="font-size:0.8rem;"><i class="fas fa-file-excel me-1 text-success"></i> Import Bank Statement</span>
                        <a href="{{ route('bankstatement.downloadTemplate') }}" class="btn btn-sm btn-success fw-bold py-0 px-2 shadow-sm rounded-pill" style="font-size:0.72rem;line-height:1.7;">
                            <i class="fas fa-download me-1"></i> Sample Template
                        </a>
                    </div>
                    <form action="{{ route('bankstatement.import') }}" method="POST" enctype="multipart/form-data" class="row g-2 align-items-center">
                        @csrf
                        <div class="col-md-3">
                            <label class="form-label fw-bold text-dark mb-0" style="font-size:0.72rem;">Select Bank</label>
                            <select name="bank_name" class="form-select form-select-sm fw-semibold" style="height:28px;font-size:0.75rem;padding:0.15rem 0.4rem;">
                                <option value="">Auto Detect Bank</option>
                                <option value="AXIS">AXIS BANK</option>
                                <option value="HDFC">HDFC BANK</option>
                                <option value="YES BANK">YES BANK</option>
                                <option value="ICICI">ICICI BANK</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-dark mb-0" style="font-size:0.72rem;">Upload File (Excel / CSV)</label>
                            <input type="file" name="file" class="form-control form-control-sm" accept=".xlsx,.xls,.csv,.txt" required style="height:28px;font-size:0.75rem;padding:0.15rem 0.4rem;">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold text-dark mb-0" style="font-size:0.72rem;" title="Optional: override Settings start date filter">From Date (Opt)</label>
                            <input type="date" name="from_date" class="form-control form-control-sm" style="height:28px;font-size:0.75rem;padding:0.15rem 0.4rem;">
                        </div>
                        <div class="col-md-3 align-self-end">
                            <button type="submit" class="btn btn-primary btn-sm w-100 fw-bold shadow-sm" style="height:28px;font-size:0.75rem;padding:0.15rem 0.5rem;display:flex;align-items:center;justify-content:center;gap:0.3rem;">
                                <i class="fas fa-file-import"></i> Import Statement
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Filter Bar --}}
            <form method="GET" action="{{ route('bank.statement') }}" class="filter-bar">
                <input type="text"  name="name"      class="form-control" placeholder="Filter by name…"  value="{{ request('name') }}"      style="max-width:140px;">
                <select name="bank_name" class="form-control" style="max-width:115px;">
                    <option value="">All Banks</option>
                    <option value="AXIS" {{ request('bank_name') == 'AXIS' ? 'selected' : '' }}>AXIS BANK</option>
                    <option value="HDFC" {{ request('bank_name') == 'HDFC' ? 'selected' : '' }}>HDFC BANK</option>
                    <option value="YES BANK" {{ request('bank_name') == 'YES BANK' ? 'selected' : '' }}>YES BANK</option>
                    <option value="ICICI" {{ request('bank_name') == 'ICICI' ? 'selected' : '' }}>ICICI BANK</option>
                </select>
                <input type="date"  name="from_date" class="form-control" value="{{ request('from_date') }}" title="From Date"               style="max-width:130px;">
                <input type="date"  name="to_date"   class="form-control" value="{{ request('to_date') }}"   title="To Date"                 style="max-width:130px;">
                <input type="text"  name="ref_no"    class="form-control" placeholder="Ref No…"          value="{{ request('ref_no') }}"     style="max-width:120px;">
                <input type="text"  name="utrno"     class="form-control" placeholder="UTR No"           value="{{ request('utrno') }}"      style="max-width:120px;">
                <button type="submit" class="btn-primary-custom">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.099zm-5.242 1.156a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11z"/>
                    </svg>
                    Filter
                </button>
                <a href="{{ route('bank.statement') }}" class="btn-ghost">Clear</a>
            </form>

            {{-- Delete by Date Range --}}
            <div class="danger-panel">
                <div class="danger-panel-header" data-bs-toggle="collapse" data-bs-target="#deleteDateRangePanel" aria-expanded="false">
                    <span style="display:flex;align-items:center;gap:0.5rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5z"/>
                        </svg>
                        Delete Records by Date Range
                        <span style="font-size:0.7rem;opacity:0.6;font-weight:400;">(Soft Delete)</span>
                    </span>
                    <svg class="toggle-icon" xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/>
                    </svg>
                </div>
                <div class="collapse" id="deleteDateRangePanel">
                    <div class="danger-panel-body">
                        <form method="POST" action="{{ route('account.statement.deleteByDate') }}" id="deleteDateRangeForm">
                            @csrf
                            <div style="display:flex;gap:1rem;flex-wrap:wrap;align-items:flex-end;">
                                <div>
                                    <label style="display:block;font-size:0.78rem;color:var(--muted);margin-bottom:0.4rem;font-weight:600;text-transform:uppercase;letter-spacing:0.06em;">From Date</label>
                                    <input type="date" name="delete_from_date" id="delete_from_date"
                                        class="form-control @error('delete_from_date') is-invalid @enderror"
                                        value="{{ old('delete_from_date') }}" required
                                        style="background:#fff;border:1px solid var(--border);color:var(--text);border-radius:8px;padding:0.45rem 0.9rem;font-size:0.85rem;">
                                    @error('delete_from_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div>
                                    <label style="display:block;font-size:0.78rem;color:var(--muted);margin-bottom:0.4rem;font-weight:600;text-transform:uppercase;letter-spacing:0.06em;">To Date</label>
                                    <input type="date" name="delete_to_date" id="delete_to_date"
                                        class="form-control @error('delete_to_date') is-invalid @enderror"
                                        value="{{ old('delete_to_date') }}" required
                                        style="background:#fff;border:1px solid var(--border);color:var(--text);border-radius:8px;padding:0.45rem 0.9rem;font-size:0.85rem;">
                                    @error('delete_to_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <button type="button" class="btn-danger-custom" onclick="confirmDelete()">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5z"/>
                                    </svg>
                                    Delete Records
                                </button>
                            </div>
                            <p class="info-note">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                    <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                                </svg>
                                Records will be soft deleted and can be restored from "Deleted Records".
                            </p>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Alert --}}
            @if(session('success'))
            <div class="custom-alert success mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                </svg>
                {{ session('success') }}
            </div>
            @endif

            @if(session('warning'))
            <div class="alert alert-warning border-0 rounded-3 shadow-sm d-flex align-items-center mb-3">
                <i class="fas fa-exclamation-triangle me-2 fs-5 text-warning"></i>
                <div>{{ session('warning') }}</div>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger border-0 rounded-3 shadow-sm d-flex align-items-center mb-3">
                <i class="fas fa-times-circle me-2 fs-5 text-danger"></i>
                <div>{{ session('error') }}</div>
            </div>
            @endif

            {{-- Main Card --}}
            <div class="main-card">
                <div class="card-header-custom">
                    <h6>Imported Bank Statements</h6>
                </div>

                <div style="overflow-x:auto;width:100%;">
                    <table id="statements-table" class="custom-table">
                        <thead>
                            <tr>
                                <th style="width:78px;padding-left:0.6rem;">Date</th>
                                <th style="width:50px;text-align:center;">Bank</th>
                                <th style="width:90px;">UTR No</th>
                                <th style="min-width:260px;width:28%;">Remarks</th>
                                <th style="min-width:110px;max-width:140px;">Name</th>
                                <th style="width:85px;text-align:right;">Amount</th>
                                <th style="width:42px;text-align:center;">CR/DR</th>
                                <th style="width:120px;">REF NO</th>
                                <th style="width:140px;">Action</th>
                                <th style="width:38px;text-align:center;padding-right:0.6rem;">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($statements as $statement)
                            <tr data-id="{{ $statement->id }}">

                                {{-- Date --}}
                                <td style="white-space:nowrap;font-size:0.75rem;padding-left:0.6rem;">
                                    {{ \Carbon\Carbon::parse($statement->date)->format('d-m-Y') }}
                                </td>

                                {{-- Bank Name --}}
                                <td style="text-align:center;">
                                    <span class="badge bg-secondary font-monospace" style="font-size:0.68rem;padding:0.18rem 0.4rem;">
                                        {{ $statement->bank_name ?? 'N/A' }}
                                    </span>
                                </td>

                                {{-- UTR --}}
                                <td style="font-family:monospace;font-size:0.72rem;color:var(--muted);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:100px;" title="{{ $statement->utrno }}">
                                    {{ $statement->utrno }}
                                </td>

                                {{-- Remarks --}}
                                <td class="remarks-cell" title="{{ $statement->remarks }}">
                                    {{ $statement->remarks }}
                                </td>

                                {{-- Name --}}
                                <td style="font-weight:600;font-size:0.78rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:150px;" title="{{ $statement->name }}">
                                    {{ $statement->name }}
                                </td>

                                {{-- Amount --}}
                                <td style="text-align:right;font-variant-numeric:tabular-nums;font-weight:700;font-size:0.8rem;white-space:nowrap;">
                                    {{ number_format($statement->amount, 2) }}
                                </td>

                                {{-- CR/DR --}}
                                <td style="text-align:center;white-space:nowrap;">
                                    @if(in_array($statement->cr_dr, ['CR', 'Credit']))
                                        <span class="badge-cr">CR</span>
                                    @else
                                        <span class="badge-dr">DR</span>
                                    @endif
                                </td>

                                 {{-- REF NO column --}}
                                <td>
                                    <div style="display:flex;gap:3px;align-items:center;flex-wrap:nowrap;">
                                        {{-- Save form --}}
                                        <form class="refno-form" data-id="{{ $statement->id }}" method="POST" onsubmit="return false;" style="display:inline-flex;gap:3px;align-items:center;margin:0;">
                                            @csrf
                                            <input type="text" name="ref_no"
                                                   value="{{ $statement->conform_ref_no ?? $statement->ref_no }}"
                                                   class="refno-input form-control"
                                                   placeholder="Ref No"
                                                   style="width:72px;font-size:0.74rem;padding:0.18rem 0.35rem;height:26px;min-height:26px;">
                                            <button type="submit" class="tbl-btn success-btn" style="padding:0.18rem 0.45rem;font-size:0.72rem;height:26px;white-space:nowrap;">Save</button>
                                        </form>

                                        {{-- ✅ Remove button --}}
                                        @if($statement->conform_ref_no || $statement->ref_no)
                                        <button type="button"
                                                class="tbl-btn danger-btn remove-refno-btn"
                                                data-id="{{ $statement->id }}"
                                                title="Remove Ref No"
                                                style="padding:0.18rem 0.35rem;height:26px;line-height:1;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10"
                                                 fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854z"/>
                                            </svg>
                                        </button>
                                        @endif
                                    </div>
                                </td>

                                {{-- Action column --}}
                                <td>
                                    @if($statement->action_type && ($statement->conform_ref_no || $statement->ref_no))
                                        <div class="action-view-{{ $statement->id }}" style="display:flex;align-items:center;gap:3px;flex-wrap:nowrap;">
                                            <span class="badge-type" style="font-size:0.68rem;padding:0.15rem 0.4rem;white-space:nowrap;">{{ $statement->action_type }}</span>
                                            <button class="tbl-btn primary-btn view-btn"
                                                    data-id="{{ $statement->id }}"
                                                    data-action="{{ $statement->action_type }}"
                                                    style="padding:0.15rem 0.4rem;font-size:0.72rem;height:24px;">View</button>
                                            <button type="button" class="tbl-btn warn edit-btn" data-id="{{ $statement->id }}" style="padding:0.15rem 0.4rem;font-size:0.72rem;height:24px;">Edit</button>
                                        </div>
                                        <div class="action-edit-{{ $statement->id }}" style="display:none;">
                                            <div style="display:flex;gap:3px;align-items:center;flex-wrap:nowrap;">
                                                <select class="form-select action-select action-select-input" data-id="{{ $statement->id }}" style="width:90px;font-size:0.72rem;padding:0.15rem 0.3rem;height:26px;">
                                                    <option value="">Select…</option>
                                                    @if(in_array($statement->cr_dr, ['CR', 'Credit']))
                                                        <option value="INVESTMENT">INVESTMENT</option>
                                                        <option value="EXPANSES">EXPANSES</option>
                                                        <option value="EXCESS">EXCESS</option>
                                                        <option value="OD INTEREST">OD INTEREST</option>
                                                        <option value="SELF TRANSACTION">SELF TRANSACTION</option>
                                                    @else
                                                        <option value="LOAN">LOAN</option>
                                                        <option value="EXPANSES">EXPANSES</option>
                                                        <option value="EXCESS">EXCESS</option>
                                                        <option value="SELF TRANSACTION">SELF TRANSACTION</option>
                                                    @endif
                                                </select>
                                                <button type="button" class="tbl-btn danger-btn cancel-btn" data-id="{{ $statement->id }}" style="padding:0.15rem 0.4rem;font-size:0.72rem;height:26px;">Cancel</button>
                                            </div>
                                        </div>
                                    @else
                                        <div style="display:flex;align-items:center;gap:3px;flex-wrap:nowrap;">
                                            <select class="form-select action-select action-select-input" data-id="{{ $statement->id }}" style="width:90px;min-width:90px;font-size:0.72rem;padding:0.15rem 0.3rem;height:26px;">
                                                <option value="">Select…</option>
                                                @if(in_array($statement->cr_dr, ['CR', 'Credit']))
                                                    <option value="INVESTMENT">INVESTMENT</option>
                                                    <option value="EXPANSES">EXPANSES</option>
                                                    <option value="EXCESS">EXCESS</option>
                                                    <option value="OD INTEREST">OD INTEREST</option>
                                                    <option value="SELF TRANSACTION">SELF TRANSACTION</option>
                                                @else
                                                    <option value="LOAN">LOAN</option>
                                                    <option value="EXPANSES">EXPANSES</option>
                                                    <option value="EXCESS">EXCESS</option>
                                                    <option value="SELF TRANSACTION">SELF TRANSACTION</option>
                                                @endif
                                            </select>
                                            @if(!$statement->action_type && !$statement->is_manual_categorized)
                                            <button type="button"
                                                    class="tbl-btn primary-btn manual-cat-btn"
                                                    data-id="{{ $statement->id }}"
                                                    data-name="{{ $statement->name }}"
                                                    data-amount="{{ number_format($statement->amount, 2) }}"
                                                    data-crdr="{{ $statement->cr_dr }}"
                                                    style="padding:0.18rem 0.42rem;font-size:0.72rem;height:26px;white-space:nowrap;"
                                                    title="Manually categorize this transaction">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="currentColor" viewBox="0 0 16 16">
                                                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                                </svg>
                                                Manual
                                            </button>
                                            @endif
                                        </div>
                                    @endif
                                </td>

                                {{-- Soft Delete Column --}}
                                <td style="text-align:center;padding-right:0.6rem;white-space:nowrap;">
                                    <button type="button"
                                        class="tbl-btn danger-btn soft-delete-btn"
                                        data-id="{{ $statement->id }}"
                                        data-url="{{ route('account.statement.softDeleteSingle', $statement->id) }}"
                                        title="Soft Delete"
                                        style="padding:0.18rem 0.42rem;height:26px;display:inline-flex;align-items:center;justify-content:center;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5z"/>
                                        </svg>
                                    </button>
                                </td>

                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" style="text-align:center;padding:2.5rem 1rem;color:var(--muted);font-size:0.85rem;">
                                    No bank statements found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                </div>
            </div>

            {{-- Pagination --}}
            <div class="mt-3 d-flex justify-content-end">
                {{ $statements->links('pagination::bootstrap-5') }}
            </div>

        </div>
    </div>
</div>

{{-- Soft Delete Confirmation Overlay --}}
<div class="delete-confirm-overlay" id="softDeleteOverlay">
    <div class="delete-confirm-box">
        <h5>
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                 style="margin-right:6px;vertical-align:-2px;" viewBox="0 0 16 16">
                <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
            </svg>
            Confirm Soft Delete
        </h5>
        <p>This record will be soft deleted and can be restored later from <strong>Deleted Records</strong>. Continue?</p>
        <div class="delete-confirm-actions">
            <button type="button" class="btn-ghost" id="cancelSoftDelete">Cancel</button>
            <form id="softDeleteForm" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger-sm" style="padding:0.45rem 1rem;font-size:0.82rem;border-radius:8px;">Yes, Delete</button>
            </form>
        </div>
    </div>
</div>

{{-- Confirm Date-Range Delete Modal --}}
<div class="modal fade" id="confirmDeleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:440px;">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom:1px solid var(--border);">
                <h5 class="modal-title" style="color:var(--danger);display:flex;align-items:center;gap:0.5rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                    </svg>
                    Confirm Soft Delete
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter:invert(1);opacity:0.5;"></button>
            </div>
            <div class="modal-body">
                <p style="color:var(--text);margin-bottom:0.5rem;">
                    Are you sure you want to soft delete all records from
                    <strong id="confirm_from" style="color:var(--accent);"></strong> to
                    <strong id="confirm_to"   style="color:var(--accent);"></strong>?
                </p>
                <p style="color:var(--muted);font-size:0.82rem;margin:0;">These records can be restored later from the Deleted Records section.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-ghost" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn-danger-custom" onclick="submitDeleteForm()">Yes, Delete</button>
            </div>
        </div>
    </div>
</div>

{{-- ── Scripts ─────────────────────────────────────────────────────── --}}
<script>
    // These two helpers are global (used by onclick= in the modal)
    function confirmDelete() {
        var fromDate = document.getElementById('delete_from_date').value;
        var toDate   = document.getElementById('delete_to_date').value;
        if (!fromDate || !toDate) { alert('Please select both From Date and To Date.'); return; }
        var fmt = function(d) { var p = d.split('-'); return p[2]+'-'+p[1]+'-'+p[0]; };
        document.getElementById('confirm_from').textContent = fmt(fromDate);
        document.getElementById('confirm_to').textContent   = fmt(toDate);
        new bootstrap.Modal(document.getElementById('confirmDeleteModal')).show();
    }
    function submitDeleteForm() { document.getElementById('deleteDateRangeForm').submit(); }

    @if($errors->has('delete_from_date') || $errors->has('delete_to_date'))
        document.addEventListener('DOMContentLoaded', function () {
            var panel = document.getElementById('deleteDateRangePanel');
            if (panel) panel.classList.add('show');
        });
    @endif
</script>

<link  rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const investorSearchUrl = "{{ url('/investor/search') }}";

    // ── Toast (defined FIRST so every handler below can call it) ─────
    function showToast(message, type) {
        type = type || 'success';
        var existing = document.getElementById('ajax-toast');
        if (existing) existing.remove();
        var c = {
            success: { bg:'#ecfdf5', border:'#10b981', color:'#065f46', icon:'✓' },
            error:   { bg:'#fff1f2', border:'#e63946', color:'#9f1239', icon:'✕' },
            warning: { bg:'#fffbeb', border:'#f59e0b', color:'#92400e', icon:'⚠' }
        }[type] || { bg:'#ecfdf5', border:'#10b981', color:'#065f46', icon:'✓' };

        var t = document.createElement('div');
        t.id  = 'ajax-toast';
        t.style.cssText = [
            'position:fixed','top:1.5rem','right:1.5rem','z-index:99999',
            'background:'+c.bg,'border-left:4px solid '+c.border,'color:'+c.color,
            'padding:0.9rem 1.4rem','border-radius:10px','font-size:0.88rem','font-weight:600',
            "font-family:'DM Sans',sans-serif",
            'box-shadow:0 8px 32px rgba(0,0,0,0.12)',
            'min-width:280px','max-width:400px',
            'display:flex','align-items:center','gap:0.6rem',
            'transform:translateX(120%)',
            'transition:transform 0.35s cubic-bezier(0.34,1.56,0.64,1)'
        ].join(';');
        t.innerHTML = '<span style="font-size:1.1rem">'+c.icon+'</span>'
                    + '<span style="flex:1">'+message+'</span>'
                    + '<span onclick="this.parentElement.remove()" style="cursor:pointer;opacity:0.5;font-size:1.1rem">&times;</span>';
        document.body.appendChild(t);
        requestAnimationFrame(function () {
            requestAnimationFrame(function () { t.style.transform = 'translateX(0)'; });
        });
        setTimeout(function () {
            t.style.transform = 'translateX(120%)';
            setTimeout(function () { if (t.parentElement) t.remove(); }, 380);
        }, 3500);
    }

    // ── Autocomplete helpers ──────────────────────────────────────────
    function hideAllSuggestionBoxes() {
        document.querySelectorAll('.suggestion-box').forEach(b => b.style.display = 'none');
    }
    function renderSuggestions(box, items, inputEl) {
        box.innerHTML = '';
        if (!items || !items.length) { box.style.display = 'none'; return; }
        items.forEach(name => {
            var div = document.createElement('div');
            div.className = 'suggestion-item';
            div.textContent = name;
            div.addEventListener('click', function () { inputEl.value = name; box.style.display = 'none'; });
            box.appendChild(div);
        });
        box.style.display = 'block';
    }
    function debounce(fn, delay) {
        var t;
        return function () { var args = arguments; clearTimeout(t); t = setTimeout(function () { fn.apply(this, args); }, delay); };
    }
    var handleInput = debounce(function (e) {
        var target = e.target;
        if (!target.classList.contains('investor-name-input')) return;
        var query = target.value.trim();
        var box = target.closest('.autocomplete-wrapper') && target.closest('.autocomplete-wrapper').querySelector('.suggestion-box');
        if (!box) return;
        if (query.length <= 1) { box.style.display = 'none'; return; }
        fetch(investorSearchUrl + '?q=' + encodeURIComponent(query))
            .then(function (r) { return r.json(); })
            .then(function (data) { renderSuggestions(box, data.slice(0, 10), target); })
            .catch(function () { box.style.display = 'none'; });
    }, 250);
    document.addEventListener('input', handleInput);
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.autocomplete-wrapper')) hideAllSuggestionBoxes();
    });
    document.addEventListener('keydown', function (e) {
        var active = document.activeElement;
        if (!active || !active.classList.contains('investor-name-input')) return;
        var box = active.closest('.autocomplete-wrapper') && active.closest('.autocomplete-wrapper').querySelector('.suggestion-box');
        if (!box || box.style.display === 'none') return;
        var items = Array.from(box.querySelectorAll('.suggestion-item'));
        if (!items.length) return;
        var idx = items.findIndex(function (it) { return it.classList.contains('active'); });
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            if (idx >= 0) items[idx].classList.remove('active');
            idx = Math.min(items.length - 1, idx + 1);
            items[idx].classList.add('active');
            items[idx].scrollIntoView({ block: 'nearest' });
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            if (idx >= 0) items[idx].classList.remove('active');
            idx = Math.max(0, idx - 1);
            items[idx].classList.add('active');
            items[idx].scrollIntoView({ block: 'nearest' });
        } else if (e.key === 'Enter' && idx >= 0) {
            e.preventDefault();
            items[idx].click();
        }
    });

    // ── Loader helper ─────────────────────────────────────────────────
    function showLoader(message) {
        var loader = document.createElement('div');
        loader.id = 'page-loader';
        loader.innerHTML = '<div class="loader-inner"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div><p>' + message + '</p></div>';
        document.body.appendChild(loader);
    }

    // ── Action URL builder ────────────────────────────────────────────
    function buildActionUrl(actionType, statementId) {
        var map = {
            'INVESTMENT':        '/bank-statement/set-action/' + statementId,
            'LOAN':              '/bank-statement/showloan/'   + statementId,
            'EXCESS':            '/bank-statement/excess/'     + statementId,
            'EXPANSES':          '/bank-statement/expanses/'   + statementId,
            'OD INTEREST':       '/bank-statement/set-action/' + statementId,
            'SELF TRANSACTION':  '/bank-statement/set-action/' + statementId
        };
        var base = map[actionType] || '/bank-statement/set-action/' + statementId;
        return base + '?action_type=' + encodeURIComponent(actionType);
    }

    // ── Action select → redirect ──────────────────────────────────────
    document.querySelectorAll('.action-select').forEach(function (select) {
        select.addEventListener('change', function () {
            var actionType = this.value;
            var id = this.dataset.id;
            if (!actionType) return;
            showLoader('Loading…');
            setTimeout(function () { window.location.href = buildActionUrl(actionType, id); }, 200);
        });
    });

    // ── View button ───────────────────────────────────────────────────
    document.querySelectorAll('.view-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var id = this.dataset.id;
            var actionType = this.dataset.action;
            if (!actionType) { alert('No action assigned for this statement.'); return; }
            showLoader('Redirecting…');
            setTimeout(function () { window.location.href = buildActionUrl(actionType, id); }, 200);
        });
    });

    // ── Edit / Cancel buttons ─────────────────────────────────────────
    document.querySelectorAll('.edit-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var id = this.dataset.id;
            document.querySelector('.action-view-' + id).style.display = 'none';
            document.querySelector('.action-edit-' + id).style.display = 'block';
        });
    });
    document.querySelectorAll('.cancel-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var id = this.dataset.id;
            document.querySelector('.action-edit-' + id).style.display = 'none';
            document.querySelector('.action-view-' + id).style.display = 'flex';
        });
    });

    // ── Show/hide categorize form ─────────────────────────────────────
    document.querySelectorAll('.show-form-btn').forEach(function (button) {
        button.addEventListener('click', function () {
            var id   = this.getAttribute('data-id');
            var form = document.querySelector('.refno-form[data-id="' + id + '"]');
            var hidden = form.style.display === 'none' || form.style.display === '';
            form.style.display  = hidden ? 'block' : 'none';
            this.textContent    = hidden ? 'Hide Form' : 'Categorize';
        });
    });

    // ── Banner helpers ────────────────────────────────────────────────
    function setBannerStyle(el, bg, borderColor) {
        el.style.cssText = [
            'position:fixed','bottom:1.5rem','right:1.5rem','z-index:99999',
            'background:'+bg,
            'border:1px solid '+borderColor,
            'border-left:5px solid '+borderColor,
            'border-radius:14px','padding:1.1rem 1.4rem',
            'min-width:300px','max-width:400px',
            "font-family:'DM Sans',sans-serif",
            'box-shadow:0 8px 32px rgba(0,0,0,0.15)',
            'transform:translateX(120%)',
            'transition:transform 0.4s cubic-bezier(0.34,1.56,0.64,1)'
        ].join(';');
    }
    function animateBannerIn(el) {
        requestAnimationFrame(function () {
            requestAnimationFrame(function () { el.style.transform = 'translateX(0)'; });
        });
    }
    function autoDismissBanner(el, ms) {
        setTimeout(function () {
            el.style.transform = 'translateX(120%)';
            setTimeout(function () { if (el.parentElement) el.remove(); }, 400);
        }, ms);
    }
    function removeBanner(id) {
        var el = document.getElementById(id);
        if (el) el.remove();
    }
    function fmtINR(n) {
        return Number(n).toLocaleString('en-IN', { minimumFractionDigits: 2 });
    }

    // ── Loan Balance Banner ───────────────────────────────────────────
    function showLoanBalanceBanner(refNo, balance, interest) {
        removeBanner('loan-balance-banner');
        var banner = document.createElement('div');
        banner.id  = 'loan-balance-banner';
        setBannerStyle(banner, '#fff1f2', '#e63946');
        banner.innerHTML =
            '<div style="display:flex;align-items:flex-start;justify-content:space-between;gap:0.75rem;">' +
                '<div style="flex:1;">' +
                    '<div style="font-weight:700;color:#e63946;font-size:0.9rem;margin-bottom:0.6rem;">⚠ Loan Pending — ' + refNo + '</div>' +
                    '<div style="display:flex;flex-direction:column;gap:0.4rem;">' +
                        '<div style="display:flex;justify-content:space-between;font-size:0.84rem;"><span style="color:#64748b;font-weight:500;">Remaining Principal</span><span style="font-weight:700;color:#e63946;">₹' + fmtINR(balance) + '</span></div>' +
                        '<div style="display:flex;justify-content:space-between;font-size:0.84rem;"><span style="color:#64748b;font-weight:500;">Interest Accrued</span><span style="font-weight:700;color:#4361ee;">₹' + fmtINR(interest) + '</span></div>' +
                        '<div style="display:flex;justify-content:space-between;font-size:0.84rem;border-top:1px solid #fecdd3;padding-top:0.4rem;margin-top:0.2rem;"><span style="color:#64748b;font-weight:500;">Total to Recover</span><span style="font-weight:700;color:#9f1239;">₹' + fmtINR(balance + interest) + '</span></div>' +
                    '</div>' +
                    '<div style="margin-top:0.6rem;font-size:0.73rem;color:#94a3b8;">Row will be hidden once fully repaid.</div>' +
                '</div>' +
                '<button onclick="document.getElementById(\'loan-balance-banner\').remove()" style="background:none;border:none;cursor:pointer;color:#94a3b8;font-size:1.3rem;line-height:1;padding:0;flex-shrink:0;">&times;</button>' +
            '</div>';
        document.body.appendChild(banner);
        animateBannerIn(banner);
        autoDismissBanner(banner, 10000);
    }

    // ── Investment Pending Interest Banner ────────────────────────────
    function showInvestmentPendingBanner(refNo, pendingInterest) {
        removeBanner('investment-pending-banner');
        var banner = document.createElement('div');
        banner.id  = 'investment-pending-banner';
        setBannerStyle(banner, '#fffbeb', '#f59e0b');
        banner.innerHTML =
            '<div style="display:flex;align-items:flex-start;justify-content:space-between;gap:0.75rem;">' +
                '<div style="flex:1;">' +
                    '<div style="font-weight:700;color:#92400e;font-size:0.9rem;margin-bottom:0.6rem;">💰 Investment Categorised — ' + refNo + '</div>' +
                    '<div style="display:flex;flex-direction:column;gap:0.4rem;">' +
                        '<div style="display:flex;justify-content:space-between;font-size:0.84rem;"><span style="color:#78350f;font-weight:600;">Principal</span><span style="font-weight:700;color:#059669;">Returned ✓</span></div>' +
                        '<div style="display:flex;justify-content:space-between;font-size:0.84rem;"><span style="color:#78350f;font-weight:600;">Pending Interest</span><span style="font-weight:700;color:#e63946;">₹' + fmtINR(pendingInterest) + '</span></div>' +
                    '</div>' +
                    '<div style="margin-top:0.65rem;background:#fef3c7;border:1px solid #fde68a;border-radius:7px;padding:0.45rem 0.7rem;font-size:0.76rem;color:#92400e;font-weight:600;">📋 Check <strong>Investment List → Pending Interest</strong> tab for details.</div>' +
                '</div>' +
                '<button onclick="document.getElementById(\'investment-pending-banner\').remove()" style="background:none;border:none;cursor:pointer;color:#94a3b8;font-size:1.3rem;line-height:1;padding:0;flex-shrink:0;">&times;</button>' +
            '</div>';
        document.body.appendChild(banner);
        animateBannerIn(banner);
        autoDismissBanner(banner, 10000);
    }

    // ── Fade rows helper ──────────────────────────────────────────────
    function fadeRows(crId, drId) {
        if (crId) $('tr[data-id="' + crId + '"]').fadeOut(500);
        if (drId) $('tr[data-id="' + drId + '"]').fadeOut(500);
    }

    // ── AJAX: Save Ref No ─────────────────────────────────────────────
    $(document).on('submit', '.refno-form', function (e) {
        e.preventDefault();
        var form  = $(this);
        var id    = form.data('id');
        var refNo = form.find('input[name="ref_no"]').val();

        $.ajax({
            url:    '/bank-statement/save-refno/' + id,
            method: 'POST',
            data:   form.serialize(),

            success: function (res) {
                // ── LOAN ──────────────────────────────────────────────
                if (res.type === 'loan') {
                    if (res.loan_balance > 0) {
                        showLoanBalanceBanner(refNo, res.loan_balance, res.loan_interest);
                        showToast(res.message, 'warning');
                    } else {
                        showToast(res.message, 'success');
                        fadeRows(res.cr_id, res.dr_id);
                    }
                    return;
                }
                // ── INVESTMENT ────────────────────────────────────────
                if (res.type === 'investment') {
                    if (res.interest_pending) {
                        showToast(res.message, 'warning');
                        showInvestmentPendingBanner(refNo, res.pending_interest);
                        fadeRows(res.cr_id, res.dr_id);
                    } else if (res.is_categorized) {
                        showToast(res.message, 'success');
                        fadeRows(res.cr_id, res.dr_id);
                    } else {
                        showToast(res.message || 'Not yet complete.', 'warning');
                    }
                    return;
                }
                // ── OTHER ─────────────────────────────────────────────
                if (res.success) {
                    showToast(res.message || 'Saved!', 'success');
                    if (res.is_categorized) fadeRows(res.cr_id, res.dr_id);
                } else {
                    showToast(res.message || 'Something went wrong.', 'error');
                }
            },
            error: function () {
                showToast('Server error. Please try again.', 'error');
            }
        });
    });

    // ── AJAX: Remove Ref No ───────────────────────────────────────────
    // ✅ Handler is INSIDE DOMContentLoaded so showToast is accessible
    $(document).on('click', '.remove-refno-btn', function (e) {
        e.preventDefault();
        e.stopPropagation();

        if (!confirm('Remove Ref No? This will reset paired Loan/Investment records too.')) return;

        var btn = $(this);
        var id  = btn.data('id');

        $.ajax({
            url:    '/bank-statement/remove-refno/' + id,
            method: 'POST',
            data:   { _token: '{{ csrf_token() }}' },

            success: function (res) {
                if (res.success) {
                    showToast(res.message || 'Ref No removed.', 'success');
                    // Clear input immediately so user sees feedback
                    $('tr[data-id="' + id + '"]').find('input[name="ref_no"]').val('');
                    btn.hide();
                    // Reload after toast
                    setTimeout(function () { location.reload(); }, 1400);
                } else {
                    showToast(res.message || 'Could not remove.', 'error');
                }
            },
            error: function (xhr) {
                showToast('Server error: ' + xhr.status, 'error');
            }
        });
    });

    // ── Soft Delete logic ─────────────────────────────────────────────
    var overlay         = document.getElementById('softDeleteOverlay');
    var cancelBtn       = document.getElementById('cancelSoftDelete');
    var softDeleteForm  = document.getElementById('softDeleteForm');

    document.querySelectorAll('.soft-delete-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            softDeleteForm.action = this.dataset.url;
            overlay.classList.add('show');
        });
    });
    cancelBtn.addEventListener('click', function () { overlay.classList.remove('show'); });
    overlay.addEventListener('click', function (e) {
        if (e.target === overlay) overlay.classList.remove('show');
    });

}); // ── END DOMContentLoaded ──────────────────────────────────────────
</script>
<div class="modal fade" id="manualCatModal" tabindex="-1" aria-labelledby="manualCatModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:460px;">
        <div class="modal-content" style="border-radius:16px;border:none;box-shadow:0 20px 60px rgba(67,97,238,0.18);">
 
            <div class="modal-header" style="background:linear-gradient(135deg,#4361ee 0%,#7209b7 100%);border-radius:16px 16px 0 0;padding:1.1rem 1.5rem;">
                <h5 class="modal-title" id="manualCatModalLabel"
                    style="font-family:'Syne',sans-serif;font-weight:700;color:#fff;font-size:1rem;display:flex;align-items:center;gap:0.5rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                    </svg>
                    Manual Categorize
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        style="filter:invert(1);opacity:0.7;"></button>
            </div>
 
            <div class="modal-body" style="padding:1.5rem;">
                {{-- Transaction summary --}}
                <div id="manualCatSummary"
                     style="background:#f8faff;border:1px solid #e2e8f0;border-radius:10px;
                            padding:0.85rem 1rem;margin-bottom:1.25rem;font-size:0.84rem;">
                </div>
 
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.78rem;color:#64748b;font-weight:700;
                                  text-transform:uppercase;letter-spacing:0.06em;margin-bottom:0.4rem;">
                        Category <span style="color:#e63946;">*</span>
                    </label>
                    <select id="manualCatSelect"
                            style="width:100%;background:#fff;border:1px solid #e2e8f0;border-radius:8px;
                                   color:#1e293b;font-size:0.85rem;padding:0.5rem 0.9rem;
                                   transition:border-color 0.2s;outline:none;">
                        <option value="">— Select category —</option>
                        <option value="INVESTMENT">INVESTMENT</option>
                        <option value="LOAN">LOAN</option>
                        <option value="EXPANSES">EXPANSES</option>
                        <option value="EXCESS">EXCESS</option>
                        <option value="OD INTEREST">OD INTEREST</option>
                        <option value="SELF TRANSACTION">SELF TRANSACTION</option>
                        <option value="OTHER">OTHER</option>
                    </select>
                    <div id="manualCatSelectErr"
                         style="color:#e63946;font-size:0.76rem;margin-top:0.3rem;display:none;">
                        Please select a category.
                    </div>
                </div>
 
                <div>
                    <label style="display:block;font-size:0.78rem;color:#64748b;font-weight:700;
                                  text-transform:uppercase;letter-spacing:0.06em;margin-bottom:0.4rem;">
                        Note <span style="color:#94a3b8;font-weight:400;">(optional)</span>
                    </label>
                    <textarea id="manualCatNote" rows="2" maxlength="500"
                              placeholder="Add a short reason or note…"
                              style="width:100%;background:#fff;border:1px solid #e2e8f0;border-radius:8px;
                                     color:#1e293b;font-size:0.84rem;padding:0.5rem 0.9rem;resize:vertical;
                                     outline:none;font-family:'DM Sans',sans-serif;transition:border-color 0.2s;">
                    </textarea>
                </div>
            </div>
 
            <div class="modal-footer" style="padding:1rem 1.5rem;border-top:1px solid #e2e8f0;">
                <button type="button" class="btn-ghost" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="manualCatSubmitBtn"
                        style="background:linear-gradient(135deg,#4361ee 0%,#7209b7 100%);
                               color:#fff;border:none;border-radius:8px;
                               padding:0.5rem 1.3rem;font-size:0.84rem;font-weight:700;
                               cursor:pointer;display:inline-flex;align-items:center;gap:0.4rem;
                               box-shadow:0 2px 8px rgba(67,97,238,0.25);
                               font-family:'DM Sans',sans-serif;transition:opacity 0.2s;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                    </svg>
                    Categorize
                </button>
            </div>
 
        </div>
    </div>
</div>
 
{{-- ── Manual Categorize JS ─────────────────────────────────── --}}
<script>
(function () {
    var CSRF      = '{{ csrf_token() }}';
    var BASE_URL  = '{{ url("/bank-statement/manual-categorize") }}';
 
    var modal         = null;   // Bootstrap Modal instance (lazy init)
    var currentId     = null;
 
    // ── helpers already defined in DOMContentLoaded, so duplicate inline ──
    function _toast(message, type) {
        type = type || 'success';
        var existing = document.getElementById('ajax-toast');
        if (existing) existing.remove();
        var c = {
            success: { bg:'#ecfdf5', border:'#10b981', color:'#065f46', icon:'✓' },
            error:   { bg:'#fff1f2', border:'#e63946', color:'#9f1239', icon:'✕' },
            warning: { bg:'#fffbeb', border:'#f59e0b', color:'#92400e', icon:'⚠' }
        }[type] || { bg:'#ecfdf5', border:'#10b981', color:'#065f46', icon:'✓' };
 
        var t = document.createElement('div');
        t.id  = 'ajax-toast';
        t.style.cssText = [
            'position:fixed','top:1.5rem','right:1.5rem','z-index:99999',
            'background:'+c.bg,'border-left:4px solid '+c.border,'color:'+c.color,
            'padding:0.9rem 1.4rem','border-radius:10px','font-size:0.88rem','font-weight:600',
            "font-family:'DM Sans',sans-serif",
            'box-shadow:0 8px 32px rgba(0,0,0,0.12)',
            'min-width:280px','max-width:400px',
            'display:flex','align-items:center','gap:0.6rem',
            'transform:translateX(120%)',
            'transition:transform 0.35s cubic-bezier(0.34,1.56,0.64,1)'
        ].join(';');
        t.innerHTML = '<span style="font-size:1.1rem">'+c.icon+'</span>'
                    + '<span style="flex:1">'+message+'</span>'
                    + '<span onclick="this.parentElement.remove()" style="cursor:pointer;opacity:0.5;font-size:1.1rem">&times;</span>';
        document.body.appendChild(t);
        requestAnimationFrame(function () {
            requestAnimationFrame(function () { t.style.transform = 'translateX(0)'; });
        });
        setTimeout(function () {
            t.style.transform = 'translateX(120%)';
            setTimeout(function () { if (t.parentElement) t.remove(); }, 380);
        }, 3500);
    }
 
    // ── Open modal when "Manual" button clicked ───────────────────────
    document.addEventListener('click', function (e) {
        var btn = e.target.closest('.manual-cat-btn');
        if (!btn) return;
 
        currentId = btn.dataset.id;
        var name   = btn.dataset.name   || '—';
        var amount = btn.dataset.amount || '0.00';
        var crdr   = btn.dataset.crdr   || '—';
 
        // Populate summary card
        var crdrColor  = crdr === 'CR' ? '#059669' : '#e63946';
        var crdrBg     = crdr === 'CR' ? '#ecfdf5' : '#fff1f2';
        var crdrBorder = crdr === 'CR' ? '#a7f3d0' : '#fecdd3';
 
        document.getElementById('manualCatSummary').innerHTML =
            '<div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:0.5rem;">' +
                '<div>' +
                    '<div style="font-size:0.7rem;color:#94a3b8;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;">Name</div>' +
                    '<div style="font-weight:600;color:#1e293b;font-size:0.9rem;">'+name+'</div>' +
                '</div>' +
                '<div style="text-align:right;">' +
                    '<div style="font-size:0.7rem;color:#94a3b8;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;">Amount</div>' +
                    '<div style="font-weight:700;color:#1e293b;font-size:0.9rem;font-variant-numeric:tabular-nums;">₹'+amount+'</div>' +
                '</div>' +
                '<span style="background:'+crdrBg+';color:'+crdrColor+';border:1px solid '+crdrBorder+';' +
                       'border-radius:5px;padding:0.22rem 0.7rem;font-size:0.75rem;font-weight:700;">'+crdr+'</span>' +
            '</div>';
 
        // Reset form and filter options for CR vs DR
        var selectEl = document.getElementById('manualCatSelect');
        selectEl.value = '';
        var isCr = (crdr === 'CR' || crdr === 'Credit');
        Array.from(selectEl.options).forEach(function(opt) {
            if (!opt.value) return;
            if (isCr) {
                opt.hidden = (opt.value === 'LOAN');
            } else {
                opt.hidden = (opt.value === 'INVESTMENT' || opt.value === 'OD INTEREST');
            }
        });

        document.getElementById('manualCatNote').value   = '';
        document.getElementById('manualCatSelectErr').style.display = 'none';
 
        // Show modal
        if (!modal) {
            modal = new bootstrap.Modal(document.getElementById('manualCatModal'));
        }
        modal.show();
    });
 
    // ── Submit ────────────────────────────────────────────────────────
    document.getElementById('manualCatSubmitBtn').addEventListener('click', function () {
        var category = document.getElementById('manualCatSelect').value;
        var note     = document.getElementById('manualCatNote').value.trim();
        var errEl    = document.getElementById('manualCatSelectErr');
 
        if (!category) {
            errEl.style.display = 'block';
            document.getElementById('manualCatSelect').style.borderColor = '#e63946';
            return;
        }
        errEl.style.display = 'none';
        document.getElementById('manualCatSelect').style.borderColor = '#e2e8f0';
 
        var submitBtn = this;
        submitBtn.disabled   = true;
        submitBtn.innerHTML  = '<span class="spinner-border spinner-border-sm" role="status"></span> Saving…';
 
        fetch(BASE_URL + '/' + currentId, {
            method:  'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF,
                'Accept':       'application/json',
            },
            body: JSON.stringify({ manual_category: category, manual_note: note }),
        })
        .then(function (r) { return r.json(); })
        .then(function (res) {
            submitBtn.disabled  = false;
            submitBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" viewBox="0 0 16 16"><path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/></svg> Categorize';
 
            if (res.success) {
                modal.hide();
                _toast(res.message, 'success');
                // Fade row out of main table
                var row = document.querySelector('tr[data-id="' + currentId + '"]');
                if (row) {
                    row.style.transition = 'opacity 0.5s';
                    row.style.opacity    = '0';
                    setTimeout(function () { row.remove(); }, 520);
                }
            } else {
                _toast(res.message || 'Something went wrong.', 'error');
            }
        })
        .catch(function () {
            submitBtn.disabled  = false;
            submitBtn.innerHTML = 'Categorize';
            _toast('Server error. Please try again.', 'error');
        });
    });
 
    // Focus border effect
    document.getElementById('manualCatSelect').addEventListener('focus', function () {
        this.style.borderColor = '#4361ee';
        this.style.boxShadow   = '0 0 0 3px rgba(67,97,238,0.1)';
    });
    document.getElementById('manualCatSelect').addEventListener('blur', function () {
        this.style.borderColor = '#e2e8f0';
        this.style.boxShadow   = 'none';
    });
    document.getElementById('manualCatNote').addEventListener('focus', function () {
        this.style.borderColor = '#4361ee';
        this.style.boxShadow   = '0 0 0 3px rgba(67,97,238,0.1)';
    });
    document.getElementById('manualCatNote').addEventListener('blur', function () {
        this.style.borderColor = '#e2e8f0';
        this.style.boxShadow   = 'none';
    });
})();
</script>
@endsection