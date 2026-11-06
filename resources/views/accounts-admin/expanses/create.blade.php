@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Add Expanse</h3>
    <form action="{{ route('expanses.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label>Remarks</label>
            <textarea name="remarks" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label>Upload Bill</label>
            <input type="file" name="upload_bills" class="form-control">
        </div>

        <div class="mb-3">
            <label>GST Number</label>
            <input type="text" name="gst_number" class="form-control">
        </div>

        <button class="btn btn-success">Submit</button>
    </form>
</div>
@endsection
