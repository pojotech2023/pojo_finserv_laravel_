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

    /* Page Header */
    .page-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 1.75rem; padding-bottom: 1.25rem;
        border-bottom: 2px solid var(--border);
    }
    .page-title {
        font-family: var(--font-display); font-size: 1.65rem; font-weight: 800;
        background: linear-gradient(135deg, var(--accent) 0%, var(--accent2) 100%);
        -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        background-clip: text; margin: 0;
    }
    .page-subtitle { color: var(--muted); font-size: 0.82rem; margin-top: 3px; }

    /* Tabs */
    .tab-bar {
        display: flex; gap: 0; margin-bottom: 1.5rem;
        background: var(--surface); border: 1px solid var(--border);
        border-radius: 12px; padding: 5px; width: fit-content;
        box-shadow: 0 1px 4px rgba(67,97,238,0.07);
    }
    .tab-btn {
        padding: 0.55rem 1.5rem; border-radius: 9px; border: none;
        font-size: 0.84rem; font-weight: 600; font-family: var(--font-body);
        cursor: pointer; transition: all 0.2s; background: transparent;
        color: var(--muted); display: flex; align-items: center; gap: 0.5rem;
        text-decoration: none;
    }
    .tab-btn.active {
        background: linear-gradient(135deg, var(--accent) 0%, var(--accent2) 100%);
        color: #fff; box-shadow: 0 2px 8px rgba(67,97,238,0.28);
    }
    .tab-btn:hover:not(.active) { background: var(--accent-light); color: var(--accent); }
    .tab-count {
        background: rgba(255,255,255,0.25); color: inherit;
        border-radius: 20px; padding: 0.1rem 0.55rem;
        font-size: 0.72rem; font-weight: 700;
    }
    .tab-btn:not(.active) .tab-count {
        background: var(--border); color: var(--muted);
    }
    .tab-btn.cleared-tab.active {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        box-shadow: 0 2px 8px rgba(16,185,129,0.28);
    }

    /* Main Card */
    .main-card {
        background: var(--surface); border: 1px solid var(--border); border-radius: 16px;
        overflow: hidden; box-shadow: 0 2px 8px rgba(67,97,238,0.06);
    }
    .card-header-custom {
        background: linear-gradient(135deg, #4361ee 0%, #7209b7 100%);
        padding: 1rem 1.5rem; display: flex; align-items: center; justify-content: space-between;
    }
    .card-header-custom.cleared-header {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }
    .card-header-custom h6 {
        font-family: var(--font-display); font-size: 1rem; font-weight: 700;
        color: #fff; margin: 0;
    }

    /* Table */
    .custom-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .custom-table thead th {
        background: var(--surface2); color: var(--muted);
        font-size: 0.71rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.07em; padding: 0.85rem 1rem;
        border-bottom: 2px solid var(--border); white-space: nowrap;
    }
    .custom-table tbody tr { transition: background 0.12s; }
    .custom-table tbody tr:hover td { background: #f5f7ff; }
    .custom-table tbody tr:nth-child(even) td { background: #fafbff; }
    .custom-table tbody tr:nth-child(even):hover td { background: #f0f3ff; }
    .custom-table tbody td {
        padding: 0.9rem 1rem; border-bottom: 1px solid #f1f5f9;
        font-size: 0.84rem; color: var(--text); vertical-align: middle;
    }
    .custom-table tbody tr:last-child td { border-bottom: none; }

    /* Badges */
    .badge-cr { background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; border-radius: 5px; padding: 0.22rem 0.6rem; font-size: 0.71rem; font-weight: 700; }
    .badge-dr { background: #fff1f2; color: var(--danger); border: 1px solid #fecdd3; border-radius: 5px; padding: 0.22rem 0.6rem; font-size: 0.71rem; font-weight: 700; }
    .badge-ref { background: var(--accent-light); color: var(--accent); border: 1px solid #c7d2fe; border-radius: 5px; padding: 0.22rem 0.6rem; font-size: 0.71rem; font-weight: 700; font-family: monospace; }
    .badge-cleared { background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; border-radius: 5px; padding: 0.22rem 0.6rem; font-size: 0.71rem; font-weight: 700; }

    /* Mismatch name highlight */
    .name-mismatch { color: var(--danger); font-weight: 600; }
    .name-pair {
        display: flex; flex-direction: column; gap: 2px;
        background: #fff8f8; border: 1px solid #fecdd3;
        border-radius: 8px; padding: 0.4rem 0.6rem;
    }
    .name-pair .cr-name { font-size: 0.8rem; color: #059669; font-weight: 600; }
    .name-pair .dr-name { font-size: 0.8rem; color: var(--danger); font-weight: 600; }
    .name-pair .lbl { font-size: 0.67rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--muted); }

    /* Buttons */
    .btn-ghost {
        background: #fff; border: 1px solid var(--border); color: var(--muted);
        border-radius: 8px; padding: 0.45rem 1rem; font-size: 0.82rem; font-weight: 500;
        transition: all 0.2s; text-decoration: none; display: inline-flex;
        align-items: center; gap: 0.4rem; cursor: pointer;
        box-shadow: 0 1px 2px rgba(0,0,0,0.04);
    }
    .btn-ghost:hover { border-color: var(--accent); color: var(--accent); background: var(--accent-light); }

    .tbl-btn {
        background: #fff; border: 1px solid var(--border); border-radius: 7px;
        padding: 0.32rem 0.75rem; font-size: 0.76rem; font-weight: 600;
        color: var(--muted); cursor: pointer; transition: all 0.13s;
        text-decoration: none; display: inline-flex; align-items: center; gap: 0.3rem;
        font-family: var(--font-body);
    }
    .tbl-btn.success-btn { background: #ecfdf5; border-color: #a7f3d0; color: #059669; }
    .tbl-btn.success-btn:hover { background: #d1fae5; border-color: #059669; }
    .tbl-btn.primary-btn { background: var(--accent-light); border-color: #c7d2fe; color: var(--accent); }
    .tbl-btn.primary-btn:hover { background: #dde4fd; border-color: var(--accent); }
    .tbl-btn.danger-btn { background: #fff1f2; border-color: #fecdd3; color: var(--danger); }
    .tbl-btn.danger-btn:hover { background: #ffe4e6; border-color: var(--danger); }

    /* Cleared badge row */
    .cleared-row td { opacity: 0.75; }

    /* Pagination */
    .pagination .page-link {
        background: #fff !important; border: 1px solid var(--border) !important;
        color: var(--muted) !important; border-radius: 7px !important;
        margin: 0 2px; font-size: 0.82rem; padding: 0.38rem 0.72rem; transition: all 0.13s;
    }
    .pagination .page-item.active .page-link { background: var(--accent) !important; border-color: var(--accent) !important; color: #fff !important; }
    .pagination .page-link:hover { border-color: var(--accent) !important; color: var(--accent) !important; background: var(--accent-light) !important; }

    /* ── KYC Modal ── */
    .kyc-modal-overlay {
        position: fixed; inset: 0; background: rgba(15,23,42,0.4);
        backdrop-filter: blur(4px); z-index: 10000;
        display: flex; align-items: center; justify-content: center;
        opacity: 0; pointer-events: none; transition: opacity 0.25s;
    }
    .kyc-modal-overlay.show { opacity: 1; pointer-events: all; }
    .kyc-modal {
        background: #fff; border-radius: 18px; width: 90%; max-width: 520px;
        box-shadow: 0 24px 64px rgba(67,97,238,0.18);
        transform: translateY(20px) scale(0.98); transition: transform 0.25s;
        overflow: hidden;
        max-height: 90vh;
        display: flex;
        flex-direction: column;
    }
    .kyc-modal-overlay.show .kyc-modal { transform: translateY(0) scale(1); }
    .kyc-modal-header {
        background: linear-gradient(135deg, var(--accent) 0%, var(--accent2) 100%);
        padding: 1.1rem 1.5rem; display: flex; align-items: center; justify-content: space-between;
        flex-shrink: 0;
    }
    .kyc-modal-header h5 { font-family: var(--font-display); font-size: 1rem; font-weight: 700; color: #fff; margin: 0; }
    .kyc-modal-close { background: rgba(255,255,255,0.2); border: none; border-radius: 6px; color: #fff; width: 28px; height: 28px; cursor: pointer; font-size: 1rem; display: flex; align-items: center; justify-content: center; transition: background 0.15s; }
    .kyc-modal-close:hover { background: rgba(255,255,255,0.35); }
    .kyc-modal-body {
        padding: 1.5rem;
        overflow-y: auto;
        flex: 1 1 auto;
    }
    #kycFileList {
        max-height: 180px;
        overflow-y: auto;
        padding-right: 4px;
        margin-bottom: 0.5rem;
    }
    .kyc-file-list { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.5rem; }
    .kyc-file-item {
        display: flex; align-items: center; gap: 0.6rem;
        background: var(--surface2); border: 1px solid var(--border);
        border-radius: 8px; padding: 0.55rem 0.9rem; font-size: 0.82rem;
    }
    .kyc-file-item a { color: var(--accent); text-decoration: none; font-weight: 500; flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .kyc-file-item a:hover { text-decoration: underline; }
    .kyc-empty { color: var(--muted); font-size: 0.85rem; margin-bottom: 0.5rem; padding: 1rem; background: var(--surface2); border-radius: 8px; text-align: center; }
    .kyc-upload-zone {
        border: 2px dashed var(--border); border-radius: 10px;
        padding: 1.25rem; text-align: center; cursor: pointer;
        transition: all 0.2s; background: var(--surface2);
    }
    .kyc-upload-zone:hover, .kyc-upload-zone.drag-over { border-color: var(--accent); background: var(--accent-light); }
    .kyc-upload-zone input[type="file"] { display: none; }
    .kyc-upload-zone .upload-icon { color: var(--accent); margin-bottom: 0.5rem; }
    .kyc-upload-zone .upload-label { font-size: 0.85rem; color: var(--muted); }
    .kyc-upload-zone .upload-label strong { color: var(--accent); cursor: pointer; }
    .kyc-selected-file { margin-top: 0.75rem; font-size: 0.82rem; color: var(--accent); font-weight: 500; display: none; }
    .kyc-modal-footer {
        padding: 1rem 1.5rem; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 0.75rem;
        flex-shrink: 0;
        background: #fff;
    }
    .btn-primary-custom {
        background: var(--accent); color: #fff; border: none; border-radius: 8px;
        padding: 0.5rem 1.2rem; font-size: 0.84rem; font-weight: 600;
        transition: all 0.2s; cursor: pointer; display: inline-flex; align-items: center; gap: 0.4rem;
        box-shadow: 0 2px 8px rgba(67,97,238,0.22); font-family: var(--font-body);
    }
    .btn-primary-custom:hover { background: #3451d1; }
    .btn-primary-custom:disabled { opacity: 0.65; cursor: not-allowed; }

    /* Mismatch Confirm Overlay */
    .confirm-overlay {
        position: fixed; inset: 0; background: rgba(15,23,42,0.4);
        backdrop-filter: blur(3px); z-index: 10001;
        display: flex; align-items: center; justify-content: center;
        opacity: 0; pointer-events: none; transition: opacity 0.2s;
    }
    .confirm-overlay.show { opacity: 1; pointer-events: all; }
    .confirm-box {
        background: #fff; border-radius: 16px; padding: 2rem 2.25rem;
        max-width: 400px; width: 90%; box-shadow: 0 20px 60px rgba(67,97,238,0.2);
        transform: translateY(16px); transition: transform 0.2s;
        border-top: 4px solid var(--success);
    }
    .confirm-overlay.show .confirm-box { transform: translateY(0); }
    .confirm-box h5 { font-family: var(--font-display); font-weight: 700; font-size: 1.05rem; color: var(--success); margin-bottom: 0.5rem; }
    .confirm-box p { color: var(--muted); font-size: 0.87rem; margin-bottom: 1.4rem; }
    .confirm-actions { display: flex; gap: 0.75rem; justify-content: flex-end; }

    /* Toast */
    #ajax-toast {
        position: fixed; top: 1.5rem; right: 1.5rem; z-index: 99999;
        padding: 0.9rem 1.4rem; border-radius: 10px; font-size: 0.88rem;
        font-weight: 600; font-family: var(--font-body);
        box-shadow: 0 8px 32px rgba(0,0,0,0.12); min-width: 280px; max-width: 400px;
        display: flex; align-items: center; gap: 0.6rem;
        transform: translateX(120%);
        transition: transform 0.35s cubic-bezier(0.34,1.56,0.64,1);
    }

    ::-webkit-scrollbar { width: 5px; }
    ::-webkit-scrollbar-track { background: #f1f5f9; }
    ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
</style>

<div class="page-wrapper">

    {{-- Page Header --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">Unmatched Loans</h1>
            <p class="page-subtitle">Loans where CR and DR name do not match — review and resolve</p>
        </div>
        <a href="{{ route('bank.statement') }}" class="btn-ghost">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
            </svg>
            Back to Bank Statements
        </a>
    </div>

    {{-- Tabs --}}
    <div class="tab-bar">
        <a href="{{ route('unmatched.loans', ['tab' => 'unmatched']) }}"
           class="tab-btn {{ $tab === 'unmatched' ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" viewBox="0 0 16 16">
                <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
            </svg>
            Unmatched
            <span class="tab-count">{{ $unmatchedCount }}</span>
        </a>
        <a href="{{ route('unmatched.loans', ['tab' => 'cleared']) }}"
           class="tab-btn cleared-tab {{ $tab === 'cleared' ? 'active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" viewBox="0 0 16 16">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
            </svg>
            Mismatch Cleared
            <span class="tab-count">{{ $clearedCount }}</span>
        </a>
    </div>

    {{-- Main Card --}}
    <div class="main-card">
        <div class="card-header-custom {{ $tab === 'cleared' ? 'cleared-header' : '' }}">
            <h6>
                @if($tab === 'cleared')
                    ✓ Mismatch Cleared Records
                @else
                    ⚠ Unmatched Loan Records
                @endif
            </h6>
            <span style="color:rgba(255,255,255,0.75);font-size:0.8rem;">
                {{ $unmatched->total() }} record(s)
            </span>
        </div>

        <div style="overflow-x:auto;">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Ref No</th>
                        <th>CR Name</th>
                        <th>DR Name</th>
                        <th>Name Mismatch</th>
                        <th style="text-align:right;">CR Amount</th>
                        <th style="text-align:right;">DR Amount</th>
                        <th>CR Date</th>
                        <th>DR Date</th>
                        @if($tab === 'cleared')
                            <th>Cleared At</th>
                        @endif
                        <th style="text-align:center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($unmatched as $index => $item)
                    <tr data-ref="{{ $item['ref_no'] }}"
                        data-loan-id="{{ $item['loan']?->id ?? '' }}"
                        class="{{ $tab === 'cleared' ? 'cleared-row' : '' }}">

                        {{-- # --}}
                        <td style="color:var(--muted);font-size:0.78rem;">
                            {{ ($unmatched->currentPage() - 1) * $unmatched->perPage() + $loop->iteration }}
                        </td>

                        {{-- Ref No --}}
                        <td><span class="badge-ref">{{ $item['ref_no'] }}</span></td>

                        {{-- CR Name --}}
                        <td>
                            <span class="cr-name" style="color:#059669;font-weight:600;font-size:0.82rem;">
                                {{ $item['credit']->name }}
                            </span>
                        </td>

                        {{-- DR Name --}}
                        <td>
                            <span style="color:var(--danger);font-weight:600;font-size:0.82rem;">
                                {{ $item['debit']->name }}
                            </span>
                        </td>

                        {{-- Mismatch indicator --}}
                        <td>
                            @if($tab === 'cleared')
                                <span class="badge-cleared">✓ Resolved</span>
                            @else
                                <span class="badge-dr">⚠ Mismatch</span>
                            @endif
                        </td>

                        {{-- CR Amount --}}
                        <td style="text-align:right;font-variant-numeric:tabular-nums;font-weight:600;color:#059669;">
                            {{ number_format($item['credit']->amount, 2) }}
                        </td>

                        {{-- DR Amount --}}
                        <td style="text-align:right;font-variant-numeric:tabular-nums;font-weight:600;color:var(--danger);">
                            {{ number_format($item['debit']->amount, 2) }}
                        </td>

                        {{-- CR Date --}}
                        <td style="font-size:0.79rem;white-space:nowrap;">
                            {{ \Carbon\Carbon::parse($item['credit']->date)->format('d-m-Y') }}
                        </td>

                        {{-- DR Date --}}
                        <td style="font-size:0.79rem;white-space:nowrap;">
                            {{ \Carbon\Carbon::parse($item['debit']->date)->format('d-m-Y') }}
                        </td>

                        {{-- Cleared At (only for cleared tab) --}}
                        @if($tab === 'cleared')
                        <td style="font-size:0.79rem;white-space:nowrap;color:var(--muted);">
                            @if($item['loan']?->mismatch_cleared_at)
                                {{ \Carbon\Carbon::parse($item['loan']->mismatch_cleared_at)->format('d-m-Y H:i') }}
                            @else
                                —
                            @endif
                        </td>
                        @endif

                        {{-- Actions --}}
                        <td style="text-align:center;">
                            <div style="display:flex;gap:0.4rem;justify-content:center;flex-wrap:wrap;">

                                @if($tab === 'unmatched')
                                    {{-- Mismatch Cleared Button --}}
                                    <button type="button"
                                        class="tbl-btn success-btn mark-cleared-btn"
                                        data-ref="{{ $item['ref_no'] }}"
                                        data-loan-id="{{ $item['loan']?->id ?? '' }}"
                                        {{ !$item['loan'] ? 'disabled title=No loan record found' : '' }}>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                                        </svg>
                                        Mismatch Cleared
                                    </button>
                                @endif

                                {{-- Upload KYC Button --}}
                                <button type="button"
                                    class="tbl-btn primary-btn upload-kyc-btn"
                                    data-loan-id="{{ $item['loan']?->id ?? '' }}"
                                    data-ref="{{ $item['ref_no'] }}"
                                    {{ !$item['loan'] ? 'disabled title=No loan record found' : '' }}>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                                        <path d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708l3-3z"/>
                                    </svg>
                                    Upload KYC
                                </button>

                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ $tab === 'cleared' ? 11 : 10 }}"
                            style="text-align:center;padding:3.5rem 1rem;color:var(--muted);font-size:0.88rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor"
                                 style="opacity:0.15;display:block;margin:0 auto 1rem;" viewBox="0 0 16 16">
                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                            </svg>
                            @if($tab === 'cleared')
                                No mismatch-cleared records yet.
                            @else
                                No unmatched loan records found. All names are consistent!
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-3 d-flex justify-content-end">
        {{ $unmatched->links('pagination::bootstrap-5') }}
    </div>

</div>

{{-- ── Mismatch Cleared Confirm Overlay ── --}}
<div class="confirm-overlay" id="mismatchConfirmOverlay">
    <div class="confirm-box">
        <h5>
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                 style="margin-right:6px;vertical-align:-2px;" viewBox="0 0 16 16">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
            </svg>
            Confirm Mismatch Cleared
        </h5>
        <p>
            Mark Ref No <strong id="confirmRefNo" style="color:var(--accent);"></strong> as
            <strong>Mismatch Cleared</strong>? This will move it to the Cleared tab.
        </p>
        <div class="confirm-actions">
            <button type="button" class="btn-ghost" id="cancelMismatch">Cancel</button>
            <button type="button" class="btn-primary-custom" id="confirmMismatch"
                    style="background:linear-gradient(135deg,#10b981,#059669);">
                Yes, Mark Cleared
            </button>
        </div>
    </div>
</div>

<div class="kyc-modal-overlay" id="kycModalOverlay">
    <div class="kyc-modal">
        <div class="kyc-modal-header">
            <h5>
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor"
                     style="margin-right:6px;vertical-align:-2px;" viewBox="0 0 16 16">
                    <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0zM9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1zM4.5 9a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1h-7zM4 10.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm.5 2.5a.5.5 0 0 1 0-1h4a.5.5 0 0 1 0 1h-4z"/>
                </svg>
                KYC Documents — <span id="kycRefLabel" style="opacity:0.8;font-size:0.85rem;"></span>
            </h5>
            <button class="kyc-modal-close" id="closeKycModal">&times;</button>
        </div>
        <div class="kyc-modal-body">

            {{-- Existing files --}}
            <p style="font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:var(--muted);margin-bottom:0.6rem;">
                Uploaded Files
            </p>
            <div id="kycFileList">
                <div class="kyc-empty">Loading…</div>
            </div>

            {{-- Upload zone --}}
            <p style="font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:var(--muted);margin:1.1rem 0 0.6rem;">
                Add New KYC File <span style="font-weight:400;text-transform:none;letter-spacing:0;font-size:0.75rem;color:#94a3b8;">(optional)</span>
            </p>
            <div class="kyc-upload-zone" id="kycUploadZone" onclick="document.getElementById('kycFileInput').click()">
                <div class="upload-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                        <path d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708l3-3z"/>
                    </svg>
                </div>
                <p class="upload-label">
                    Drag & drop or <strong>browse files</strong><br>
                    <span style="font-size:0.75rem;opacity:0.7;">PDF, JPG, PNG, DOC — max 5MB</span>
                </p>
                <input type="file" id="kycFileInput" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
            </div>

            {{-- Selected file name --}}
            <div class="kyc-selected-file" id="kycSelectedFile" style="display:none;margin-top:0.6rem;display:none;align-items:center;gap:0.4rem;font-size:0.82rem;color:var(--accent);font-weight:500;">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0zM9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1z"/>
                </svg>
                <span id="kycSelectedFileName"></span>
                <span onclick="clearKycFile()" style="cursor:pointer;color:var(--danger);margin-left:4px;font-size:1rem;">×</span>
            </div>

            {{-- Divider --}}
            <div style="border-top:1px dashed var(--border);margin:1.25rem 0;"></div>

            {{-- ✅ Checkbox: Mark as Mismatch Cleared --}}
            <label id="kycClearCheckboxWrapper" style="
                display:flex; align-items:flex-start; gap:0.75rem;
                background: #f0fdf4; border: 1px solid #a7f3d0;
                border-radius: 10px; padding: 0.85rem 1rem; cursor:pointer;
                transition: background 0.15s;
            ">
                <input type="checkbox" id="kycMarkCleared"
                    style="width:17px;height:17px;margin-top:1px;accent-color:#10b981;cursor:pointer;flex-shrink:0;">
                <div>
                    <div style="font-size:0.85rem;font-weight:700;color:#065f46;margin-bottom:2px;">
                        Mark as Mismatch Cleared
                    </div>
                    <div style="font-size:0.77rem;color:#059669;line-height:1.4;">
                        Tick this to move this record to the <strong>Mismatch Cleared</strong> tab.
                        You can do this with or without uploading a file.
                    </div>
                </div>
            </label>

        </div>
        <div class="kyc-modal-footer">
            <button type="button" class="btn-ghost" id="cancelKycModal">Cancel</button>

            {{-- Upload only (shown when file selected, checkbox not ticked) --}}
            <button type="button" class="btn-primary-custom" id="submitKycUpload"
                    style="display:none;">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                    <path d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708l3-3z"/>
                </svg>
                Upload File
            </button>

            {{-- Submit (shown when checkbox ticked — with or without file) --}}
            <button type="button" class="btn-primary-custom" id="submitKycAndClear"
                    style="display:none;background:linear-gradient(135deg,#10b981,#059669);box-shadow:0 2px 8px rgba(16,185,129,0.3);">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                </svg>
                <span id="submitBtnLabel">Submit & Clear</span>
            </button>
        </div>
    </div>
</div>

{{-- Toast --}}
<div id="ajax-toast" style="display:none;"></div>

{{-- Replace the entire <script> block in unmatched-loans.blade.php with this --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const csrfToken = '{{ csrf_token() }}';

    // ── Toast ─────────────────────────────────────────────────────────
    function showToast(message, type) {
        type = type || 'success';
        const colors = {
            success: { bg:'#ecfdf5', border:'#10b981', color:'#065f46', icon:'✓' },
            error:   { bg:'#fff1f2', border:'#e63946', color:'#9f1239', icon:'✕' }
        };
        const c = colors[type] || colors.success;
        let toast = document.getElementById('ajax-toast');
        if (!toast) { toast = document.createElement('div'); toast.id = 'ajax-toast'; document.body.appendChild(toast); }
        toast.style.cssText = `
            position:fixed;top:1.5rem;right:1.5rem;z-index:99999;
            background:${c.bg};border-left:4px solid ${c.border};color:${c.color};
            padding:0.9rem 1.4rem;border-radius:10px;font-size:0.88rem;
            font-weight:600;font-family:'DM Sans',sans-serif;
            box-shadow:0 8px 32px rgba(0,0,0,0.12);min-width:280px;max-width:400px;
            display:flex;align-items:center;gap:0.6rem;
            transform:translateX(120%);
            transition:transform 0.35s cubic-bezier(0.34,1.56,0.64,1);
        `;
        toast.innerHTML = `<span style="font-size:1.1rem">${c.icon}</span><span style="flex:1">${message}</span>`;
        requestAnimationFrame(() => requestAnimationFrame(() => toast.style.transform = 'translateX(0)'));
        setTimeout(() => {
            toast.style.transform = 'translateX(120%)';
        }, 3500);
    }

    // ── Mismatch Confirmed Overlay (standalone button) ─────────────────
    const mismatchOverlay = document.getElementById('mismatchConfirmOverlay');
    const cancelMismatch  = document.getElementById('cancelMismatch');
    const confirmBtn      = document.getElementById('confirmMismatch');
    const confirmRefLabel = document.getElementById('confirmRefNo');
    let pendingRefNo = '', pendingLoanId = '', pendingRow = null;

    document.querySelectorAll('.mark-cleared-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            if (this.disabled) return;
            pendingRefNo  = this.dataset.ref;
            pendingLoanId = this.dataset.loanId;
            pendingRow    = this.closest('tr');
            confirmRefLabel.textContent = pendingRefNo;
            mismatchOverlay.classList.add('show');
        });
    });

    if (cancelMismatch) cancelMismatch.addEventListener('click', () => mismatchOverlay.classList.remove('show'));
    if (mismatchOverlay) mismatchOverlay.addEventListener('click', e => { if (e.target === mismatchOverlay) mismatchOverlay.classList.remove('show'); });

    function doMarkCleared(refNo, row, onDone) {
        fetch(`/unmatched-loans/mark-cleared/${encodeURIComponent(refNo)}`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' },
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showToast('Marked as Mismatch Cleared!', 'success');
                if (row) {
                    row.style.transition = 'opacity 0.4s, transform 0.4s';
                    row.style.opacity = '0';
                    row.style.transform = 'translateX(30px)';
                    setTimeout(() => row.remove(), 450);
                }
                if (onDone) onDone();
                else setTimeout(() => { window.location.href = '{{ route("unmatched.loans", ["tab" => "cleared"]) }}'; }, 900);
            } else {
                showToast(data.message || 'Something went wrong.', 'error');
                if (onDone) onDone(true); // pass error flag
            }
        })
        .catch(() => {
            showToast('Server error. Please try again.', 'error');
            if (onDone) onDone(true);
        });
    }

    if (confirmBtn) {
        confirmBtn.addEventListener('click', function () {
            confirmBtn.disabled = true;
            confirmBtn.textContent = 'Processing…';
            doMarkCleared(pendingRefNo, pendingRow, (err) => {
                mismatchOverlay.classList.remove('show');
                confirmBtn.disabled = false;
                confirmBtn.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                    </svg> Yes, Mark Cleared`;
                if (!err) setTimeout(() => { window.location.href = '{{ route("unmatched.loans", ["tab" => "cleared"]) }}'; }, 900);
            });
        });
    }

    // ── KYC Modal ──────────────────────────────────────────────────────
    const kycOverlay    = document.getElementById('kycModalOverlay');
    const closeKycBtn   = document.getElementById('closeKycModal');
    const cancelKycBtn  = document.getElementById('cancelKycModal');
    const kycFileInput  = document.getElementById('kycFileInput');
    const submitUpload  = document.getElementById('submitKycUpload');
    const submitClear   = document.getElementById('submitKycAndClear');
    const submitLabel   = document.getElementById('submitBtnLabel');
    const kycRefLabel   = document.getElementById('kycRefLabel');
    const kycFileList   = document.getElementById('kycFileList');
    const kycSelected   = document.getElementById('kycSelectedFile');
    const kycSelName    = document.getElementById('kycSelectedFileName');
    const markCleared   = document.getElementById('kycMarkCleared');

    let activeLoanId = '', activeRef = '', activeRow = null;

    // Update footer buttons based on state
    function updateModalButtons() {
        const hasFile    = kycFileInput && kycFileInput.files.length > 0;
        const isCleared  = markCleared && markCleared.checked;

        // Show Upload only when file selected AND checkbox NOT ticked
        if (submitUpload) submitUpload.style.display = (hasFile && !isCleared) ? 'inline-flex' : 'none';

        // Show Submit & Clear when checkbox ticked (with or without file)
        if (submitClear) {
            submitClear.style.display = isCleared ? 'inline-flex' : 'none';
            if (isCleared && submitLabel) {
                submitLabel.textContent = hasFile ? 'Upload & Mark Cleared' : 'Mark as Cleared';
            }
        }
    }

    function loadKycFiles() {
        if (!activeLoanId) return;
        kycFileList.innerHTML = '<div class="kyc-empty">Loading…</div>';
        fetch(`/unmatched-loans/kyc-files/${activeLoanId}`, {
            headers: { 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success && data.files && data.files.length > 0) {
                kycFileList.innerHTML = `<ul class="kyc-file-list">${
                    data.files.map(f => `
                        <li class="kyc-file-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16" style="color:var(--accent);flex-shrink:0">
                                <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0zM9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1z"/>
                            </svg>
                            <a href="${f.url}" target="_blank">${f.filename}</a>
                        </li>`
                    ).join('')
                }</ul>`;
            } else {
                kycFileList.innerHTML = '<div class="kyc-empty">No KYC files uploaded yet.</div>';
            }
        })
        .catch(() => {
            kycFileList.innerHTML = '<div class="kyc-empty" style="color:var(--danger)">Failed to load files.</div>';
        });
    }

    function openKycModal(loanId, refNo, row) {
        activeLoanId = loanId;
        activeRef    = refNo;
        activeRow    = row;
        kycRefLabel.textContent = refNo;

        // Reset state
        if (kycFileInput) kycFileInput.value = '';
        if (kycSelected) kycSelected.style.display = 'none';
        if (markCleared) markCleared.checked = false;
        updateModalButtons();

        kycOverlay.classList.add('show');
        loadKycFiles();
    }

    document.querySelectorAll('.upload-kyc-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            if (this.disabled) return;
            openKycModal(this.dataset.loanId, this.dataset.ref, this.closest('tr'));
        });
    });

    function closeModal() { kycOverlay.classList.remove('show'); }
    if (closeKycBtn) closeKycBtn.addEventListener('click', closeModal);
    if (cancelKycBtn) cancelKycBtn.addEventListener('click', closeModal);
    if (kycOverlay) kycOverlay.addEventListener('click', e => { if (e.target === kycOverlay) closeModal(); });

    // Drag & drop
    const uploadZone = document.getElementById('kycUploadZone');
    if (uploadZone) {
        uploadZone.addEventListener('dragover', e => { e.preventDefault(); uploadZone.classList.add('drag-over'); });
        uploadZone.addEventListener('dragleave', () => uploadZone.classList.remove('drag-over'));
        uploadZone.addEventListener('drop', e => {
            e.preventDefault();
            uploadZone.classList.remove('drag-over');
            if (e.dataTransfer.files.length) {
                // DataTransfer workaround
                const dt = new DataTransfer();
                dt.items.add(e.dataTransfer.files[0]);
                kycFileInput.files = dt.files;
                kycFileInput.dispatchEvent(new Event('change'));
            }
        });
    }

    if (kycFileInput) {
        kycFileInput.addEventListener('change', function () {
            if (this.files.length) {
                kycSelName.textContent = this.files[0].name;
                kycSelected.style.display = 'flex';
            } else {
                kycSelected.style.display = 'none';
            }
            updateModalButtons();
        });
    }

    if (markCleared) {
        markCleared.addEventListener('change', updateModalButtons);
    }

    // Upload only (no clear)
    if (submitUpload) {
        submitUpload.addEventListener('click', function () {
            if (!kycFileInput.files.length) return;
            doUploadFile(() => {
                showToast('File uploaded successfully!', 'success');
                loadKycFiles();
                kycFileInput.value = '';
                kycSelected.style.display = 'none';
                updateModalButtons();
            });
        });
    }

    // Upload + Mark Cleared OR just Mark Cleared
    if (submitClear) {
        submitClear.addEventListener('click', function () {
            const hasFile = kycFileInput && kycFileInput.files.length > 0;
            submitClear.disabled = true;
            submitClear.querySelector('#submitBtnLabel').textContent = 'Processing…';

            function proceed() {
                // Now mark cleared
                doMarkCleared(activeRef, activeRow, (err) => {
                    submitClear.disabled = false;
                    updateModalButtons();
                    if (!err) {
                        closeModal();
                        setTimeout(() => {
                            window.location.href = '{{ route("unmatched.loans", ["tab" => "cleared"]) }}';
                        }, 700);
                    }
                });
            }

            if (hasFile) {
                doUploadFile((err) => {
                    if (err) {
                        submitClear.disabled = false;
                        updateModalButtons();
                        return;
                    }
                    showToast('File uploaded!', 'success');
                    loadKycFiles();
                    proceed();
                });
            } else {
                proceed();
            }
        });
    }

    // ── Helper: do file upload ─────────────────────────────────────────
    function doUploadFile(callback) {
        const formData = new FormData();
        formData.append('kyc_file', kycFileInput.files[0]);
        formData.append('_token', csrfToken);

        fetch(`/unmatched-loans/upload-kyc/${activeLoanId}`, {
            method: 'POST',
            headers: { 'Accept': 'application/json' },
            body: formData,
        })
        .then(r => {
            if (!r.ok) throw new Error('Upload failed: ' + r.status);
            return r.json();
        })
        .then(data => {
            if (data.success) {
                callback(false);
            } else {
                showToast(data.message || 'Upload failed.', 'error');
                callback(true);
            }
        })
        .catch(err => {
            console.error('Upload error:', err);
            showToast('Upload failed. Check file size/type and try again.', 'error');
            callback(true);
        });
    }

}); // DOMContentLoaded

// Clear file selection
function clearKycFile() {
    document.getElementById('kycFileInput').value = '';
    document.getElementById('kycSelectedFile').style.display = 'none';
    document.getElementById('kycSelectedFileName').textContent = '';
    // trigger button update
    document.getElementById('kycFileInput').dispatchEvent(new Event('change'));
}
</script>

@endsection