@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Add New Employee</h3>

    <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('accounts-admin.employees.form')
        <button type="submit" class="btn btn-success">Save</button>
        <a href="{{ route('employees.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
