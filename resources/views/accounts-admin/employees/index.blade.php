@extends('layouts.app')

@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <h3 class="mb-4">Employees</h3>
    <a href="{{ route('employees.create') }}" class="btn btn-primary mb-3"  style="float:right">+ Add Employee</a>

    

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Emp ID</th>
                <th>Aadhaar</th>
                <th>Mobile</th>
                <th>Document</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($employees as $employee)
                <tr>
                    <td>{{ $employee->name }}</td>
                    <td>{{ $employee->employee_id }}</td>
                    <td>{{ $employee->aadhaar_number }}</td>
                    <td>{{ $employee->mobile_number }}</td>
                    <td>
                        @if($employee->document_path)
                            <a href="{{ asset('storage/'.$employee->document_path) }}" target="_blank">View</a>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('employees.edit', $employee) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('employees.destroy', $employee) }}" method="POST" style="display:inline-block;">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
