
@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">
        <i class="fas fa-clipboard-check"></i> Loan Approval Management
    </h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- PENDING LOANS TAB --}}
    <ul class="nav nav-tabs mb-4" id="loanTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button">
                <i class="fas fa-clock"></i> Pending Approvals 
                <span class="badge bg-warning text-dark">{{ $pendingLoans->count() }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="approved-tab" data-bs-toggle="tab" data-bs-target="#approved" type="button">
                <i class="fas fa-check-circle"></i> Approved 
                <span class="badge bg-success">{{ $approvedLoans->count() }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="rejected-tab" data-bs-toggle="tab" data-bs-target="#rejected" type="button">
                <i class="fas fa-times-circle"></i> Rejected 
                <span class="badge bg-danger">{{ $rejectedLoans->count() }}</span>
            </button>
        </li>
    </ul>

    <div class="tab-content" id="loanTabsContent">
        
        {{-- PENDING LOANS --}}
        <div class="tab-pane fade show active" id="pending" role="tabpanel">
            @if($pendingLoans->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-warning">
                            <tr>
                                <th>ID</th>
                                <th>Ref No</th>
                                <th>Bank Name</th>
                                <th>Customer</th>
                                <th>Staff</th>
                                <th>Changes</th>
                                <th>Amount</th>
                                <th>Submitted</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingLoans as $loan)
                            <tr>
                                <td>{{ $loan->id }}</td>
                                <td>
                                    <strong class="text-primary">{{ $loan->ref_no }}</strong>
                                </td>
                                <td>{{ $loan->bank_name }}</td>
                                <td>{{ $loan->customer_name }}</td>
                                <td>{{ $loan->staff_name }}</td>
                                <td>
                                    @if($loan->old_percentage || $loan->old_interest_amount)
                                        <span class="badge bg-info">Edited</span>
                                        <br>
                                        <small>
                                            @if($loan->old_percentage)
                                                %: {{ $loan->old_percentage }}% → {{ $loan->percentage }}%<br>
                                            @endif
                                            @if($loan->old_interest_amount)
                                                ₹: {{ number_format((float)$loan->old_interest_amount, 2) }} → {{ number_format((float)$loan->interest_amount, 2) }}
                                            @endif
                                        </small>
                                    @else
                                        <span class="badge bg-secondary">New</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    ₹{{ number_format((float)($loan->statement->amount ?? 0), 2) }}
                                </td>
                                <td>
                                    <small>{{ $loan->approved_at ? \Carbon\Carbon::parse($loan->approved_at)->format('d-m-Y h:i A') : '-' }}
</small><br>
                                    
                                </td>
                                <td>
                                   <a href="{{ route('admin.loan.kvb.detail', $loan->id) }}" class="btn btn-sm btn-primary">
    View
</a>

                                    <br>
                                    <form action="{{ route('admin.loan.kvb.approve', $loan->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success mb-1" onclick="return confirm('Approve this loan?')">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-sm btn-danger mb-1" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $loan->id }}">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                </td>
                            </tr>

                            {{-- Reject Modal for each loan --}}
                            <div class="modal fade" id="rejectModal{{ $loan->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.loan.kvb.reject', $loan->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title">Reject Loan #{{ $loan->id }}</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>Ref No:</strong> {{ $loan->ref_no }}</p>
                                                <p><strong>Customer:</strong> {{ $loan->customer_name }}</p>
                                                
                                                <div class="mb-3">
                                                    <label class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                                                    <textarea name="rejection_reason" class="form-control" rows="3" required 
                                                              placeholder="Enter detailed reason for rejection..."></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="fas fa-times"></i> Confirm Rejection
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> No pending loan approvals at this time.
                </div>
            @endif
        </div>

        {{-- APPROVED LOANS --}}
        <div class="tab-pane fade" id="approved" role="tabpanel">
            @if($approvedLoans->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-success">
                            <tr>
                                <th>ID</th>
                                <th>Ref No</th>
                                <th>Bank Name</th>
                                <th>Customer</th>
                                <th>Percentage</th>
                                <th>Interest</th>
                                <th>Approved By</th>
                                <th>Approved At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($approvedLoans as $loan)
                            <tr>
                                <td>{{ $loan->id }}</td>
                                <td>{{ $loan->ref_no }}</td>
                                <td>{{ $loan->bank_name }}</td>
                                <td>{{ $loan->customer_name }}</td>
                                <td>{{ $loan->percentage }}%</td>
                                <td class="text-end">₹{{ number_format((float)$loan->interest_amount, 2) }}</td>
                                <td>{{ $loan->approver->name ?? 'Admin' }}</td>
                                <td>
                                    {{ $loan->approved_at ? $loan->approved_at->format('d-m-Y h:i A') : '-' }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">No approved loans yet.</div>
            @endif
        </div>

        {{-- REJECTED LOANS --}}
        <div class="tab-pane fade" id="rejected" role="tabpanel">
            @if($rejectedLoans->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-danger">
                            <tr>
                                <th>ID</th>
                                <th>Ref No</th>
                                <th>Bank Name</th>
                                <th>Customer</th>
                                <th>Rejection Reason</th>
                                <th>Rejected At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rejectedLoans as $loan)
                            <tr>
                                <td>{{ $loan->id }}</td>
                                <td>{{ $loan->ref_no }}</td>
                                <td>{{ $loan->bank_name }}</td>
                                <td>{{ $loan->customer_name }}</td>
                                <td>
                                    <small class="text-danger">{{ $loan->rejection_reason }}</small>
                                </td>
                                <td>
                                    {{ $loan->updated_at->format('d-m-Y h:i A') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">No rejected loans.</div>
            @endif
        </div>

    </div>
</div>

<style>
.nav-tabs .nav-link {
    color: #495057;
}
.nav-tabs .nav-link.active {
    font-weight: bold;
}
.badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}
</style>
@endsection
