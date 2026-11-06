@php
    $staff = $bankStaff ?? null;
@endphp

<div class="mb-3">
    <label>Staff Name *</label>
    <input type="text" name="staff_name" class="form-control" required value="{{ old('staff_name', $staff->staff_name ?? '') }}">
</div>

<div class="mb-3">
    <label>Bank Name *</label>
    <input type="text" name="bank_name" class="form-control" required value="{{ old('bank_name', $staff->bank_name ?? '') }}">
</div>

<div class="mb-3">
    <label>Employee ID</label>
    <input type="text" name="employee_id" class="form-control" value="{{ old('employee_id', $staff->employee_id ?? '') }}">
</div>

<div class="mb-3">
    <label>Designation *</label>
    <input type="text" name="employee_designation" class="form-control" required value="{{ old('employee_designation', $staff->employee_designation ?? '') }}">
</div>

<div class="mb-3">
    <label>Aadhaar Number</label>
    <input type="text" name="aadhaar_number" class="form-control" value="{{ old('aadhaar_number', $staff->aadhaar_number ?? '') }}">
</div>

<div class="mb-3">
    <label>Address</label>
    <textarea name="address" class="form-control">{{ old('address', $staff->address ?? '') }}</textarea>
</div>

<div class="mb-3">
    <label>Upload Document</label>
    <input type="file" name="document" class="form-control">
    @if (!empty($staff->document_path))
        <small>Current: <a href="{{ asset('storage/'.$staff->document_path) }}" target="_blank">View</a></small>
    @endif
</div>

<div class="mb-3">
    <label>Percentage</label>
    <input type="text"  name="percentage" class="form-control" value="{{ old('percentage', $staff->percentage ?? 0) }}">
</div>
