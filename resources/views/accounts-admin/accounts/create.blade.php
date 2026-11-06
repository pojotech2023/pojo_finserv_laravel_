@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3>Add Account</h3>

    <form action="{{ route('accounts.store') }}" method="POST">
        @csrf
       

<div class="mb-3">
    <label>Bank Name *</label>
    <input type="text" name="bank_name" class="form-control" required>
</div>

<div class="mb-3">
    <label>Bank Account Number *</label>
    <input type="text" name="account_number" class="form-control"  required>
</div>

<div class="mb-3">
    <label>IFSC Code *</label>
    <input type="text" name="ifsc_code" class="form-control"  required>
</div>

<div class="mb-3">
    <label>Address</label>
    <textarea name="address" class="form-control"></textarea>
</div>

<div class="mb-3">
    <label>Account Holder Name *</label>
    <input type="text" name="account_holder_name" class="form-control"  required>
</div>

<div class="mb-3">
    <label>GST</label>
    <input type="text" name="gst" class="form-control" >
</div>

        <button type="submit" class="btn btn-success mt-2">Save</button>
        <a href="{{ route('accounts.index') }}" class="btn btn-secondary mt-2">Cancel</a>
    </form>
</div>
@endsection
