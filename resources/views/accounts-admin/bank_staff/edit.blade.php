@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-3">Edit Bank Staff</h3>
    <form action="{{ route('bank-staff.update', $bankStaff) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        @include('accounts-admin.bank_staff.form', ['bankStaff' => $bankStaff])
        <button class="btn btn-primary">Update</button>
        <a href="{{ route('bank-staff.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
