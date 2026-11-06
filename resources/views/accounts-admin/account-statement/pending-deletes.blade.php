@extends('layouts.app')

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="container-fluid">
    <div class="card shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">⏳ Pending Delete Requests</h5>
            <a href="{{ route('bank.statement') }}" class="btn btn-secondary btn-sm">
                ← Back to Statements
            </a>
        </div>

        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-warning text-center">
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Name</th>
                        <th>UTR No</th>
                        <th>Amount</th>
                        <th>CR/DR</th>
                        <th>Deleted At</th>
                        <th>Requested At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($statements as $index => $statement)
                    <tr class="table-warning">
                        <td class="text-center">{{ $statements->firstItem() + $index }}</td>
                        <td>{{ \Carbon\Carbon::parse($statement->date)->format('d-m-Y') }}</td>
                        <td>{{ $statement->name }}</td>
                        <td>{{ $statement->utrno }}</td>
                        <td class="text-end">{{ number_format($statement->amount, 2) }}</td>
                        <td class="text-center">
                            @if($statement->cr_dr == 'CR')
                                <span class="badge bg-success">CR</span>
                            @else
                                <span class="badge bg-danger">DR</span>
                            @endif
                        </td>
                        <td class="text-center text-muted small">
                           {{-- ✅ Line 59 --}}
{{ $statement->deleted_at ? \Carbon\Carbon::parse($statement->deleted_at)->format('d-m-Y H:i') : '-' }}
                        </td>
                        <td class="text-center text-muted small">
                            {{ $statement->delete_requested_at ? \Carbon\Carbon::parse($statement->delete_requested_at)->format('d-m-Y H:i') : '-' }}
                        </td>
                        <td class="text-center" style="min-width: 180px;">

                            {{-- ✅ Approve --}}
                            <form action="{{ route('account.statement.approve.delete', $statement->id) }}"
                                method="POST" class="d-inline"
                                onsubmit="return confirm('Approve and permanently delete this record?')">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success mb-1">
                                    ✅ Approve Delete
                                </button>
                            </form>

                            {{-- ✅ Reject --}}
                            <form action="{{ route('account.statement.reject.delete', $statement->id) }}"
                                method="POST" class="d-inline"
                                onsubmit="return confirm('Reject this delete request?')">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger mb-1">
                                    ❌ Reject
                                </button>
                            </form>

                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">
                            No pending delete requests.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="d-flex justify-content-end mt-3">
                {{ $statements->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

@endsection