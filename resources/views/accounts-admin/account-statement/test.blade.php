@extends('layouts.app')

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('warning'))
<div class="alert alert-warning alert-dismissible fade show" role="alert">
    {{ session('warning') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="container-fluid">
    <div class="card shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">🗑 Deleted Bank Statements</h5>
            <a href="{{ route('bank.statement') }}" class="btn btn-secondary btn-sm">
                ← Back to Statements
            </a>
        </div>

        <div class="card-body">

            {{-- Filter --}}
            <form method="GET" action="{{ route('account.statement.trashed') }}" class="row g-2 mb-3">
                <div class="col-md-3">
                    <input type="text" name="name" class="form-control"
                        placeholder="Filter by Name" value="{{ request('name') }}">
                </div>
                <div class="col-md-2">
                    <input type="date" name="from_date" class="form-control"
                        value="{{ request('from_date') }}">
                </div>
                <div class="col-md-2">
                    <input type="date" name="to_date" class="form-control"
                        value="{{ request('to_date') }}">
                </div>
                <div class="col-md-auto">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('account.statement.trashed') }}"
                        class="btn btn-outline-secondary">Clear</a>
                </div>
            </form>

            {{-- Restore by Date Range --}}
            <div class="card border-success mb-4">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center"
                    style="cursor:pointer;"
                    data-bs-toggle="collapse"
                    data-bs-target="#restoreDateRangePanel">
                    <span>♻️ Restore Records by Date Range</span>
                    <span>▼</span>
                </div>
                <div class="collapse" id="restoreDateRangePanel">
                    <div class="card-body">
                        <form method="POST" action="{{ route('account.statement.restoreByDate') }}">
                            @csrf
                            <div class="row g-3 align-items-end">
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">From Date</label>
                                    <input type="date" name="restore_from_date"
                                        class="form-control @error('restore_from_date') is-invalid @enderror"
                                        value="{{ old('restore_from_date') }}" required>
                                    @error('restore_from_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">To Date</label>
                                    <input type="date" name="restore_to_date"
                                        class="form-control @error('restore_to_date') is-invalid @enderror"
                                        value="{{ old('restore_to_date') }}" required>
                                    @error('restore_to_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-auto">
                                    <button type="submit" class="btn btn-success"
                                        onclick="return confirm('Restore all records in this date range?')">
                                        ♻️ Restore Records
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Trashed Table --}}
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-warning text-center">
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>UTR No</th>
                            <th>Remarks</th>
                            <th>Name</th>
                            <th>Amount</th>
                            <th>CR/DR</th>
                            <th>Deleted At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($statements as $index => $statement)
                        <tr class="table-danger">
                            <td class="text-center">{{ $statements->firstItem() + $index }}</td>
                            <td>{{ \Carbon\Carbon::parse($statement->date)->format('d-m-Y') }}</td>
                            <td>{{ $statement->utrno }}</td>
                            <td>{{ $statement->remarks }}</td>
                            <td>{{ $statement->name }}</td>
                            <td class="text-end">{{ number_format($statement->amount, 2) }}</td>
                            <td class="text-center">
                                @if($statement->cr_dr == 'CR')
                                    <span class="badge bg-success">CR</span>
                                @else
                                    <span class="badge bg-danger">DR</span>
                                @endif
                            </td>
                            <td class="text-center text-muted small">
                                {{ \Carbon\Carbon::parse($statement->deleted_at)->format('d-m-Y H:i') }}
                            </td>
                            <td class="text-center" style="min-width: 180px;">

                                {{-- ✅ Restore Button --}}
                                <form method="POST"
                                    action="{{ route('account.statement.restore', $statement->id) }}"
                                    class="d-inline"
                                    onsubmit="return confirm('Restore this record?')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success mb-1"
                                        title="Restore">
                                        ♻️ Restore
                                    </button>
                                </form>

                                {{-- ✅ Permanent Delete (Force Delete) with approval status --}}
{{-- ✅ Permanent Delete with status check --}}
@if($statement->delete_status === 'pending')
    <span class="badge bg-warning text-dark d-block mt-1">
        ⏳ Waiting for Admin Approval
    </span>

@elseif($statement->delete_status === 'rejected')
    <span class="badge bg-danger d-block mb-1">❌ Delete Rejected</span>
    <form action="{{ route('account.statement.request.delete', $statement->id) }}"
        method="POST" class="d-inline"
        onsubmit="return confirm('Re-send delete request to admin?')">
        @csrf
        <button type="submit" class="btn btn-sm btn-outline-danger mt-1">
            🔁 Re-request Delete
        </button>
    </form>

@else
    <form action="{{ route('account.statement.request.delete', $statement->id) }}"
        method="POST" class="d-inline"
        onsubmit="return confirm('Send permanent delete request to admin?')">
        @csrf
        <button type="submit" class="btn btn-sm btn-danger mt-1">
            🗑 Request Permanent Delete
        </button>
    </form>
@endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                No deleted records found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-end mt-3">
                {{ $statements->links('pagination::bootstrap-5') }}
            </div>

        </div>
    </div>
</div>

@endsection