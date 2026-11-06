{{-- bankstatement/partials/loan_interest_table.blade.php --}}
{{-- ✅ Pagination support added --}}

@php
    $perPage     = 10;
    $currentPage = (int) request('page_' . ($tabName ?? 'all'), 1);
    $collection  = collect($data);
    $total       = $collection->count();
    $sliced      = $collection->forPage($currentPage, $perPage)->values();
    $lastPage    = (int) ceil($total / $perPage);
    if ($lastPage < 1) $lastPage = 1;

    // Build URL helper (preserve tab anchor + other query params)
    $baseQuery = request()->except('page_' . ($tabName ?? 'all'));
@endphp

@if($total === 0)
    <div class="alert alert-info mt-3">No records found.</div>
@else

<div class="table-responsive mt-3">
    <table class="table table-bordered table-hover table-sm align-middle">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Ref No</th>
                <th>Bank</th>
                <th>Staff</th>
                <th>Customer</th>
                <th class="text-end">Debit Amt</th>
                <th class="text-center">Days</th>
                <th class="text-center">Interest %</th>
                <th class="text-end">Interest Amt</th>
                <th class="text-end">Total (D+I)</th>
                <th class="text-end">Simple Balance<br><small class="fw-normal">(C - D)</small></th>
                <th class="text-end">Balance<br><small class="fw-normal">(C - Total)</small></th>
                <th class="text-center">Status</th>
                <th class="text-center">Reason</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sliced as $i => $row)
            @php
                $loan          = $row['loan'];
                $status        = $row['status'] ?? 'pending';
                $balanceAmt    = $row['balance_amount'] ?? 0;
                $simpleBalance = $row['simple_balance'] ?? 0;
                $rowBg         = match(true) {
                    $status === 'accepted'                    => 'table-success',
                    $status === 'rejected'                    => 'table-danger',
                    $balanceAmt < 0                           => 'table-warning',
                    default                                   => '',
                };
               $badgeColor    = match(true) {
    $status === 'accepted'          => 'success',
    $status === 'rejected'          => 'danger',
    $balanceAmt >= 0                => 'success',   // balance 0 or + = paid
    default                         => 'warning',
};
$displayStatus = match(true) {
    $status === 'accepted'          => 'ACCEPTED',
    $status === 'rejected'          => 'REJECTED',
    $balanceAmt >= 0                => 'PAID',
    default                         => 'PENDING',
};
                $absoluteIndex = (($currentPage - 1) * $perPage) + $i + 1;
            @endphp
            <tr class="{{ $rowBg }}">
                <td>{{ $absoluteIndex }}</td>

                {{-- Ref No --}}
                <td>
                    @if($row['conform_ref_no'])
                        <a href="{{ route('loan.interest.status', $loan->id) }}" class="fw-semibold text-decoration-none">
                            {{ $row['conform_ref_no'] }}
                        </a>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>

                <td>{{ $loan->bank_name ?? '-' }}</td>
                <td>{{ $loan->staff->staff_name ?? $loan->staff_name ?? '-' }}</td>
                <td>{{ $loan->customer_name ?? '-' }}</td>

                {{-- Debit --}}
                <td class="text-end text-danger fw-semibold">
                    ₹{{ number_format($row['debit_total'], 2) }}
                </td>

                <td class="text-center">{{ $row['days_diff'] }}</td>
                <td class="text-center">{{ $row['interest_percent'] }}%</td>

                {{-- Interest Amount --}}
                <td class="text-end">₹{{ number_format($row['total_interest'], 2) }}</td>

                {{-- Total D+I --}}
                <td class="text-end">₹{{ number_format($row['total_amount'], 2) }}</td>

                {{-- Simple Balance --}}
                <td class="text-end fw-semibold {{ $simpleBalance >= 0 ? 'text-success' : 'text-danger' }}">
                    ₹{{ number_format($simpleBalance, 2) }}
                </td>

                {{-- Balance (C - Total) --}}
                <td class="text-end fw-bold {{ $balanceAmt >= 0 ? 'text-success' : 'text-danger' }}">
                    ₹{{ number_format($balanceAmt, 2) }}
                </td>

                {{-- Status --}}
                <td class="text-center">
                    <span class="badge bg-{{ $badgeColor }} {{ ($displayStatus === 'PENDING' || $displayStatus === 'PAID') ? 'text-dark' : '' }}">
    {{ $displayStatus }}
