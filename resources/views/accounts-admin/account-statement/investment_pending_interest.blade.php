{{-- pending_interest.blade.php — Fixed Design --}}

@extends('layouts.app')

@section('content')

<div class="page-wrapper">

    {{-- Tabs --}}
    <ul class="nav nav-tabs mb-4" id="investmentTabs">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('investment.list') ? 'active' : '' }}"
               href="{{ route('investment.list') }}">
                All Investments
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('investment.pending.interest') ? 'active' : '' }}"
               href="{{ route('investment.pending.interest') }}"
               style="{{ request()->routeIs('investment.pending.interest') ? '' : 'color:#f59e0b;font-weight:600;' }}">
                💰 Pending Interest
                @if($pendingCount ?? 0)
                    <span class="badge bg-warning text-dark ms-1">{{ $pendingCount }}</span>
                @endif
            </a>
        </li>
    </ul>

    {{-- ══ PENDING INTEREST TABLE ══ --}}
    <div class="main-card">

        {{-- Card Header --}}
        <div style="
            background: linear-gradient(135deg, #f59e0b, #d97706);
            padding: 1rem 1.5rem;
            border-radius: 10px 10px 0 0;
            display: flex;
            flex-direction: column;
            gap: 4px;
        ">
            <h6 style="margin:0; color:#fff; font-size:1rem; font-weight:700;">
                💰 Investments with Pending Interest
            </h6>
            <span style="color:rgba(255,255,255,0.85); font-size:0.8rem;">
                Principal returned — interest not yet collected
            </span>
        </div>

        {{-- Table --}}
        <div style="overflow-x:auto; padding: 0 0 1rem 0;">
            <table style="
                width: 100%;
                border-collapse: collapse;
                font-size: 0.85rem;
            ">
                <thead>
                    <tr style="background:#fef3c7; border-bottom: 2px solid #fde68a;">
                        <th style="padding:12px 16px; text-align:left; color:#92400e; font-weight:700; white-space:nowrap;">Ref No</th>
                        <th style="padding:12px 16px; text-align:left; color:#92400e; font-weight:700; white-space:nowrap;">Investor Name</th>
                        <th style="padding:12px 16px; text-align:right; color:#92400e; font-weight:700; white-space:nowrap;">Invested Amount</th>
                        <th style="padding:12px 16px; text-align:center; color:#92400e; font-weight:700; white-space:nowrap;">Percentage</th>
                        <th style="padding:12px 16px; text-align:right; color:#92400e; font-weight:700; white-space:nowrap;">Pending Interest</th>
                        <th style="padding:12px 16px; text-align:center; color:#92400e; font-weight:700; white-space:nowrap;">Status</th>
                        <th style="padding:12px 16px; text-align:center; color:#92400e; font-weight:700; white-space:nowrap;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingInvestments as $investor)
                    <tr style="border-bottom:1px solid #f1f5f9; transition:background 0.15s;"
                        onmouseover="this.style.background='#fffbeb'"
                        onmouseout="this.style.background='transparent'">

                        {{-- Ref No --}}
                        <td style="padding:13px 16px; font-family:monospace; font-weight:600; color:#374151;">
                            {{ optional($investor->statement)->conform_ref_no ?? '—' }}
                        </td>

                        {{-- Investor Name --}}
                        <td style="padding:13px 16px; font-weight:600; color:#111827;">
                            {{ $investor->name }}
                        </td>

                        {{-- Invested Amount --}}
                        <td style="padding:13px 16px; text-align:right; font-weight:700; color:#059669; font-size:0.9rem;">
                            ₹{{ number_format($investor->statement->amount ?? 0, 2) }}
                        </td>

                        {{-- Percentage --}}
                        <td style="padding:13px 16px; text-align:center;">
                            <span style="
                                background:#fef3c7;
                                color:#92400e;
                                border:1px solid #fde68a;
                                border-radius:5px;
                                padding:3px 10px;
                                font-size:0.78rem;
                                font-weight:700;
                            ">
                                {{ $investor->percentage }}%
                            </span>
                        </td>

                        {{-- Pending Interest --}}
                        <td style="padding:13px 16px; text-align:right;">
                            <span style="
                                background:#fff1f2;
                                color:#e63946;
                                border:1px solid #fecdd3;
                                border-radius:5px;
                                padding:4px 10px;
                                font-size:0.85rem;
                                font-weight:700;
                            ">
                                ₹{{ number_format($investor->interest_amount ?? 0, 2) }}
                            </span>
                        </td>

                        {{-- Status --}}
                        <td style="padding:13px 16px; text-align:center;">
                            <span style="
                                background:#fffbeb;
                                color:#92400e;
                                border:1px solid #fde68a;
                                border-radius:5px;
                                padding:4px 10px;
                                font-size:0.75rem;
                                font-weight:700;
                                letter-spacing:0.3px;
                                white-space:nowrap;
                            ">
                                ⏳ INTEREST PENDING
                            </span>
                        </td>

                        {{-- Action --}}
                        <td style="padding:13px 16px; text-align:center;">
                            <form method="POST"
                                  action="{{ route('investment.markInterestPaid', $investor->id) }}"
                                  onsubmit="return confirm('Mark interest as received for {{ $investor->name }}?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit" style="
                                    background: linear-gradient(135deg, #10b981, #059669);
                                    color: #fff;
                                    border: none;
                                    border-radius: 6px;
                                    padding: 6px 14px;
                                    font-size: 0.8rem;
                                    font-weight: 600;
                                    cursor: pointer;
                                    white-space: nowrap;
                                    transition: opacity 0.2s;
                                "
                                onmouseover="this.style.opacity='0.85'"
                                onmouseout="this.style.opacity='1'">
                                    ✓ Mark Paid
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="
                            text-align:center;
                            padding:3rem;
                            color:#64748b;
                            font-size:0.88rem;
                        ">
                            <div style="font-size:2.5rem; margin-bottom:0.5rem;">🎉</div>
                            No pending interest — all investments are fully settled!
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>

@endsection