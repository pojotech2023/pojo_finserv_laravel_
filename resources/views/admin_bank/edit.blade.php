@extends('layouts.app')

@section('content')
<h4>Edit Bank</h4>

<form method="POST" action="{{ route('admin-bank.update',$bank->id) }}">
@csrf

<select name="bank_name" class="form-control mb-3" required>
    <option value="">-- Select Bank --</option>
    <option value="AXIS BANK" {{ in_array($bank->bank_name, ['AXIS BANK', 'AXIS']) ? 'selected' : '' }}>AXIS BANK</option>
    <option value="HDFC BANK" {{ in_array($bank->bank_name, ['HDFC BANK', 'HDFC']) ? 'selected' : '' }}>HDFC BANK</option>
    <option value="YES BANK" {{ in_array($bank->bank_name, ['YES BANK', 'YES']) ? 'selected' : '' }}>YES BANK</option>
    <option value="ICICI BANK" {{ in_array($bank->bank_name, ['ICICI BANK', 'ICICI']) ? 'selected' : '' }}>ICICI BANK</option>
</select>

<input type="text" name="user_nick_name" class="form-control mb-3" value="{{ $bank->user_nick_name }}" required>

<button class="btn btn-primary">Update</button>
</form>
@endsection
