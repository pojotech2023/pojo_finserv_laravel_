@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=Syne:wght@600;700;800&display=swap" rel="stylesheet">

<style>
    :root {
        --accent: #4361ee; --accent2: #7209b7;
        --accent-light: #eef1fd;
        --success: #10b981; --warning: #f59e0b; --danger: #e63946;
        --text: #1e293b; --muted: #64748b;
        --border: #e2e8f0; --surface: #ffffff; --surface2: #f8faff;
        --font-display: 'Syne',sans-serif; --font-body: 'DM Sans',sans-serif;
    }
    body { background:#f0f4ff !important; font-family:var(--font-body); color:var(--text); }
    .page-wrapper { padding:1.75rem 2rem; max-width:1400px; margin:0 auto; }
    .page-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:1.75rem; padding-bottom:1.25rem; border-bottom:2px solid var(--border); }
    .page-title { font-family:var(--font-display); font-size:1.65rem; font-weight:800; background:linear-gradient(135deg,var(--accent),var(--accent2)); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; margin:0; }
    .page-subtitle { color:var(--muted); font-size:.82rem; margin-top:3px; }

    .stats-row { display:grid; grid-template-columns:repeat(3,1fr); gap:1rem; margin-bottom:1.5rem; }
    .stat-card { background:var(--surface); border:1px solid var(--border); border-radius:12px; padding:1.1rem 1.25rem; text-align:center; }
    .stat-card .lbl { font-size:.75rem; color:var(--muted); font-weight:600; text-transform:uppercase; letter-spacing:.06em; margin-bottom:.4rem; }
    .stat-card .val { font-size:1.6rem; font-weight:700; }
    .stat-card .val.warn { color:var(--warning); }

    .filter-bar { display:flex; gap:.75rem; flex-wrap:wrap; align-items:center; background:var(--surface2); padding:1rem 1.25rem; border-radius:12px; border:1px solid var(--border); margin-bottom:1.5rem; }
    .filter-bar .form-control { background:#fff !important; border:1px solid var(--border) !important; border-radius:8px !important; font-size:.84rem; padding:.45rem .9rem; height:auto; }
    .filter-bar .form-control:focus { border-color:var(--accent) !important; box-shadow:0 0 0 3px rgba(67,97,238,.1) !important; outline:none; }

    .inv-card { background:var(--surface); border:1px solid var(--border); border-radius:16px; overflow:hidden; margin-bottom:1.25rem; box-shadow:0 2px 8px rgba(67,97,238,.05); }
    .inv-card-header { padding:1rem 1.25rem; display:flex; align-items:center; justify-content:space-between; border-bottom:1px solid var(--border); background:var(--surface2); }
    .ref-no { font-family:var(--font-display); font-size:1rem; font-weight:700; color:var(--accent); }
    .inv-name { font-size:.84rem; color:var(--text); font-weight:500; margin-top:2px; }
    .inv-meta { font-size:.75rem; color:var(--muted); margin-top:2px; }

    .status-badge { display:inline-flex; align-items:center; gap:.3rem; padding:.28rem .75rem; border-radius:6px; font-size:.75rem; font-weight:700; }
    .status-badge.partial { background:#fffbeb; color:#92400e; border:1px solid #fde68a; }
    .status-badge.no-return { background:#fff1f2; color:#9f1239; border:1px solid #fecdd3; }
    .status-badge.interest-pending { background:#eff6ff; color:#1e40af; border:1px solid #bfdbfe; }

    .inv-body { padding:1.25rem 1.5rem; }

    .progress-row { margin-bottom:1rem; }
    .progress-label { display:flex; justify-content:space-between; font-size:.78rem; color:var(--muted); margin-bottom:.35rem; font-weight:500; }
    .progress-bar { height:7px; background:#f1f5f9; border-radius:4px; overflow:hidden; }
    .progress-fill { height:100%; border-radius:4px; transition:width .4s; }
    .progress-fill.warn { background:var(--warning); }
    .progress-fill.success { background:var(--success); }
    .progress-fill.danger { background:var(--danger); }

    .summary-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:.75rem; margin:1rem 0; padding:1rem; background:var(--surface2); border-radius:10px; border:1px solid var(--border); }
    .sum-item .lbl { font-size:.7rem; color:var(--muted); font-weight:600; text-transform:uppercase; letter-spacing:.05em; }
    .sum-item .val { font-size:.95rem; font-weight:700; margin-top:.2rem; }
    .sum-item .val.need { color:var(--warning); }
    .sum-item .val.ok { color:var(--success); }
    .sum-item .val.info { color:var(--accent); }

    .txn-table { width:100%; border-collapse:separate; border-spacing:0; margin-top:.75rem; font-size:.82rem; }
    .txn-table th { background:var(--surface2); color:var(--muted); font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:.07em; padding:.6rem .85rem; border-bottom:1px solid var(--border); }
    .txn-table td { padding:.6rem .85rem; border-bottom:1px solid #f1f5f9; vertical-align:middle; }
    .txn-table tr:last-child td { border-bottom:none; }
    .badge-cr { background:#ecfdf5; color:#059669; border:1px solid #a7f3d0; border-radius:4px; padding:.18rem .5rem; font-size:.69rem; font-weight:700; }
    .badge-dr { background:#fff1f2; color:var(--danger); border:1px solid #fecdd3; border-radius:4px; padding:.18rem .5rem; font-size:.69rem; font-weight:700; }

    .card-actions { display:flex; gap:.6rem; padding-top:.75rem; border-top:1px solid var(--border); margin-top:.75rem; }
    .btn-ghost { background:#fff; border:1px solid var(--border); color:var(--muted); border-radius:8px; padding:.4rem 1rem; font-size:.8rem; font-weight:500; text-decoration:none; display:inline-flex; align-items:center; gap:.35rem; cursor:pointer; transition:all .15s; }
    .btn-ghost:hover { border-color:var(--accent); color:var(--accent); background:var(--accent-light); }
    .btn-primary-sm { background:var(--accent); color:#fff; border:none; border-radius:8px; padding:.4rem 1rem; font-size:.8rem; font-weight:600; text-decoration:none; display:inline-flex; align-items:center; gap:.35rem; cursor:pointer; transition:all .15s; }
    .btn-primary-sm:hover { background:#3451d1; color:#fff; }

    .empty-state { text-align:center; padding:4rem 1rem; color:var(--muted); }
    .empty-state svg { opacity:.3; margin-bottom:1rem; }
    .empty-state p { font-size:.88rem; }

    @media(max-width:768px) {
        .stats-row { grid-template-columns:1fr; }
        .summary-grid { grid-template-columns:1fr 1fr; }
    }
</style>

<div class="page-wrapper">

    <div class="page-header">
        <div>
            <h1 class="page-title">Open Investments</h1>
            <p class="page-subtitle">Pending — not yet fully categorized</p>
        </div>
        <a href="{{ route('investment.list') }}" class="btn-ghost">
            ← All Investments
        </a>
    </div>

    {{-- Stats Row --}}
    <div class="stats-row">
        <div class="stat-card">
            <div class="lbl">Total open</div>
            <div class="val warn">{{ $totalOpen }}</div>
        </div>
        <div class="stat-card">
            <div class="lbl">Total invested</div>
            <div class="val">₹{{ number_format($totalInvested, 2) }}</div>
        </div>
        <div class="stat-card">
            <div class="lbl">Still pending</div>
            <div class="val warn">₹{{ number_format($totalStillPending, 2) }}</div>
        </div>
    </div>

    {{-- Filter --}}
    <form method="GET" action="{{ route('investment.open') }}" class="filter-bar">
        <input type="text" name="name" class="form-control" placeholder="Investor name…"
               value="{{ request('name') }}" style="max-width:200px;">
        <input type="text" name="ref_no" class="form-control" placeholder="Ref No…"
               value="{{ request('ref_no') }}" style="max-width:160px;">
        <select name="status" class="form-control" style="max-width:180px;">
            <option value="">All statuses</option>
            <option value="no_return"       {{ request('status')==='no_return'       ? 'selected' : '' }}>No return yet</option>
            <option value="partial_return"  {{ request('status')==='partial_return'  ? 'selected' : '' }}>Partial return</option>
            <option value="interest_pending"{{ request('status')==='interest_pending'? 'selected' : '' }}>Interest pending</option>
        </select>
        <button type="submit" class="btn-primary-sm">Filter</button>
        <a href="{{ route('investment.open') }}" class="btn-ghost">Clear</a>
    </form>

    @forelse($openInvestments as $row)
        @php
            $pct = $row['total_credit'] > 0
                ? min(100, ($row['total_debit'] / $row['total_credit']) * 100)
                : 0;
            $fillClass = $pct >= 100 ? 'success' : ($pct > 50 ? 'warn' : 'danger');
            $statusLabel = match($row['status']) {
                'no_return'        => ['cls' => 'no-return',        'txt' => 'No return yet'],
                'interest_pending' => ['cls' => 'interest-pending', 'txt' => 'Interest pending'],
                default            => ['cls' => 'partial',          'txt' => 'Partial return'],
            };
        @endphp

        <div class="inv-card">
            <div class="inv-card-header">
                <div>
                    <div class="ref-no">{{ $row['ref_no'] }}</div>
                    <div class="inv-name">{{ $row['investor']->name }}</div>
                    <div class="inv-meta">
                        Invested {{ $row['cr_date'] ? \Carbon\Carbon::parse($row['cr_date'])->format('d-M-Y') : '—' }}
                        &middot; {{ $row['percentage'] }}% / day
                    </div>
                </div>
                <span class="status-badge {{ $statusLabel['cls'] }}">
                    {{ $statusLabel['txt'] }}
                </span>
            </div>

            <div class="inv-body">
                {{-- Progress Bar --}}
                <div class="progress-row">
                    <div class="progress-label">
                        <span>Returned so far</span>
                        <span>₹{{ number_format($row['total_debit'], 2) }}
                              of ₹{{ number_format($row['total_credit'], 2) }}</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill {{ $fillClass }}"
                             style="width:{{ $pct }}%"></div>
                    </div>
                </div>

                {{-- Summary Grid --}}
                <div class="summary-grid">
                    <div class="sum-item">
                        <div class="lbl">Invested (CR)</div>
                        <div class="val info">₹{{ number_format($row['total_credit'], 2) }}</div>
                    </div>
                    <div class="sum-item">
                        <div class="lbl">Returned (DR)</div>
                        <div class="val">₹{{ number_format($row['total_debit'], 2) }}</div>
                    </div>
                    <div class="sum-item">
                        <div class="lbl">Interest ({{ $row['no_of_days'] }} days)</div>
                        <div class="val">₹{{ number_format($row['total_interest'], 2) }}</div>
                    </div>
                    <div class="sum-item">
                        <div class="lbl">Still needed</div>
                        <div class="val need">₹{{ number_format($row['still_needed'], 2) }}</div>
                    </div>
                </div>

                {{-- Transaction Table --}}
                <table class="txn-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>UTR No</th>
                            <th>Name</th>
                            <th style="text-align:right">Amount</th>
                            <th style="text-align:center">CR/DR</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($row['all_statements'] as $stmt)
                        <tr>
                            <td style="white-space:nowrap">
                                {{ \Carbon\Carbon::parse($stmt->date)->format('d-M-Y') }}
                            </td>
                            <td style="font-family:monospace;font-size:.77rem;color:var(--muted)">
                                {{ $stmt->utrno ?? '—' }}
                            </td>
                            <td>{{ $stmt->name }}</td>
                            <td style="text-align:right;font-weight:600">
                                ₹{{ number_format($stmt->amount, 2) }}
                            </td>
                            <td style="text-align:center">
                                @if(in_array($stmt->cr_dr, ['CR','Credit']))
                                    <span class="badge-cr">CR</span>
                                @else
                                    <span class="badge-dr">DR</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="card-actions">
    <a href="{{ route('bankstatement.view', $row['ref_no']) }}"
       class="btn-primary-sm">
        View full details
    </a>
    <a href="{{ route('bankstatement.uncategorizeInvestment', $row['ref_no']) }}"
       class="btn-ghost"
       onclick="return confirm('Uncategorize this open investment ({{ $row['ref_no'] }}) and move it to the Uncategorized page?')"
       style="background:#fef2f2; color:#b91c1c; border-color:#fecaca; font-weight:600;">
        ✓ unCategorize Open Investment
    </a>
    <a href="{{ route('bank.statement') }}"
       class="btn-ghost">
        Add transaction
    </a>
    
   @php
    $firstCrStmt = collect($row['all_statements'])
        ->first(fn($s) => in_array($s->cr_dr, ['CR','Credit']))
        ?? collect($row['all_statements'])->first();
@endphp
@if($firstCrStmt)
<a href="{{ url('bank-statement/set-action/' . $firstCrStmt->id . '?action_type=INVESTMENT') }}"
   class="btn-ghost">
    ✏️ Edit
</a>
@endif
</div>
            </div>
        </div>
    @empty
        <div class="inv-card">
            <div class="empty-state">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p>No open investments found. All investments are categorized!</p>
            </div>
        </div>
    @endforelse

</div>
@endsection