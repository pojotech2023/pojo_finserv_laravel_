@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3>Edit Account</h3>

    <form action="{{ route('accounts.update', $account->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Bank Name *</label>
            <input type="text" name="bank_name" class="form-control" value="{{ old('bank_name', $account->bank_name) }}" required>
        </div>

        <div class="mb-3">
            <label>Bank Account Number *</label>
            <input type="text" name="account_number" class="form-control" value="{{ old('account_number', $account->account_number) }}" required>
        </div>

        <div class="mb-3">
            <label>IFSC Code *</label>
            <input type="text" name="ifsc_code" class="form-control" value="{{ old('ifsc_code', $account->ifsc_code) }}" required>
        </div>

        <div class="mb-3">
            <label>Address</label>
            <textarea name="address" class="form-control">{{ old('address', $account->address) }}</textarea>
        </div>

        <div class="mb-3">
            <label>Account Holder Name *</label>
            <input type="text" name="account_holder_name" class="form-control" value="{{ old('account_holder_name', $account->account_holder_name) }}" required>
        </div>

        <div class="mb-3">
            <label>GST</label>
            <input type="text" name="gst" class="form-control" value="{{ old('gst', $account->gst) }}">
        </div>

        <button type="submit" class="btn btn-primary mt-2">Update</button>
        <a href="{{ route('accounts.index') }}" class="btn btn-secondary mt-2">Cancel</a>
    </form>
</div>
@endsection