</span>
                </td>

                {{-- ✅ Reason column --}}
                <td class="text-center">
                    @if($loan->interest_remark)
                        <button type="button" class="btn btn-sm btn-outline-secondary"
                            data-bs-toggle="popover"
                            data-bs-trigger="focus"
                            data-bs-placement="left"
                            data-bs-content="{{ $loan->interest_remark }}"
                            title="Reason">
                            <i class="bi bi-chat-text"></i>
                        </button>
                    @else
                        <span class="text-muted small">—</span>
                    @endif
                </td>

                {{-- Action --}}
                <td class="text-center" style="white-space:nowrap;">
                    <a href="{{ route('loan.interest.status', $loan->id) }}"
                       class="btn btn-sm btn-outline-primary mb-1">
                        <i class="bi bi-eye"></i> View
                    </a>

                    @if($status === 'pending' && $balanceAmt < 0)
                        {{-- ✅ Accept with reason popup --}}
                        <button type="button" class="btn btn-sm btn-success mb-1"
                            onclick="openAcceptModal(
                                {{ $loan->id }},
                                '{{ route('loan.interest.accept', $loan->id) }}',
                                '{{ $row['total_interest'] }}',
                                '{{ $balanceAmt }}',
                                '{{ $row['debit_total'] }}',
                                '{{ $row['credit_total'] }}'
                            )">
                            <i class="bi bi-check-lg"></i> Accept
                        </button>

                        {{-- ✅ Reject with reason popup --}}
                        <button type="button" class="btn btn-sm btn-danger mb-1"
                            onclick="openRejectModal(
                                {{ $loan->id }},
                                '{{ route('loan.interest.reject', $loan->id) }}'
                            )">
                            <i class="bi bi-x-lg"></i> Reject
                        </button>
                    @elseif($status === 'accepted' || $status === 'rejected')
                        <span class="badge bg-{{ $badgeColor }} {{ $status === 'pending' ? 'text-dark' : '' }} px-2 py-1">
                            <i class="bi bi-{{ $status === 'accepted' ? 'check' : 'x' }}-circle-fill me-1"></i>
                            Done
                        </span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- ✅ Pagination --}}
@if($lastPage > 1)
<nav class="mt-3 d-flex justify-content-between align-items-center">
    <small class="text-muted">
        Showing {{ (($currentPage - 1) * $perPage) + 1 }}–{{ min($currentPage * $perPage, $total) }} of {{ $total }} records
    </small>
    <ul class="pagination pagination-sm mb-0">
        {{-- Previous --}}
        <li class="page-item {{ $currentPage <= 1 ? 'disabled' : '' }}">
            <a class="page-link"
               href="{{ request()->fullUrlWithQuery(array_merge($baseQuery, ['page_' . ($tabName ?? 'all') => $currentPage - 1])) }}#{{ $tabName ?? 'all' }}">
                &laquo; Prev
            </a>
        </li>

        {{-- Page numbers --}}
        @for($p = max(1, $currentPage - 2); $p <= min($lastPage, $currentPage + 2); $p++)
        <li class="page-item {{ $p === $currentPage ? 'active' : '' }}">
            <a class="page-link"
               href="{{ request()->fullUrlWithQuery(array_merge($baseQuery, ['page_' . ($tabName ?? 'all') => $p])) }}#{{ $tabName ?? 'all' }}">
                {{ $p }}
            </a>
        </li>
        @endfor

        {{-- Next --}}
        <li class="page-item {{ $currentPage >= $lastPage ? 'disabled' : '' }}">
            <a class="page-link"
               href="{{ request()->fullUrlWithQuery(array_merge($baseQuery, ['page_' . ($tabName ?? 'all') => $currentPage + 1])) }}#{{ $tabName ?? 'all' }}">
                Next &raquo;
            </a>
        </li>
    </ul>
</nav>
@endif

@endif

{{-- ✅ Popover init --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-bs-toggle="popover"]').forEach(function (el) {
        new bootstrap.Popover(el);
    });
});
</script>