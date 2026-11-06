@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Investment Return - Statement ID: {{ $statement->id }}</h3>

    {{-- Show message if any --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Statement Details --}}
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
            <label class="form-label">Name</label>
            <input type="text" class="form-control" readonly value="{{ $statement->name }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Amount</label>
            <input type="text" class="form-control" readonly value="{{ number_format($statement->amount, 2) }}">
        </div>

    {{-- Investment Return Form --}}
    <form action="{{ route('investors.uploadKyc', $statement->id) }}" method="POST" enctype="multipart/form-data">
   @csrf

        <input type="hidden" name="action_type" value="INVESTMENT RETURN">

      {{-- Ref No Input --}}
<div class="mb-3">
    <label class="form-label">Ref No</label>
    <div class="input-group"  style="width: 400px;">
        <input type="text" id="ref_no" name="ref_no" class="form-control" placeholder="Enter Ref No">
        <span class="input-group-text">/R</span>
        <button type="button" id="search_btn" class="btn btn-primary">Search</button>
    </div>
</div>


{{-- Investor Name --}}
<div class="mb-3">
    <label class="form-label">Investor Name</label>
    <input type="text" id="investor_name" class="form-control" readonly>
</div>

{{-- Percentage --}}
<div class="mb-3">
    <label class="form-label">Percentage</label>
    <input type="text" id="percentage" class="form-control" readonly>
</div>

{{-- Investment Duration --}}
<div class="mb-3">
    <label class="form-label">Investment Duration</label>
    <input type="text" id="duration" class="form-control" readonly>
</div>

{{-- Interest Amount --}}
<div class="mb-3">
    <label class="form-label">Interest Amount</label>
    <input type="text" id="interest_amount" class="form-control" readonly>
</div>

{{-- Interest Balance --}}
<div class="mb-3">
    <label class="form-label">Interest Balance</label>
    <input type="text" id="interest_balance" class="form-control" readonly>
</div>

{{-- Name Match Message --}}
<div class="mb-3">
    <span id="name_check_message" class="fw-bold"></span>
</div>
{{-- File Upload (hidden initially) --}}
<div class="mb-3" id="kyc_upload_div" style="display: none;">
    <label class="form-label">Upload KYC Document</label>
   <input type="file" name="kyc_file" class="form-control">
</div>
{{-- File Upload (hidden initially) --}}
<div class="mb-3" id="kyc_preview_div" style="display: none;">
    <label class="form-label">KYC Document</label>
    <div id="kyc_preview_content"></div>
</div>

<button type="submit" class="btn btn-primary">Save Loan</button>
        <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
    </form>
    <!-- Loader Overlay -->
<!-- Loader Overlay -->
<div id="loader-overlay" style="
    display:none;
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(255,255,255,0.8);
    z-index:9999;
    justify-content:center;
    align-items:center;
    font-size:20px;
    font-weight:bold;
    color:#333;
">
    Loading...
</div>

</div>

</div>

<script>
document.getElementById('search_btn').addEventListener('click', function () {
    let refNo = document.getElementById('ref_no').value.trim();

    if (refNo !== '') {
        // Show loader
        document.getElementById('loader-overlay').style.display = 'flex';

        fetch(`/get-investor-by-ref/${refNo}`)
            .then(response => response.json())
            .then(data => {
                // Hide loader
                document.getElementById('loader-overlay').style.display = 'none';

                if (data.success && data.investor) {
                    document.getElementById('investor_name').value = data.investor.name ?? '';
                    document.getElementById('percentage').value = data.investor.percentage ?? '';
                    document.getElementById('duration').value = data.investor.investment_duration ?? '';
                    document.getElementById('interest_amount').value = data.investor.interest_amount ?? '';
                    document.getElementById('interest_balance').value = data.investor.interest_balance ?? '';

                    // Show KYC preview
                    let kycDiv = document.getElementById('kyc_preview_div');
                    let kycContent = document.getElementById('kyc_preview_content');
                    kycContent.innerHTML = '';

                    if (data.investor.kyc_file_path) {
                        let filePath = `/storage/${data.investor.kyc_file_path}`;
                        let ext = filePath.split('.').pop().toLowerCase();

                        if (['jpg', 'jpeg', 'png', 'gif'].includes(ext)) {
                            kycContent.innerHTML = `<img src="${filePath}" alt="KYC Document" class="img-thumbnail" style="max-width: 300px;">`;
                        } else if (ext === 'pdf') {
                            kycContent.innerHTML = `<a href="${filePath}" target="_blank" class="btn btn-info btn-sm">View PDF</a>`;
                        } else {
                            kycContent.innerHTML = `<a href="${filePath}" target="_blank" class="btn btn-secondary btn-sm">Download File</a>`;
                        }
                        kycDiv.style.display = 'block';
                    } else {
                        kycDiv.style.display = 'none';
                    }

                    // Name match check
                    let msgElem = document.getElementById('name_check_message');
                    msgElem.classList.remove('text-success', 'text-danger');

                    if (data.nameMatch) {
                        msgElem.innerHTML = "✅ Name Match";
                        msgElem.classList.add('text-success');
                        document.getElementById('kyc_upload_div').style.display = 'none';
                    } else {
                        msgElem.innerHTML = "❌ Name not match — Please upload KYC.";
                        msgElem.classList.add('text-danger');
                        document.getElementById('kyc_upload_div').style.display = 'block';
                    }
                } else {
                    alert('Investor not found for this Ref No');
                }
            })
            .catch(error => {
                document.getElementById('loader-overlay').style.display = 'none';
                console.error('Error fetching investor:', error);
                alert('Something went wrong while fetching investor details.');
            });
    } else {
        alert('Please enter a Ref No before searching.');
    }
});


</script>

@endsection
