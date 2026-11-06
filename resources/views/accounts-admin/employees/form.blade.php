<div class="mb-3">
    <label>Name *</label>
    <input type="text" name="name" value="{{ old('name', $employee->name ?? '') }}" class="form-control" required>
</div>

<div class="mb-3">
    <label>Employee ID</label>
    <input type="text" name="employee_id" value="{{ old('employee_id', $employee->employee_id ?? '') }}" class="form-control">
</div>

<div class="mb-3">
    <label>Address</label>
    <textarea name="address" class="form-control">{{ old('address', $employee->address ?? '') }}</textarea>
</div>

<div class="mb-3">
    <label>Aadhaar Number *</label>
    <input type="text" name="aadhaar_number" value="{{ old('aadhaar_number', $employee->aadhaar_number ?? '') }}" class="form-control" required>
</div>

<div class="mb-3">
    <label>Mobile Number *</label>
    <input type="text" name="mobile_number" value="{{ old('mobile_number', $employee->mobile_number ?? '') }}" class="form-control" required>
</div>

<div class="mb-3">
    <label>Upload Document</label>
    <input type="file" name="document" class="form-control">
    @if (!empty($employee->document_path))
        <small>Current: <a href="{{ asset('storage/'.$employee->document_path) }}" target="_blank">View Document</a></small>
    @endif
</div>
