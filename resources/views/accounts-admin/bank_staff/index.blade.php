@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Bank Staff Name</h3>
        <a href="{{ route('bank-staff.create') }}" class="btn btn-primary">+ Add Staff</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Staff Name</th>
                <th>Bank</th>
                <th>Emp ID</th>
                <th>Designation</th>
                <th>Aadhaar</th>
                <th>Document</th>
                <th>Percentage</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($staff as $item)
                <tr>
                    <td>{{ $item->staff_name }}</td>
                    <td>{{ $item->bank_name }}</td>
                    <td>{{ $item->employee_id }}</td>
                    <td>{{ $item->employee_designation }}</td>
                    <td>{{ $item->aadhaar_number }}</td>
                    <td>
                        @if($item->document_path)
                            <a href="{{ asset('storage/'.$item->document_path) }}" target="_blank">View</a>
                        @endif
                    </td>
                    <td>{{ $item->percentage }}%</td>
                    <td>
                        <a href="{{ route('bank-staff.edit', $item) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('bank-staff.destroy', $item) }}" method="POST" style="display:inline-block;">
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
