@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Edit Bank Detail</h3>
    <form action="{{ route('bank-details.update', $bankDetail) }}" method="POST">
        @csrf @method('PUT')
        @include('accounts-admin.bank_details.form')
        <button class="btn btn-primary">Update</button>
        <a href="{{ route('bank-details.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
