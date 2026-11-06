@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3>PROCESS BANK NAME</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('accounts.create') }}" class="btn btn-primary mb-3" style="float:right">Add Account</a>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Bank Name</th>
                <th>Account Number</th>
                <th>IFSC</th>
                <th>Holder Name</th>
                <th>GST</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($accounts as $account)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $account->bank_name }}</td>
                    <td>{{ $account->account_number }}</td>
                    <td>{{ $account->ifsc_code }}</td>
                    <td>{{ $account->account_holder_name }}</td>
                    <td>{{ $account->gst }}</td>
                    <td>
                        <a href="{{ route('accounts.edit', $account->id) }}" class="btn btn-sm btn-info">Edit</a>
                        <form action="{{ route('accounts.destroy', $account->id) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
