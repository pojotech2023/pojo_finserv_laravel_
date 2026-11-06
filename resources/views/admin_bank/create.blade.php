@extends('layouts.app')

@section('content')
<h4>Add Bank</h4>

<form method="POST" action="{{ route('admin-bank.store') }}">
@csrf

<select name="bank_name" class="form-control mb-3" required>
    <option value="">-- Select Bank --</option>
    <option value="AXIS BANK">AXIS BANK</option>
    <option value="HDFC BANK">HDFC BANK</option>
    <option value="YES BANK">YES BANK</option>
    <option value="ICICI BANK">ICICI BANK</option>
</select>

<input type="text" name="user_nick_name" class="form-control mb-3" placeholder="User Nick Name" required>

<button class="btn btn-success">Save</button>
</form>
@endsection
