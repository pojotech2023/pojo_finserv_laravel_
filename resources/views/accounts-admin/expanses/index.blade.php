@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Expanses <a href="{{ route('expanses.create') }}" class="btn btn-primary float-end">Add New</a></h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Remarks</th>
                <th>Bill</th>
                <th>GST Number</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($expanses as $expanse)
                <tr>
                    <td>{{ $expanse->remarks }}</td>
                    <td>
                        @if($expanse->upload_bills)
                            <a href="{{ asset('storage/' . $expanse->upload_bills) }}" target="_blank">View Bill</a>
                        @endif
                    </td>
                    <td>{{ $expanse->gst_number }}</td>
                    <td>
                        <a href="{{ route('expanses.edit', $expanse->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('expanses.destroy', $expanse->id) }}" method="POST" style="display:inline-block;">
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
