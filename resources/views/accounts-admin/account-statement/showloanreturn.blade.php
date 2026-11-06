@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Loan Detail for Statement ID: {{ $statement->id }}</h3>

    {{-- Bank Statement Details --}}
    <form action="{{ route('loan.save') }}" method="POST" enctype="multipart/form-data">
          @csrf
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

  <div class="mb-3">
    <label class="form-label">Ref No</label>
    <div class="input-group"  style="width: 400px;">
        <input type="text" id="ref_no" name="ref_no" class="form-control" placeholder="Enter Ref No">
        <span class="input-group-text">/R</span>
        <button type="button" id="search-btn" class="btn btn-outline-primary">Search</button>
    </div>
</div>
{{-- Loan Details --}}
<div class="mb-3">
    <label>Bank Name</label>
    <input type="text" id="bank-name" name="bank_name" class="form-control">
</div>
<div class="mb-3">
    <label>Staff Name</label>
    <input type="text" id="staff-name" name="staff_name" class="form-control">
</div>
<div class="mb-3">
    <label>Customer Name</label>
    <input type="text" id="customer-name" name="customer_name" class="form-control">
</div>
<div class="mb-3">
    <label>Percentage (%)</label>
    <input type="number" step="0.01" id="percentage" name="percentage" class="form-control">
</div>
<div class="mb-3">
    <label>Interest Amount</label>
    <input type="text" id="interest-amount" name="interest_amount" class="form-control" readonly>
</div>

{{-- KYC --}}
<div class="mb-3" id="kyc_document_div" style="display:none;">
    <label>KYC Document</label>
    <a id="kyc_document_link" href="#" target="_blank" class="btn btn-outline-primary btn-sm">View Document</a>
</div>
<div class="mb-3" id="kyc_upload_div" style="display:none;">
    <label>Upload KYC</label>
    <input type="file" class="form-control" name="loan_kyc_upload">
</div>
@if(!empty($loan->kyc_file))
<div class="mb-3">
    <label>Uploaded KYC Document</label><br>
    <a href="{{ asset('storage/'.$loan->kyc_file) }}" target="_blank" class="btn btn-outline-primary btn-sm">
        View Uploaded KYC
    </a>
</div>
@endif

<div class="mb-2" id="name-match-message" style="display:none;">
    <span id="name-match-text"></span>
</div>

    <button type="submit" class="btn btn-primary">Save Loan</button>
    <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
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
</div>

{{-- jQuery --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#search-btn').on('click', function() {
        var refNo = $('#ref_no').val().trim();

        if (refNo) {
            // Show loader
            $("#loader-overlay").css("display", "flex").hide().fadeIn(200);

            $.ajax({
                url: '/loan/by-ref/' + refNo,
                method: 'GET',
                success: function(res) {
                    // Hide loader after request completes
                    $("#loader-overlay").fadeOut(200);

                    if (res.success) {
                        $('#bank-name').val(res.loan.bank_name);
                        $('#staff-name').val(res.loan.staff_name);
                        $('#customer-name').val(res.loan.customer_name);
                        $('#percentage').val(res.loan.percentage);
                        $('#interest-amount').val(res.loan.interest_amount);

                        if (res.loan.kyc_file) {
                            $('#kyc_document_div').show();
                            $('#kyc_document_link').attr('href', '/storage/' + res.loan.kyc_file);
                        } else {
                            $('#kyc_document_div').hide();
                        }

                        if (res.nameMatch) {
                            $('#name-match-message').show();
                            $('#name-match-text').html('<span style="color:green; font-weight:bold;">✅ Name Match</span>');
                            $('#kyc_upload_div').hide();
                        } else {
                            $('#name-match-message').show();
                            $('#name-match-text').html('<span style="color:red; font-weight:bold;">❌ Name Not Match</span>');
                            $('#kyc_upload_div').show();
                        }

                    } else {
                        alert('No loan found for this Ref No');
                        $('#bank-name, #staff-name, #customer-name, #percentage, #interest-amount').val('');
                        $('#kyc_document_div').hide();
                        $('#kyc_upload_div').hide();
                        $('#name-match-message').hide();
                    }
                },
                error: function() {
                    $("#loader-overlay").fadeOut(200);
                    alert('Error fetching loan details.');
                }
            });
        } else {
            alert('Please enter a Ref No first.');
        }
    });

    $('button[type="submit"]').on('click', function () {
        $("#loader-overlay").css("display", "flex").hide().fadeIn(200);
    });
});
</script>

@endsection
