@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Excess Detail for Statement ID: {{ $statement->id }}</h3>

    {{-- Bank Statement Details --}}
    <form action="" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="statement_id" value="{{ $statement->id }}">

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
        </div>

        <div class="mb-3">
            <label class="form-label">Name (Bank Statement)</label>
            <input type="text" class="form-control" readonly value="{{ $statement->name }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Action</label>
            <input type="text" class="form-control" readonly value="{{ $statement->action_type }}">
        </div>

        {{-- Ref No --}}
       <div class="mb-3">
    <label class="form-label">Ref No</label>
    <div class="input-group" style="width: 200px;">
        <input type="text" id="ref_no" name="ref_no" class="form-control" placeholder="Enter Ref No">
        <span class="input-group-text">/R</span>
    </div>
</div>

 <label>KYC Documents (multiple)</label>
        {{-- Existing KYC files --}}
        <div id="excess-files-list" style="margin-top:10px;"></div>
 <label>Uploaded LETTER</label>
        {{-- Existing letter --}}
        <div id="excess-letter-link" style="margin-top:5px;"></div>

        {{-- Upload Multiple KYC files --}}
        {{-- <div class="mb-3">
            <label>KYC Documents (multiple)</label>
            <div id="kyc-file-inputs">
                <input type="file" name="kyc_files[]" class="form-control mb-2">
            </div>
            <button type="button" id="add-kyc-file" class="btn btn-secondary btn-sm">+ Add More</button>
        </div> --}}

        {{-- Upload Letter --}}
        {{-- <div class="mb-3">
            <label>UPLOAD LETTER</label>
            <input type="file" name="letter" class="form-control mb-2">
        </div>--}}

        
        <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
    </form>
</div>

{{-- jQuery --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {

    // Add more KYC file inputs
    $('#add-kyc-file').click(function(){
        $('#kyc-file-inputs').append('<input type="file" name="kyc_files[]" class="form-control mb-2">');
    });

    // Fetch existing excess by Ref No
    $('#ref_no').on('change', function() {
        var refNo = $(this).val();
        if(refNo){
            $.ajax({
                url: '/excess/by-ref/' + refNo,
                method: 'GET',
                success: function(res){
                    if(res.success){
                        // Show existing files
                        var filesHtml = '<ul>';
                        res.files.forEach(function(file){
                            filesHtml += '<li><a href="/storage/' + file.filepath + '" target="_blank">' + file.filename + '</a></li>';
                        });
                        filesHtml += '</ul>';
                        $('#excess-files-list').html(filesHtml);

                        // Show letter if exists
                        if(res.excess.letter_path){
                            $('#excess-letter-link').html('<a href="/storage/' + res.excess.letter_path + '" target="_blank">View Current Letter</a>');
                        } else {
                            $('#excess-letter-link').html('');
                        }

                    } else {
                        $('#excess-files-list').html('<span style="color:red;">No record found for this Ref No.</span>');
                        $('#excess-letter-link').html('');
                    }
                }
            });
        }
    });
});
</script>
@endsection
