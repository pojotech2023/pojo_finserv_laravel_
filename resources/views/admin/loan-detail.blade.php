@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <h3 class="mb-4">Loan Details</h3>

    <div class="card">
        <div class="card-body">

            <table class="table table-bordered">

                <tr>
                    <th>ID</th>
                    <td>{{ $loan->id }}</td>
                </tr>

                <tr>
                    <th>Bank Name</th>
                    <td>{{ $loan->bank_name }}</td>
                </tr>

                <tr>
                    <th>Staff Name</th>
                    <td>{{ $loan->staff_name }}</td>
                </tr>

                <tr>
                    <th>Customer Name</th>
                    <td>{{ $loan->customer_name }}</td>
                </tr>

                <tr>
                    <th>Percentage</th>
                    <td>{{ $loan->percentage }} %</td>
                </tr>

                <tr>
                    <th>Interest Period</th>
                    <td>{{ $loan->interest_period }}</td>
                </tr>

                <tr>
                    <th>Interest Amount</th>
                    <td>₹ {{ number_format($loan->interest_amount, 2) }}</td>
                </tr>

                <tr>
                    <th>Ref No</th>
                    <td>{{ $loan->ref_no }}</td>
                </tr>

                <tr>
                    <th>Remarks</th>
                    <td>{{ $loan->remarks ?? '-' }}</td>
                </tr>

                <tr>
                    <th>Status</th>
                    <td>
                        @if($loan->approval_status == 'approved')
                            <span class="badge bg-success">Approved</span>
                        @elseif($loan->approval_status == 'rejected')
                            <span class="badge bg-danger">Rejected</span>
                        @else
                            <span class="badge bg-warning">Pending</span>
                        @endif
                    </td>
                </tr>

                <tr>
                    <th>Approved At</th>
                    <td>
                        {{ $loan->approved_at ? $loan->approved_at->format('d-m-Y h:i A') : '-' }}
                    </td>
                </tr>

                <tr>
                    <th>Approved By</th>
                    <td>
                        {{ $loan->approver ? $loan->approver->name : '-' }}
                    </td>
                </tr>

            </table>

            <a href="{{ url()->previous() }}" class="btn btn-secondary">
                Back
            </a>

        </div>
    </div>

</div>
@endsection
