@extends('layouts.app')
@section('content')
<div class="container">
    <h3>Excess Detail for Statement ID: {{ $statement->id }}</h3>

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

    <form action="{{ route('bank-statement.saveexcess', $statement->id) }}" 
      method="POST" enctype="multipart/form-data">
    @csrf
<input type="hidden" name="conform_ref_no" value="{{ $statement->ref_no }}">
<input type="hidden" class="form-control" name="old_action_type"value="{{ $statement->action_type }}">
    <div class="mb-3">
        <label>KYC Documents (multiple)</label>
        <input type="hidden" class="form-control" name="ref_no" value="{{ $statement->ref_no }}">
        <div id="kyc-file-inputs">
            <input type="file" name="kyc_files[]" class="form-control mb-2">
        </div>
         <button type="button" id="add-kyc-file" class="btn btn-secondary btn-sm">+ Add More</button>
   
        @if(isset($excess) && $excess->files)
            <ul>
                @foreach($excess->files as $file)
                    <li>
                        <a href="{{ asset('storage/'.$file->filepath) }}" target="_blank">
                            {{ $file->filename }}
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
        </div>

    <div class="mb-3">
        <label>UPLOAD LETTER</label>
        <input type="file" name="letter" class="form-control mb-2">
        @if(isset($excess) && $excess->letter_path)
            <a href="{{ asset('storage/'.$excess->letter_path) }}" target="_blank">View Current Letter</a>
        @endif
    </div>

    <button type="submit" class="btn btn-primary">Save Excess</button>
    <a href="{{ route('bank.statement') }}" class="btn btn-secondary">Back</a>
</form>
   <!-- Loader Overlay -->
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
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(function () {
    // Add more KYC file inputs dynamically
    $('#add-kyc-file').on('click', function () {
        $('#kyc-file-inputs').append('<input type="file" name="kyc_files[]" class="form-control mb-2">');
    });

});


     $('button[type="submit"]').on('click', function () {
         $("#loader-overlay").css("display", "flex").hide().fadeIn(200);
    });
</script>
@endsection
