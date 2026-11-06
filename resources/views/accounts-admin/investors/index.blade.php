@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3>Investor List</h3>
    <a href="{{ route('investors.create') }}" class="btn btn-success mb-3" style="float:right">+ Add Investor</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>S.No</th>
                <th>Name</th>
                <th>Account No</th>
                <th>IFSC</th>
                <th>GST</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($investors as $investor)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $investor->name }}</td>
                <td>{{ $investor->account_number }}</td>
                <td>{{ $investor->ifsc_code }}</td>
                <td>{{ $investor->gst_number }}</td>
                <td>
                    <a href="{{ route('investors.edit', $investor->id) }}" class="btn btn-sm btn-info">Edit</a>
                    <form action="{{ route('investors.destroy', $investor->id) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this investor?')">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
