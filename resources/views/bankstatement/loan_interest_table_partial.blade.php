{{-- resources/views/bankstatement/partials/loan_interest_table.blade.php --}}

@if(count($data) === 0)
    <div class="alert alert-info mt-3">No records found.</div>
@else
<div class="table-responsive mt-2">
    <table class="table table-bordered table-hover table-sm align-middle">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Loan ID</th>
                <th>Ref No</th>
                <th>Bank Name</th>
                <th>Staff / Customer</th>
                <th class="text-end">Debit Amt</th>
                <th class="text-end">Credit Amt</th>
                <th class="text-center">Days</th>
                <th class="text-center">Interest %</th>
                <th class="text-end">Interest Amt</th>
                <th class="text-end">Total (D+I)</th>
                <th class="text-end">Simple Balance<br><small>(C - D)</small></th>
                <th class="text-end">Balance<br><small>(C - Total)</small></th>
                <th class="text-center">Status</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $i => $row)
            @php
                $loan   = $row['loan'];
                $status = $row['status'];
                $rowClass = match($status) {
                    'accepted' => 'table-success',
                    'rejected' => 'table-danger',
                    default    => '',
                };
            @endphp
            <tr class="{{ $rowClass }}">
                <td>{{ $i + 1 }}</td>
                <td>{{ $loan->id }}</td>
                <td>
                    @if($row['conform_ref_no'])
                        <a href="{{ route('bankstatement.view', $row['conform_ref_no']) }}" class="text-decoration-none">
                            {{ $row['conform_ref_no'] }}
                        </a>
                    @else
                        <span class="text-muted">N/A</span>
                    @endif
                </td>
                <td>{{ $loan->bank_name ?? '-' }}</td>
                <td>
                    <span class="d-block">{{ $loan->staff_name ?? '-' }}</span>
                    <small class="text-muted">{{ $loan->customer_name ?? '' }}</small>
                </td>
                <td class="text-end text-danger">₹{{ number_format($row['debit_total'], 2) }}</td>
                <td class="text-end text-success">₹{{ number_format($row['credit_total'], 2) }}</td>
                <td class="text-center">{{ $row['days_diff'] }}</td>
                <td class="text-center">{{ $row['interest_percent'] }}%</td>
                <td class="text-end">₹{{ number_format($row['total_interest'], 2) }}</td>
                <td class="text-end">₹{{ number_format($row['total_amount'], 2) }}</td>

                {{-- Simple Balance: Credit - Debit --}}
                <td class="text-end fw-bold {{ $row['simple_balance'] >= 0 ? 'text-success' : 'text-danger' }}">
                    ₹{{ number_format($row['simple_balance'], 2) }}
                </td>

                {{-- Balance: Credit - (Debit + Interest) --}}
                @if($status === 'accepted')
                    <td class="text-end fw-bold text-success">
                        ₹{{ number_format($loan->balance_amount > 0 ? $loan->balance_amount : $row['balance_amount'], 2) }}
                        <br><small class="badge bg-success">Cleared</small>
                    </td>
                @else
                    <td class="text-end fw-bold {{ $row['balance_amount'] >= 0 ? 'text-success' : 'text-danger' }}">
                        ₹{{ number_format($row['balance_amount'], 2) }}
                        @if($status === 'pending')
                            <br><small class="badge bg-warning text-dark">Pending</small>
                        @endif
                    </td>
                @endif

                {{-- Status Badge --}}
                <td class="text-center">
                    @if($status === 'accepted')
                        <span class="badge bg-success">
                            <i class="bi bi-check-circle-fill"></i> Accepted
                        </span>
                        @if($loan->interest_updated_at)
                            <br><small class="text-muted">{{ \Carbon\Carbon::parse($loan->interest_updated_at)->format('d-m-Y') }}</small>
                        @endif
                    @elseif($status === 'rejected')
                        <span class="badge bg-danger">
                            <i class="bi bi-x-circle-fill"></i> Rejected
                        </span>
                        @if($loan->interest_updated_at)
                            <br><small class="text-muted">{{ \Carbon\Carbon::parse($loan->interest_updated_at)->format('d-m-Y') }}</small>
                        @endif
                    @else
                        <span class="badge bg-warning text-dark">
                            <i class="bi bi-clock-fill"></i> Pending
                        </span>
                    @endif
                </td>

                {{-- Action --}}
                <td class="text-center">
                    <a href="{{ route('loan.interest.status', $loan->id) }}"
                       class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-eye"></i> View
                    </a>

                    @if($status === 'pending')
                    <div class="mt-1 d-flex gap-1 justify-content-center">
                        <form method="POST" action="{{ route('loan.interest.accept', $loan->id) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="interest_amount" value="{{ $row['total_interest'] }}">
                            <input type="hidden" name="balance_amount"  value="{{ $row['balance_amount'] }}">
                            <input type="hidden" name="debit_amount"    value="{{ $row['debit_total'] }}">
                            <input type="hidden" name="credit_amount"   value="{{ $row['credit_total'] }}">
                            <button type="submit" class="btn btn-sm btn-success"
                                    onclick="return confirm('Accept interest for Loan #{{ $loan->id }}?')">
                                <i class="bi bi-check-lg"></i>
                            </button>
                        </form>
                        <form method="POST" action="{{ route('loan.interest.reject', $loan->id) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Reject interest for Loan #{{ $loan->id }}?')">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </form>
                    </div>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>

        {{-- Totals Footer --}}
        @php
            $totalDebit    = collect($data)->sum('debit_total');
            $totalCredit   = collect($data)->sum('credit_total');
            $totalInterest = collect($data)->sum('total_interest');
            $totalBalance  = collect($data)->sum('balance_amount');
            $totalSimple   = collect($data)->sum('simple_balance');
        @endphp
        <tfoot class="table-dark fw-bold">
            <tr>
                <td colspan="5" class="text-end">Totals:</td>
                <td class="text-end">₹{{ number_format($totalDebit, 2) }}</td>
                <td class="text-end">₹{{ number_format($totalCredit, 2) }}</td>
                <td colspan="2"></td>
                <td class="text-end">₹{{ number_format($totalInterest, 2) }}</td>
                <td></td>
                <td class="text-end {{ $totalSimple >= 0 ? 'text-success' : 'text-danger' }}">
                    ₹{{ number_format($totalSimple, 2) }}
                </td>
                <td class="text-end {{ $totalBalance >= 0 ? 'text-success' : 'text-danger' }}">
                    ₹{{ number_format($totalBalance, 2) }}
                </td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>
</div>
@endif
