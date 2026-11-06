<div class="container">
    

        <div class="form-wrapper" id="form-wrapper">
            <div class="capture">
            <div class="text-center my-4">
            <div class="d-flex align-items-center mb-4">
                <div class="flex-shrink-0">
                    <img src="{{ asset('images/logo.png') }}" alt="Client Logo" class="logo me-3" style="max-height: 100px;">
                </div>
                <div class="flex-grow-1 text-start">
                    <h3 class="mb-1">PROMISSORY NOTE</h3>
                    <h6 class="text-muted mb-1">(Money Lending Agreement)</h6>
                    <p class="fw-bold mb-1">POJO FINSERV</p>
                    <p class="mb-0">(A Licensed Private Financier under Tamil Nadu Money Lenders Act)</p>
                    <p>Money Lender Licence No: TN-1920250331107</p>
                </div>
                 @if($kyc->eye_blink_image)
        <div class="flex-shrink-0 ms-3">
            <img src="{{ asset($kyc->eye_blink_image) }}"
                 alt="Borrower Photo"
                 style="height:100px; width:100px; object-fit:cover; border:1px solid #ccc; border-radius:6px;margin-right:120px">
        </div>
    @endif
            </div>
            </div>
            <form method="POST" action="{{ route('promissory.store') }}">
                @csrf
                <input type="hidden" name="kyc_id" value="{{ $kyc->id }}">

                <p>Date: 
                    <input type="date" name="sign_date" value="{{ $kyc->created_at->format('Y-m-d') }}" class="border-input" style="width: 180px;">
                </p>

                <p>
                    I, Mr./Ms. 
                    <input type="text" name="name" value="{{ $kyc->full_name }}" class="border-input" style="width: 250px;">,
                
                    <input type="text" name="parent" value="{{ $kyc->care_of }}" class="border-input" style="width: 250px">,
                    residing at 
                    @php
        $address = json_decode($kyc->address, true);
    @endphp
                    <input type="text" name="address"  value="{{ 
            ($address['house'] ?? '') . ', ' . 
            ($address['street'] ?? '') . ', ' . 
            ($address['vtc'] ?? '') . ', ' . 
            ($address['dist'] ?? '') . ' ' . 
            ($address['post'] ?? '') . ', ' . 
            ($address['state'] ?? '') . ', ' . 
            ($address['country'] ?? '')
        }} {{ $kyc->zip . '. '}}" class="border-input" style="width: 700px;">,
                    
                </p>

                <p>
                    have this day borrowed a sum of:  
                    ₹ <input type="number" name="amount" value="{{ $kyc->amount }}" class="border-input" style="width: 150px;"> 
                    
                    @if (session('amountInWords'))
                    (Rupees
                <input type="text" name="amount_words" value="{{ session('amountInWords') }}  only" class="border-input" style="width: 300px;">)
                @endif
                    from POJO FINSERV, a licensed private financier, for my personal/business use.
                </p>

                <p>
                    I hereby agree to repay the entire loan amount in full on or before  
                    <input type="date" name="repayment_date" value="{{ $kyc->repayment_date }}" class="border-input" style="width: 160px;">, 
                    by 
                    <input type="text" name="repayment_time"  value="{{ $kyc->repayment_time }}" class="border-input" style="width: 100px;"> , as per the commitment given.
                </p>

                <p>
                    The interest amount, terms, and any applicable charges communicated by the Referrer person shall be final and binding.
                </p>

                <p>
                    I understand and agree that any delay or default in repayment shall make me liable for legal action under:
                    <ul>
                        <li>The Tamil Nadu Money Lenders Act, 1957,</li>
                        <li>Section 138 of the Negotiable Instruments Act, 1881, and</li>
                        <li>Sections 406 and 420 of the Indian Penal Code (IPC), for breach of financial trust and fraudulent intention.</li>
                    </ul>
                </p>

                <p>
                    This is a voluntary borrowing, and I fully acknowledge responsibility for the use and repayment of the loaned amount.
                    I shall not hold POJO FINSERV or its representatives accountable for the usage or consequences of the borrowed funds.
                </p>

            <h5 class="mt-4">Borrower Details</h5>
    <div class="row mb-3">
        <div class="col-md-6">
            <p>Phone:</p>
           <span> <input type="text" name="phone" value="{{ $kyc->phone }}" class="border-input w-100"></span>
        </div>
        <div class="col-md-6">
            <p>Aadhaar / ID No:</p>
            <input type="text" name="aadhaar" value="{{ $kyc->aadhaar_number }}" class="border-input w-100">
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <p>Borrower Name:</p>
            <input type="text" name="signature" value="{{ $kyc->full_name }}" class="border-input w-100">
        </div>
        <div class="col-md-6">
        <div class="col-md-6">
        <label>borrow Signature:</label>
       <div class="signature-wrapper" style="width: 300px; height: 50px; border-bottom: 1px solid #000; position: relative;">
        @php
            $bSigSrc = '';
            if (!empty($kyc->borrower_signature)) {
                if (str_starts_with($kyc->borrower_signature, 'data:image')) {
                    $bSigSrc = $kyc->borrower_signature;
                } elseif (file_exists(storage_path('app/public/' . $kyc->borrower_signature))) {
                    $bSigSrc = asset('storage/' . $kyc->borrower_signature);
                }
            }
        @endphp
        @if($bSigSrc)
            <img src="{{ $bSigSrc }}" style="position: absolute; top: 0; left: 0; max-width: 100%; max-height: 100%; display: block;" onerror="this.style.display='none';">
        @endif
       </div>
  <div class="mt-3" style="font-size: 12px; color: #333;" id="borrower_meta">
    <p><strong>IP:</strong> <span id="ip_borrower">Loading...</span></p>
    <p><strong>Date & Time:</strong> <span id="time_borrower"></span></p>
