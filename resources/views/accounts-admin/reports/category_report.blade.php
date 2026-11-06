@extends('layouts.app')
@section('content')

<div class="container mt-4">
    <h4 class="mb-4 text-primary text-capitalize">
        {{ ucfirst($category) }} {{ ucfirst(strtolower($type)) }} Report
    </h4>

    <form method="GET" class="row mb-3">
        <div class="col-md-3">
            <label>From Date</label>
            <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control">
        </div>
        <div class="col-md-3">
            <label>To Date</label>
            <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control">
        </div>
        <div class="col-md-3 mt-4">
            <button class="btn btn-success w-100">Filter</button>
        </div>
        <div class="col-md-3 mt-4">
            <a href="{{ route('report.export', ['category' => $category, 'type' => strtolower($type), 'from_date' => request('from_date'), 'to_date' => request('to_date')]) }}"
               class="btn btn-primary w-100">
                Export Excel
            </a>
        </div>
    </form>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Date</th>
                <th>UTR No</th>
                <th>Remarks</th>
                <th>Name</th>
                <th class="text-end">Amount</th>
                <th>CR/DR</th>
                <th>Conform Ref No</th>
            </tr>
        </thead>
        <tbody>
            @forelse($statements as $s)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($s->date)->format('d-m-Y') }}</td>
                    <td>{{ $s->utrno }}</td>
                    <td>{{ $s->remarks }}</td>
                    <td>{{ $s->name }}</td>
                    <td class="text-end">{{ number_format($s->amount, 2) }}</td>
                    <td>
                        <span class="badge {{ $s->cr_dr == 'CR' ? 'bg-success' : 'bg-danger' }}">{{ $s->cr_dr }}</span>
                    </td>
                    <td>{{ $s->conform_ref_no }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">No records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {!! $statements->links() !!}
    </div>
</div>
@endsection
