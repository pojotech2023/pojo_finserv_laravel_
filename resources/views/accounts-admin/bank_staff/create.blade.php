@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-3">Add Bank Staff</h3>
    <form action="{{ route('bank-staff.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('accounts-admin.bank_staff.form')
        <button class="btn btn-success">Save</button>
        <a href="{{ route('bank-staff.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