</div>

                    </div>
    </div>

    <h5 class="mt-4">Lender (POJO FINSERV)</h5>

    <div class="row mb-3">
        {{-- Left side: Authorized Person Name --}}
        <div class="col-md-6">
            <label class="fw-bold">Authorized Person Name:</label>
            <input type="text" name="authorized" value="S MEENA KUMARI" class="border-input" style="width: 250px;">
        </div>

        {{-- Right side: Lender Signature --}}
        <div class="col-md-6 text-end">
            <label class="fw-bold">Lender Signature:</label><br>
            <img src="{{ asset('images/sign.png') }}" alt="Lender Signature" style="height: 100px;">
            <input type="hidden" name="lender_signature" value="lender-signature.png">
        </div>
    </div>

    {{-- Stamp below or wherever you like --}}
   

             <h5 class="" style="margin-top:-47px">Witness (Referrer Person)</h5>
<p>
    I, the undersigned, confirm that I have referred the above borrower. I accept full responsibility to monitor and follow up the repayment process, and I will ensure the borrower repays the loan amount in full to POJO FINSERV. I understand that I will be contacted, responsible and held accountable in case of any delay or issues in repayment.
</p>

<div class="row mb-3">
    <!-- Left side: Name -->
    <div class="col-md-6">
        <label>Name:</label>
        <input type="text" name="witness1" value="{{ $kyc->referel_name }}" class="border-input w-100">
    </div>

    <!-- Right side: Signature -->
    <div class="col-md-6">
        <label>Witness Signature:</label>
        <div class="signature-wrapper" style="width: 100%; height: 50px; border-bottom: 1px solid #000; position: relative;">
        @php
            $wSigSrc = '';
            if (!empty($kyc->witness_signature)) {
                if (str_starts_with($kyc->witness_signature, 'data:image')) {
                    $wSigSrc = $kyc->witness_signature;
                } elseif (file_exists(storage_path('app/public/' . $kyc->witness_signature))) {
                    $wSigSrc = asset('storage/' . $kyc->witness_signature);
                }
            }
        @endphp
        @if($wSigSrc)
            <img src="{{ $wSigSrc }}" style="position: absolute; top: 0; left: 0; max-width: 100%; max-height: 100%; display: block;" onerror="this.style.display='none';">
        @endif
        </div>
    <div class="mt-3" style="font-size: 12px; color: #333;" id="witness_meta">
    <p><strong>IP:</strong> <span id="ip_witness">Loading...</span></p>
    <p><strong>Date & Time:</strong> <span id="time_witness"></span></p>
</div>
        </div>
        
</div>
                    </div>

                 </form>
                 
             
        </div>
    </div>
      
         