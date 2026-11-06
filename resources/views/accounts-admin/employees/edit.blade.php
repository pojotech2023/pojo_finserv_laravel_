@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Edit Employee</h3>

    <form action="{{ route('employees.update', $employee) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        @include('accounts-admin.employees.form')
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('employees.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
