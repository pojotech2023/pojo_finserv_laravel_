@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Add Bank Detail</h3>
    <form action="{{ route('bank-details.store') }}" method="POST">
        @csrf
        @include('accounts-admin.bank_details.form')
        <button class="btn btn-success">Save</button>
        <a href="{{ route('bank-details.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
