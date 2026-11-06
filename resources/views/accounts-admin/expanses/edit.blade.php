@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="fw-bold mb-4">Edit Expanses</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('expanses.update', $expanse->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="remarks" class="form-label">Remarks *</label>
            <input type="text" name="remarks" class="form-control" value="{{ old('remarks', $expanse->remarks) }}" required>
        </div>

        <div class="mb-3">
            <label for="gst_number" class="form-label">GST Number</label>
            <input type="text" name="gst_number" class="form-control" value="{{ old('gst_number', $expanse->gst_number) }}">
        </div>

        <div class="mb-3">
            <label for="bills" class="form-label">Upload Bills</label>
            <input type="file" name="bills" class="form-control">
           @if($expanse->upload_bills)
                            <a href="{{ asset('storage/' . $expanse->upload_bills) }}" target="_blank">Current Bill</a>
                        @endif
        </div>

        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('expanses.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
