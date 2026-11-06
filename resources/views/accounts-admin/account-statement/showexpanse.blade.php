@extends('layouts.app')
@section('content')
<div class="container">
    <h3>Expanse Detail for Statement ID: {{ $statement->id }}</h3>

    <div class="mb-3">
        <label class="form-label">Date</label>
        <input type="text" class="form-control" readonly value="{{ optional($statement->date)->format('d-m-Y') }}">
    </div>
    <div class="mb-3">
        <label class="form-label">UTR No</label>
        <input type="text" class="form-control" readonly value="{{ $statement->utrno }}">
    </div>
    <div class="mb-3">
        <label class="form-label">Remarks</label>
        <textarea class="form-control" rows="3" readonly>{{ $statement->remarks }}</textarea>
    </div>
    <div class="mb-3">
        <label class="form-label">Amount</label>
        <input type="text" class="form-control" readonly value="{{ number_format($statement->amount, 2) }}">
        <input type="hidden" id="original-amount" value="{{ $statement->amount }}">
     </div>
    <div class="mb-3">
        <label class="form-label">Name</label>
        <input type="text" class="form-control" readonly value="{{ $statement->name }}">
    </div>
    <div class="mb-3">
        <label class="form-label">Action</label>
        <input type="text" class="form-control" readonly value="{{ $statement->action_type }}">
    </div>

    <div class="mb-3">
        <label class="form-label">REF NO</label>
        <input type="text" class="form-control" readonly value="{{ $statement->ref_no }}">
    
    </div>

    <form action="{{ route('bank-statement.saveexpanses', $statement->id) }}" 
      method="POST" enctype="multipart/form-data">
    @csrf
<input type="hidden" name="conform_ref_no" value="{{ $statement->ref_no }}">
<input type="hidden" class="form-control" name="old_action_type"value="{{ $statement->action_type }}">
     <div class="mb-3">
        <label class="form-label">Remarks </label>
       <input type="text" name="expanses_remark" class="form-control" value="{{ old('expanses_remark', $expanses->expanses_remark ?? '') }}">
    </div>
  <div class="mb-3">
    <label class="form-label">Expense Type</label>
    <select name="expense_type" class="form-control">
        <option value="">-- Select Type --</option>
        <option value="salary" {{ old('expense_type', $expanses->expense_type ?? '') == 'salary' ? 'selected' : '' }}>Salary</option>
        <option value="payout" {{ old('expense_type', $expanses->expense_type ?? '') == 'payout' ? 'selected' : '' }}>Payout</option>
        <option value="commission" {{ old('expense_type', $expanses->expense_type ?? '') == 'commission' ? 'selected' : '' }}>Commission</option>
        <option value="rent" {{ old('expense_type', $expanses->expense_type ?? '') == 'rent' ? 'selected' : '' }}>Rent</option>
        <option value="interest" {{ old('expense_type', $expanses->expense_type ?? '') == 'interest' ? 'selected' : '' }}>Interest</option>
        <option value="exrtn" {{ old('expense_type', $expanses->expense_type ?? '') == 'exrtn' ? 'selected' : '' }}>Ex Rtn</option>
    </select>
</div>

<div class="mb-3">
    <label class="form-label">Amount</label>
    <input type="text" name="amount" class="form-control"
           value="{{ old('amount', $expanses->amount ?? '') }}">
</div>

    
    <div class="mb-3">
        <label>UPLOAD Document</label>
        <input type="file" name="document" class="form-control mb-2">
       @if(!empty($expanses->document))
    <a href="{{ asset('storage/'.$expanses->document) }}" target="_blank">View Current Document</a>
@endif
    </div>

    <button type="submit" class="btn btn-primary">Save expanses</button>
    <a href="{{ route('bank.statement') }}" class="btn btn-secondary">Back</a>
</form>
<div id="loader-overlay" 
     style="display:none;
            position:fixed;
            top:0; left:0;
            width:100%; height:100%;
            background:rgba(255,255,255,0.6);
            backdrop-filter:blur(4px);
            z-index:9999;
            align-items:center;
            justify-content:center;">
    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(function () {
    // Add more KYC file inputs dynamically
    $('#add-kyc-file').on('click', function () {
        $('#kyc-file-inputs').append('<input type="file" name="kyc_files[]" class="form-control mb-2">');
    });

     $('button[type="submit"]').on('click', function () {
         $("#loader-overlay").css("display", "flex").hide().fadeIn(200);
    });
});

    
</script>
@endsection
