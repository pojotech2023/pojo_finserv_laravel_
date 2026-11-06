@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3>Add Investor</h3>

    <form method="POST" action="{{ route('investors.store') }}">
        @csrf
         @php $investor = $investor ?? null; @endphp

        <div class="mb-3">
            <label>Name *</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $investor->name ?? '') }}" required>
        </div>

       <div class="mb-3">
    <label>Investment Duration *</label>
    <div style="display: flex; gap: 8px;">
        <input type="number" name="investment_duration_value" class="form-control"
               value="{{ old('investment_duration_value', $investor->investment_duration_value ?? '') }}"
               placeholder="Enter number" min="1" required>

        <select name="investment_duration_unit" class="form-control" required>
            <option value="day" {{ old('investment_duration_unit', $investor->investment_duration_unit ?? '') == 'day' ? 'selected' : '' }}>Day(s)</option>
            <option value="week" {{ old('investment_duration_unit', $investor->investment_duration_unit ?? '') == 'week' ? 'selected' : '' }}>Week(s)</option>
            <option value="month" {{ old('investment_duration_unit', $investor->investment_duration_unit ?? '') == 'month' ? 'selected' : '' }}>Month(s)</option>
            <option value="year" {{ old('investment_duration_unit', $investor->investment_duration_unit ?? '') == 'year' ? 'selected' : '' }}>Year(s)</option>
        </select>
    </div>
</div>


        <div class="mb-3">
            <label>Percentage</label>
            <input type="text" name="percentage" class="form-control" value="{{ old('percentage', $investor->percentage ?? '') }}">
        </div>

        <div class="mb-3">
            <label>Payout Time</label>
            <input type="text" name="payout_time" class="form-control" value="{{ old('payout_time', $investor->payout_time ?? '') }}">
        </div>

        <div class="mb-3">
            <label>Account Number *</label>
            <input type="text" name="account_number" class="form-control" value="{{ old('account_number', $investor->account_number ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label>IFSC Code *</label>
            <input type="text" name="ifsc_code" class="form-control" value="{{ old('ifsc_code', $investor->ifsc_code ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label>Branch Name</label>
            <input type="text" name="branch_name" class="form-control" value="{{ old('branch_name', $investor->branch_name ?? '') }}">
        </div>

        <div class="mb-3">
            <label>Address</label>
            <textarea name="address" class="form-control">{{ old('address', $investor->address ?? '') }}</textarea>
        </div>

        <div class="mb-3">
            <label>GST Number</label>
            <input type="text" name="gst_number" class="form-control" value="{{ old('gst_number', $investor->gst_number ?? '') }}">
        </div>

        <button type="submit" class="btn btn-success mt-2">Save</button>
        <a href="{{ route('investors.index') }}" class="btn btn-secondary mt-2">Cancel</a>
    </form>
</div>
@endsection
