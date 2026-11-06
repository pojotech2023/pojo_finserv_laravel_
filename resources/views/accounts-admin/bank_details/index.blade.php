@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Bank Details</h3>
        <a href="{{ route('bank-details.create') }}" class="btn btn-primary">+ Add Bank</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Bank Name</th>
                <th>Journal</th>
                <th>Cluster</th>
                <th>Region</th>
                <th>Area</th>
                <th>State</th>
                <th>Referred By</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($banks as $bank)
                <tr>
                    <td>{{ $bank->bank_name }}</td>
                    <td>{{ $bank->journal }}</td>
                    <td>{{ $bank->cluster }}</td>
                    <td>{{ $bank->region }}</td>
                    <td>{{ $bank->area }}</td>
                    <td>{{ $bank->state }}</td>
                    <td>{{ $bank->referred_by }}</td>
                    <td>
                        <a href="{{ route('bank-details.edit', $bank) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('bank-details.destroy', $bank) }}" method="POST" style="display:inline-block;">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
