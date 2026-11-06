@extends('layouts.app')

@section('content')

<style>
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.2) !important;
    }
    .btn { transition: all 0.3s ease; }
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    .total-card .card-body .flex-grow-1 {
        min-width: 0;
        overflow: visible !important;
    }
    .total-card .card-body h3 {
        font-size: clamp(0.7rem, 1.4vw, 1.25rem);
        word-break: break-all;
        overflow-wrap: anywhere;
        white-space: normal;
        line-height: 1.2;
        margin: 0;
    }

    /* ── Pending Investment Banner ── */
    .pi-banner {
        background: linear-gradient(135deg, #f7971e 0%, #ffd200 100%);
        border-radius: 14px;
        padding: 14px 20px;
        display: flex;
        align-items: center;
        gap: 14px;
        box-shadow: 0 6px 24px rgba(247,151,30,0.35);
        text-decoration: none;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        overflow: hidden;
        position: relative;
    }
    .pi-banner:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 32px rgba(247,151,30,0.5);
    }
    .pi-banner::before {
        content: '';
        position: absolute;
        width: 160px; height: 160px;
        background: rgba(255,255,255,0.12);
        border-radius: 50%;
        top: -60px; right: 120px;
        pointer-events: none;
    }

    /* ── Pending Loan Banner ── */
    .pl-banner {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
        border-radius: 14px;
        padding: 14px 20px;
        display: flex;
        align-items: center;
        gap: 14px;
        box-shadow: 0 6px 24px rgba(15,52,96,0.45);
        text-decoration: none;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        overflow: hidden;
        position: relative;
    }
    .pl-banner:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 32px rgba(15,52,96,0.65);
    }
    .pl-banner::before {
        content: '';
        position: absolute;
        width: 160px; height: 160px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
        top: -60px; right: 120px;
        pointer-events: none;
    }

    .pi-icon {
        width: 48px; height: 48px;
        background: rgba(255,255,255,0.28);
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem;
        color: #fff;
        flex-shrink: 0;
    }
    .pi-label {
        font-size: 0.6rem;
        font-weight: 800;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: rgba(255,255,255,0.8);
        margin: 0;
    }
    .pi-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: #fff;
        margin: 0;
    }
    .pi-sub {
        font-size: 0.75rem;
        color: rgba(255,255,255,0.75);
        margin: 0;
    }
    .pi-cta {
        margin-left: auto;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .pi-badge {
        width: 42px; height: 42px;
        background: #fff;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem;
        font-weight: 900;
        box-shadow: 0 3px 10px rgba(0,0,0,0.15);
    }
    .pi-badge-orange { color: #d4600a; }
    .pi-badge-blue   { color: #0f3460; }
    .pi-btn {
        background: rgba(255,255,255,0.22);
        color: #fff;
        border: 2px solid rgba(255,255,255,0.6);
        border-radius: 50px;
        padding: 8px 20px;
        font-size: 0.82rem;
        font-weight: 700;
        white-space: nowrap;
        transition: background 0.2s ease;
    }
    .pi-banner:hover .pi-btn,
    .pl-banner:hover .pi-btn {
        background: rgba(255,255,255,0.38);
    }

    /* ── Loan Formula Box ── */
    .loan-formula-box {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9ff 100%);
        border: none;
        border-radius: 12px;
        padding: 10px 12px;
        margin-top: 8px;
        font-size: 11px;
        line-height: 2;
        text-align: left;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
        position: relative;
        overflow: hidden;
    }
    .loan-formula-box::before {
        content: '';
        position: absolute;
        top: 0; left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px 0 0 12px;
    }
    .loan-formula-box .formula-title {
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: #667eea;
        margin-bottom: 6px;
        padding-bottom: 5px;
        border-bottom: 1px solid #e8ecff;
    }
    .loan-formula-box .formula-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1px 0;
    }
    .loan-formula-box .formula-label {
        color: #6c757d;
        font-size: 10.5px;
    }
    .loan-formula-box .formula-value-green {
        font-weight: 700;
        color: #11998e;
        font-size: 11px;
    }
    .loan-formula-box .formula-value-red {
        font-weight: 700;
        color: #eb3349;
        font-size: 11px;
    }
    .loan-formula-box .formula-divider {
        border: none;
        border-top: 1.5px dashed #dee2e6;
        margin: 6px 0;
    }
    .loan-formula-box .formula-net {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
        border-radius: 6px;
        padding: 4px 6px;
        font-weight: 800;
        font-size: 11.5px;
    }

    /* ── Loan Toggle Trigger ── */
    .loan-toggle-trigger {
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: opacity 0.2s ease;
    }
    .loan-toggle-trigger:hover {
        opacity: 0.8;
    }
    .loan-toggle-trigger .toggle-hint {
        font-size: 0.65rem;
        color: #999;
        font-weight: 400;
        display: block;
        margin-top: 2px;
    }
    .loan-formula-box {
        animation: fadeSlideDown 0.25s ease;
    }
    @keyframes fadeSlideDown {
        from { opacity: 0; transform: translateY(-6px); }
        to   { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="container-fluid mt-4">

    {{-- ===== PAGE HEADER ===== --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg"
                 style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-white text-center py-4">
                    <h2 class="mb-2 fw-bold">
                        <i class="bi bi-speedometer2 me-2"></i>Financial Dashboard
                    </h2>
                    <p class="mb-0 fs-6">Complete Overview of All Financial Categories</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== GRAND TOTALS ===== --}}
    <div class="row g-3 mb-4">

        {{-- Total Credit --}}
        <div class="col-xl-3 col-md-6">
            <div class="card total-card border-0 shadow-lg h-100"
                 style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                <div class="card-body text-white">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-white bg-opacity-25 rounded-3 p-3">
                                <i class="bi bi-arrow-down-circle-fill fs-2"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3" style="min-width:0;">
                            <p class="mb-1 small fw-semibold opacity-75">Total Credit</p>
                            <h3 class="mb-0 fw-bold">₹{{ number_format($grandTotalCredit, 2) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Debit --}}
        <div class="col-xl-3 col-md-6">
            <div class="card total-card border-0 shadow-lg h-100"
                 style="background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);">
                <div class="card-body text-white">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-white bg-opacity-25 rounded-3 p-3">
                                <i class="bi bi-arrow-up-circle-fill fs-2"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3" style="min-width:0;">
                            <p class="mb-1 small fw-semibold opacity-75">Total Debit</p>
                            <h3 class="mb-0 fw-bold">₹{{ number_format($grandTotalDebit, 2) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Profit / Loss --}}
        <div class="col-xl-3 col-md-6">
            <div class="card total-card border-0 shadow-lg h-100"
                 style="background: {{ $grandTotalProfit >= 0
                    ? 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)'
                    : 'linear-gradient(135deg, #eb3349 0%, #f45c43 100%)' }};">
                <div class="card-body text-white">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-white bg-opacity-25 rounded-3 p-3">
                                <i class="bi bi-graph-{{ $grandTotalProfit >= 0 ? 'up' : 'down' }}-arrow fs-2"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3" style="min-width:0;">
                            <p class="mb-1 small fw-semibold opacity-75">
                                Total {{ $grandTotalProfit >= 0 ? 'Profit' : 'Loss' }}
                            </p>
                            <h3 class="mb-0 fw-bold">₹{{ number_format(abs($grandTotalProfit), 2) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Balance --}}
        <div class="col-xl-3 col-md-6">
            <div class="card total-card border-0 shadow-lg h-100"
                 style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                <div class="card-body text-white">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-white bg-opacity-25 rounded-3 p-3">
                                <i class="bi bi-wallet2 fs-2"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3" style="min-width:0;">
                            <p class="mb-1 small fw-semibold opacity-75">Total Balance</p>
                            <h3 class="mb-0 fw-bold">₹{{ number_format($grandTotalBalance, 2) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ===== PENDING BANNERS ===== --}}
    @php
        $showInvestmentBanner = isset($pendingCount) && $pendingCount > 0;
        $showLoanBanner       = isset($pendingLoanCount) && $pendingLoanCount > 0;
    @endphp

    @if($showInvestmentBanner || $showLoanBanner)
    <div class="row mb-4 g-3">

        @if($showInvestmentBanner)
        <div class="{{ $showLoanBanner ? 'col-md-6' : 'col-12' }}">
            <a href="{{ route('investment.pending.interest') }}" class="pi-banner">
                <div class="pi-icon">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <div>
                    <p class="pi-label">Action Required</p>
                    <p class="pi-title">Pending Investment Interest</p>
                    <p class="pi-sub">
                        {{ $pendingCount }} investment{{ $pendingCount > 1 ? 's' : '' }} waiting for interest collection
                    </p>
                </div>
                <div class="pi-cta">
                    <div class="pi-badge pi-badge-orange">{{ $pendingCount }}</div>
                    <div class="pi-btn">
                        <i class="bi bi-arrow-right-circle me-1"></i>View Pending
                    </div>
                </div>
            </a>
        </div>
        @endif

        @if($showLoanBanner)
        <div class="{{ $showInvestmentBanner ? 'col-md-6' : 'col-12' }}">
            <a href="{{ route('loan.interest.list') }}" class="pl-banner">
                <div class="pi-icon" style="background: rgba(255,255,255,0.15);">
                    <i class="bi bi-bank2"></i>
                </div>
                <div>
                    <p class="pi-label" style="color: rgba(255,255,255,0.65);">Action Required</p>
                    <p class="pi-title">Pending Loan Interest</p>
                    <p class="pi-sub">
                        {{ $pendingLoanCount }} loan{{ $pendingLoanCount > 1 ? 's' : '' }} with pending interest approval
                    </p>
                </div>
                <div class="pi-cta">
                    <div class="pi-badge pi-badge-blue">{{ $pendingLoanCount }}</div>
                    <div class="pi-btn">
                        <i class="bi bi-arrow-right-circle me-1"></i>View Pending
                    </div>
                </div>
            </a>
        </div>
        @endif

    </div>
    @endif

    {{-- ===== CATEGORY BREAKDOWN TABLE ===== --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg">
                <div class="card-header text-white py-3"
                     style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-diagram-3 me-2"></i>Category Breakdown
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead style="background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);">
                                <tr>
                                    <th class="ps-4 py-3 fw-bold">Category</th>
                                    <th class="text-center py-3 fw-bold">Total Records</th>
                                    <th class="text-end py-3 fw-bold">Credit</th>
                                    <th class="text-end py-3 fw-bold">Debit</th>
                                    <th class="text-end py-3 fw-bold">Profit / Loss</th>
                                    <th class="text-end py-3 fw-bold">Balance</th>
                                    <th class="text-center pe-4 py-3 fw-bold">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dashboardData as $data)
                                <tr class="border-bottom">

                                    {{-- Category Icon + Name --}}
                                    <td class="ps-4 py-3">
                                        <div class="d-flex align-items-center">
                                            @if($data['category_slug'] === 'investment')
                                                <div class="rounded-circle p-2 me-3"
                                                     style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                                                    <i class="bi bi-currency-rupee text-white fs-5"></i>
                                                </div>
                                            @elseif($data['category_slug'] === 'loan')
                                                <div class="rounded-circle p-2 me-3"
                                                     style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                                                    <i class="bi bi-bank text-white fs-5"></i>
                                                </div>
                                            @elseif($data['category_slug'] === 'excess')
                                                <div class="rounded-circle p-2 me-3"
                                                     style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                                                    <i class="bi bi-plus-circle text-white fs-5"></i>
                                                </div>
                                            @elseif($data['category_slug'] === 'odinterest')
                                                <div class="rounded-circle p-2 me-3"
                                                     style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);">
                                                    <i class="bi bi-percent text-white fs-5"></i>
                                                </div>
                                            @elseif($data['category_slug'] === 'selftransaction')
                                                <div class="rounded-circle p-2 me-3"
                                                     style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                                    <i class="bi bi-arrow-left-right text-white fs-5"></i>
                                                </div>
                                            @else
                                                <div class="rounded-circle p-2 me-3"
                                                     style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                                                    <i class="bi bi-cash-stack text-white fs-5"></i>
                                                </div>
                                            @endif
                                            <span class="fw-bold fs-6">{{ $data['category'] }}</span>
                                        </div>
                                    </td>

                                    {{-- Total Records --}}
                                    <td class="text-center py-3">
                                        <span class="badge rounded-pill px-3 py-2"
                                              style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                            {{ $data['total_records'] }}
                                        </span>
                                    </td>

                                    {{-- Credit --}}
                                    <td class="text-end py-3">
                                        <span class="fw-bold fs-6" style="color: #11998e;">
                                            ₹{{ number_format($data['credit'], 2) }}
                                        </span>
                                    </td>

                                    {{-- Debit --}}
                                    <td class="text-end py-3">
                                        <span class="fw-bold fs-6" style="color: #eb3349;">
                                            ₹{{ number_format($data['debit'], 2) }}
                                        </span>
                                    </td>

                                    {{-- ✅ Profit / Loss + Loan Formula Toggle --}}
                                    <td class="text-end py-3">
                                        @if($data['category_slug'] === 'loan')
                                            {{-- LOAN: Click to toggle formula box --}}
                                            @if($data['profit'] >= 0)
                                                <span class="badge bg-success mb-1">Profit</span><br>
                                            @else
                                                <span class="badge bg-danger mb-1">Loss</span><br>
                                            @endif

                                            <span class="loan-toggle-trigger"
                                                  onclick="toggleFormula('loan-formula-table')"
                                                  title="Click to view calculation breakdown">
                                                <span class="fw-bold fs-6"
                                                      style="color: {{ $data['profit'] >= 0 ? '#4facfe' : '#eb3349' }};">
                                                    ₹{{ number_format(abs($data['profit']), 2) }}
                                                </span>
                                                
                                                <!-- <i class="bi bi-info-circle-fill"
                                                   style="font-size: 0.75rem; color: #667eea;"></i>
                                                <span class="toggle-hint">Click to view</span> -->
                                            </span>

                                            {{-- Formula Box — hidden by default --}}
                                            <div id="loan-formula-table" class="loan-formula-box d-none">
                                                <div class="formula-title">
                                                    <i class="bi bi-calculator me-1"></i> Calculation Breakdown
                                                </div>
                                                <div class="formula-row">
                                                    <span class="formula-label">
                                                        <i class="bi bi-arrow-up-circle text-success me-1"></i>
                                                        Received (CR − DR)
                                                    </span>
                                                    <span class="formula-value-green">
                                                        ₹{{ number_format($data['loan_received'], 2) }}
                                                    </span>
                                                </div>
                                                <div class="formula-row">
                                                    <span class="formula-label">
                                                        <i class="bi bi-percent text-danger me-1"></i>
                                                        Interest (CR × % × Days)
                                                    </span>
                                                    <span class="formula-value-red">
                                                        − ₹{{ number_format($data['loan_interest'], 2) }}
                                                    </span>
                                                </div>
                                                <div class="formula-divider"></div>
                                                <div class="formula-net">
                                                    <span style="color: #764ba2;">
                                                        <i class="bi bi-flag-fill me-1"></i>Net Result
                                                    </span>
                                                    <span class="{{ $data['profit'] >= 0 ? 'text-success' : 'text-danger' }}">
                                                        {{ $data['profit'] < 0 ? '− ' : '' }}
                                                        ₹{{ number_format(abs($data['profit']), 2) }}
                                                    </span>
                                                </div>
                                            </div>

                                        @else
                                            {{-- Other categories: normal display --}}
                                            @if($data['profit'] >= 0)
                                                <span class="badge bg-success mb-1">Profit</span><br>
                                                <span class="fw-bold fs-6" style="color: #4facfe;">
                                                    ₹{{ number_format($data['profit'], 2) }}
                                                </span>
                                            @else
                                                <span class="badge bg-danger mb-1">Loss</span><br>
                                                <span class="fw-bold fs-6" style="color: #eb3349;">
                                                    ₹{{ number_format(abs($data['profit']), 2) }}
                                                </span>
                                            @endif
                                        @endif
                                    </td>

                                    {{-- Balance --}}
                                    <td class="text-end py-3">
                                        <span class="fw-bold fs-6"
                                              style="color: {{ $data['balance'] >= 0 ? '#43e97b' : '#fa709a' }};">
                                            ₹{{ number_format($data['balance'], 2) }}
                                        </span>
                                    </td>

                                    {{-- Action --}}
                                    <td class="text-center pe-4 py-3">
                                        <a href="{{ route('report.category.balance', $data['category_slug']) }}"
                                           class="btn btn-sm px-3 shadow-sm text-white"
                                           style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                                            <i class="bi bi-eye me-1"></i>View Report
                                        </a>
                                    </td>

                                </tr>
                                @endforeach
                            </tbody>

                            {{-- Grand Total Footer --}}
                            <tfoot style="background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);">
                                <tr class="fw-bold">
                                    <td class="ps-4 py-3 fs-5">GRAND TOTAL</td>
                                    <td class="text-center py-3">
                                        <span class="badge rounded-pill px-3 py-2 bg-dark fs-6">
                                            {{ collect($dashboardData)->sum('total_records') }}
                                        </span>
                                    </td>
                                    <td class="text-end py-3 fs-5" style="color: #11998e;">
                                        ₹{{ number_format($grandTotalCredit, 2) }}
                                    </td>
                                    <td class="text-end py-3 fs-5" style="color: #eb3349;">
                                        ₹{{ number_format($grandTotalDebit, 2) }}
                                    </td>
                                    <td class="text-end py-3">
                                        @if($grandTotalProfit >= 0)
                                            <span class="badge bg-success mb-1">Profit</span><br>
                                            <span class="fs-5 fw-bold" style="color: #4facfe;">
                                                ₹{{ number_format($grandTotalProfit, 2) }}
                                            </span>
                                        @else
                                            <span class="badge bg-danger mb-1">Loss</span><br>
                                            <span class="fs-5 fw-bold" style="color: #eb3349;">
                                                ₹{{ number_format(abs($grandTotalProfit), 2) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-end py-3 fs-5"
                                        style="color: {{ $grandTotalBalance >= 0 ? '#43e97b' : '#fa709a' }};">
                                        ₹{{ number_format($grandTotalBalance, 2) }}
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== VISUAL COMPARISON CARDS ===== --}}
    <div class="row g-4 mb-4">
        @foreach($dashboardData as $index => $data)
        @php
            $gradients = [
                'investment'      => 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
                'loan'            => 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
                'excess'          => 'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)',
                'odinterest'      => 'linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%)',
                'expanse'         => 'linear-gradient(135deg, #fa709a 0%, #fee140 100%)',
                'selftransaction' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
            ];
            $cardGradient = $gradients[$data['category_slug']] ?? 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
        @endphp
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-lg h-100">

                <div class="card-header text-white border-0 py-3"
                     style="background: {{ $cardGradient }};">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold">{{ $data['category'] }}</h6>
                        <span class="badge bg-white text-dark px-3 py-2">
                            {{ $data['total_records'] }} Records
                        </span>
                    </div>
                </div>

                <div class="card-body bg-light">

                    {{-- Credit Progress --}}
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <small class="fw-semibold text-secondary">Credit</small>
                            <small class="fw-bold" style="color: #11998e;">
                                ₹{{ number_format($data['credit'], 0) }}
                            </small>
                        </div>
                        <div class="progress" style="height: 10px; border-radius: 10px;">
                            <div class="progress-bar"
                                 style="background: linear-gradient(90deg, #11998e 0%, #38ef7d 100%);
                                 width: {{ $grandTotalCredit > 0 ? ($data['credit']/$grandTotalCredit)*100 : 0 }}%;">
                            </div>
                        </div>
                    </div>

                    {{-- Debit Progress --}}
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <small class="fw-semibold text-secondary">Debit</small>
                            <small class="fw-bold" style="color: #eb3349;">
                                ₹{{ number_format($data['debit'], 0) }}
                            </small>
                        </div>
                        <div class="progress" style="height: 10px; border-radius: 10px;">
                            <div class="progress-bar"
                                 style="background: linear-gradient(90deg, #eb3349 0%, #f45c43 100%);
                                 width: {{ $grandTotalDebit > 0 ? ($data['debit']/$grandTotalDebit)*100 : 0 }}%;">
                            </div>
                        </div>
                    </div>

                    {{-- ✅ Profit / Loss Progress --}}
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <small class="fw-semibold text-secondary">
                                {{ $data['profit'] >= 0 ? 'Profit' : 'Loss' }}
                            </small>
                            <small class="fw-bold">
                                @if($data['category_slug'] === 'loan')
                                    {{-- LOAN CARD: Click to toggle formula --}}
                                    @if($data['profit'] >= 0)
                                        <span class="badge bg-success" style="font-size:10px;">Profit</span>
                                    @else
                                        <span class="badge bg-danger" style="font-size:10px;">Loss</span>
                                    @endif
                                    <span class="loan-toggle-trigger ms-1"
                                          onclick="toggleFormula('loan-formula-card')"
                                          title="Click to view calculation breakdown">
                                        <span style="color: {{ $data['profit'] >= 0 ? '#4facfe' : '#eb3349' }};">
                                            ₹{{ number_format(abs($data['profit']), 0) }}
                                        </span>
                                        <i class="bi bi-info-circle-fill"
                                           style="font-size: 0.7rem; color: #667eea;"></i>
                                    </span>
                                @else
                                    @if($data['profit'] >= 0)
                                        <span class="badge bg-success" style="font-size:10px;">Profit</span>
                                        <span style="color:#4facfe;">
                                            ₹{{ number_format($data['profit'], 0) }}
                                        </span>
                                    @else
                                        <span class="badge bg-danger" style="font-size:10px;">Loss</span>
                                        <span style="color:#eb3349;">
                                            ₹{{ number_format(abs($data['profit']), 0) }}
                                        </span>
                                    @endif
                                @endif
                            </small>
                        </div>
                        <div class="progress" style="height: 10px; border-radius: 10px;">
                            <div class="progress-bar"
                                 style="background: {{ $data['profit'] >= 0
                                    ? 'linear-gradient(90deg, #4facfe 0%, #00f2fe 100%)'
                                    : 'linear-gradient(90deg, #eb3349 0%, #f45c43 100%)' }};
                                 width: {{ abs($data['profit']) > 0 && abs($grandTotalProfit) > 0
                                    ? (abs($data['profit'])/abs($grandTotalProfit))*100 : 0 }}%;">
                            </div>
                        </div>

                        {{-- ✅ Loan Formula Box in Card — hidden by default, toggle on click --}}
                        @if($data['category_slug'] === 'loan')
                        <div id="loan-formula-card" class="loan-formula-box mt-2 d-none">
                            <div class="formula-title">
                                <i class="bi bi-calculator me-1"></i> Calculation Breakdown
                            </div>
                            <div class="formula-row">
                                <span class="formula-label">
                                    <i class="bi bi-arrow-up-circle text-success me-1"></i>
                                    Received (CR − DR)
                                </span>
                                <span class="formula-value-green">
                                    ₹{{ number_format($data['loan_received'], 2) }}
                                </span>
                            </div>
                            <div class="formula-row">
                                <span class="formula-label">
                                    <i class="bi bi-percent text-danger me-1"></i>
                                    Interest (CR × % × Days)
                                </span>
                                <span class="formula-value-red">
                                    − ₹{{ number_format($data['loan_interest'], 2) }}
                                </span>
                            </div>
                            <div class="formula-divider"></div>
                            <div class="formula-net">
                                <span style="color: #764ba2;">
                                    <i class="bi bi-flag-fill me-1"></i>Net Result
                                </span>
                                <span class="{{ $data['profit'] >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $data['profit'] < 0 ? '− ' : '' }}
                                    ₹{{ number_format(abs($data['profit']), 2) }}
                                </span>
                            </div>
                        </div>
                        @endif
                    </div>

                    {{-- Loan Pending Interest Link --}}
                    @if($data['category_slug'] === 'loan' && isset($pendingLoanCount) && $pendingLoanCount > 0)
                    <div class="mb-3 p-2 rounded-3 d-flex align-items-center justify-content-between"
                         style="background: linear-gradient(135deg, #1a1a2e22, #0f346022);">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-clock-history text-primary"></i>
                            <small class="fw-semibold text-primary">Pending Interest</small>
                        </div>
                        <a href="{{ route('loan.interest.list') }}"
                           class="badge text-white text-decoration-none px-2 py-1"
                           style="background: linear-gradient(135deg, #1a1a2e 0%, #0f3460 100%);">
                            {{ $pendingLoanCount }} Pending →
                        </a>
                    </div>
                    @endif

                    {{-- Balance --}}
                    <div class="pt-3 border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-semibold text-secondary">Balance</span>
                            <span class="fs-4 fw-bold"
                                  style="color: {{ $data['balance'] >= 0 ? '#43e97b' : '#fa709a' }};">
                                ₹{{ number_format($data['balance'], 2) }}
                            </span>
                        </div>
                    </div>

                    {{-- View Button --}}
                    <div class="mt-3">
                        <a href="{{ route('report.category.balance', $data['category_slug']) }}"
                           class="btn btn-sm w-100 text-white shadow-sm py-2"
                           style="background: {{ $cardGradient }}; border: none;">
                            <i class="bi bi-box-arrow-up-right me-1"></i>View Full Details
                        </a>
                    </div>

                </div>
            </div>
        </div>
        @endforeach
    </div>

</div>

<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

{{-- ✅ Toggle Script --}}
<script>
    function toggleFormula(id) {
        const el = document.getElementById(id);
        if (el) {
            el.classList.toggle('d-none');
        }
    }
</script>

@endsection