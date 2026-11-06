@extends('layouts.app')
@section('content')
<div class="container">
    <h3>Loan Detail for Statement ID: {{ $statement->id }}</h3>

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

    <form action="{{ route('bank-statement.saveloan', $statement->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- STAFF NAME --}}
        <div class="mb-3 position-relative">
            <label>Staff Name</label>
            <input type="text" id="staff-name" name="staff_name" class="form-control" autocomplete="off" required
                value="{{ old('staff_name', $loan->staff_name ?? '') }}" placeholder="Type staff name...">
            <div id="staff-name-suggestions" class="list-group position-absolute w-100" style="z-index: 1000; top: 100%;"></div>
        </div>

        {{-- BANK NAME --}}
        <div class="mb-3 position-relative">
            <label>Bank Name</label>
            <input type="text" id="bank-name" name="bank_name" class="form-control" autocomplete="off" required
                value="{{ old('bank_name', $loan->bank_name ?? '') }}" placeholder="Type bank name...">
            <div id="bank-name-suggestions" class="list-group position-absolute w-100" style="z-index: 1000; top: 100%;"></div>
            <input type="hidden" name="ref_no" value="{{ $statement->ref_no }}">
            <input type="hidden" name="conform_ref_no" value="{{ $statement->ref_no }}">
            <input type="hidden" class="form-control" name="old_action_type" value="{{ $statement->action_type }}">
        </div>

        <div class="mb-3">
            <label>Customer Name</label>
            <input type="text" name="customer_name" class="form-control" required
                value="{{ old('customer_name', $loan->customer_name ?? '') }}">
        </div>

        <div class="mb-3">
            <label>Percentage (%)</label>
            <input type="text"  id="percentage" name="percentage" class="form-control" required
                value="{{ old('percentage', $loan->percentage ?? '') }}" min="0" max="100"
                placeholder="Enter interest rate (e.g. 25)">
        </div>

        <div class="mb-3 d-flex gap-2 align-items-center">
            <div style="flex:1;">
                <label>Number of Periods</label>
                <input type="number" id="period-count" name="period_count" class="form-control" min="1"
                    value="{{ old('period_count', $loan->period_count ?? 1) }}" required>
            </div>
            <div style="flex:1;">
                <label>Period Unit</label>
                <select name="period_unit" id="period-unit" class="form-control" required>
                    <option value="day"   {{ old('period_unit', $loan->period_unit ?? '') == 'day'   ? 'selected' : '' }}>Day</option>
                    <option value="week"  {{ old('period_unit', $loan->period_unit ?? '') == 'week'  ? 'selected' : '' }}>Week</option>
                    <option value="month" {{ old('period_unit', $loan->period_unit ?? '') == 'month' ? 'selected' : '' }}>Month</option>
                    <option value="year"  {{ old('period_unit', $loan->period_unit ?? '') == 'year'  ? 'selected' : '' }}>Year</option>
                </select>
            </div>
        </div>

        {{-- FIX: Display field (₹ formatted) + Hidden field (clean number to server) --}}
        <div class="mb-3">
            <label>Interest Amount</label>
            <input type="text" id="interest-amount-display" class="form-control" readonly
                value="{{ old('interest_amount', isset($loan->interest_amount) ? '₹'.number_format($loan->interest_amount, 2) : '') }}"
                placeholder="Interest will be calculated automatically">
            <input type="hidden" name="interest_amount" id="interest-amount"
                value="{{ old('interest_amount', $loan->interest_amount ?? '') }}">
        </div>

        {{-- ============================================================
             KYC DOCUMENTS (multiple)
        ============================================================ --}}
        <div class="mb-3">
            <label>KYC Documents (multiple)</label>
            <div id="kyc-file-inputs">
                <input type="file" name="kyc_files[]" class="form-control mb-2">
            </div>
            <button type="button" id="add-kyc-file" class="btn btn-secondary btn-sm">+ Add More</button>
        </div>

        {{-- ============================================================
             INDIVIDUAL DOCUMENT UPLOADS
        ============================================================ --}}
        <hr>
        <h6 class="text-muted mb-3"><i class="fas fa-id-card me-2"></i>KYC Document Uploads <span class="badge bg-secondary">Optional</span></h6>

        <div class="row g-3 mb-3">

            {{-- Aadhar Front --}}
            <div class="col-md-6">
                <label class="form-label">
                    <i class="fas fa-id-card me-1 text-primary"></i> Aadhaar Front
                </label>
                @if(!empty($loan->aadhar_front))
                    <div class="mb-1">
                        <a href="{{ asset('storage/' . $loan->aadhar_front) }}" target="_blank" class="btn btn-sm btn-outline-success">
                            <i class="fas fa-eye me-1"></i> View Uploaded
                        </a>
                    </div>
                @endif
                <input type="file" name="aadhar_front" class="form-control"
                       accept=".jpg,.jpeg,.png,.pdf">
                <small class="text-muted">JPG, PNG or PDF — max 5MB</small>
            </div>

            {{-- Aadhar Back --}}
            <div class="col-md-6">
                <label class="form-label">
                    <i class="fas fa-id-card me-1 text-primary"></i> Aadhaar Back
                </label>
                @if(!empty($loan->aadhar_back))
                    <div class="mb-1">
                        <a href="{{ asset('storage/' . $loan->aadhar_back) }}" target="_blank" class="btn btn-sm btn-outline-success">
                            <i class="fas fa-eye me-1"></i> View Uploaded
                        </a>
                    </div>
                @endif
                <input type="file" name="aadhar_back" class="form-control"
                       accept=".jpg,.jpeg,.png,.pdf">
                <small class="text-muted">JPG, PNG or PDF — max 5MB</small>
            </div>

            {{-- PAN Card --}}
            <div class="col-md-6">
                <label class="form-label">
                    <i class="fas fa-credit-card me-1 text-warning"></i> PAN Card
                </label>
                @if(!empty($loan->pan_card))
                    <div class="mb-1">
                        <a href="{{ asset('storage/' . $loan->pan_card) }}" target="_blank" class="btn btn-sm btn-outline-success">
                            <i class="fas fa-eye me-1"></i> View Uploaded
                        </a>
                    </div>
                @endif
                <input type="file" name="pan_card" class="form-control"
                       accept=".jpg,.jpeg,.png,.pdf">
                <small class="text-muted">JPG, PNG or PDF — max 5MB</small>
            </div>

            {{-- Live Photo --}}
            <div class="col-md-6">
                <label class="form-label">
                    <i class="fas fa-camera me-1 text-info"></i> Live Photo
                </label>
                @if(!empty($loan->live_photo))
                    <div class="mb-1">
                        <a href="{{ asset('storage/' . $loan->live_photo) }}" target="_blank" class="btn btn-sm btn-outline-success">
                            <i class="fas fa-eye me-1"></i> View Uploaded
                        </a>
                    </div>
                @endif
                <input type="file" name="live_photo" class="form-control"
                       accept=".jpg,.jpeg,.png">
                <small class="text-muted">JPG or PNG only — max 5MB</small>
            </div>

            {{-- Cheque Leaf --}}
            <div class="col-md-6">
                <label class="form-label">
                    <i class="fas fa-money-check me-1 text-success"></i> Cheque Leaf
                </label>
                @if(!empty($loan->cheque_leaf))
                    <div class="mb-1">
                        <a href="{{ asset('storage/' . $loan->cheque_leaf) }}" target="_blank" class="btn btn-sm btn-outline-success">
                            <i class="fas fa-eye me-1"></i> View Uploaded
                        </a>
                    </div>
                @endif
                <input type="file" name="cheque_leaf" class="form-control"
                       accept=".jpg,.jpeg,.png,.pdf">
                <small class="text-muted">JPG, PNG or PDF — max 5MB</small>
            </div>

        </div>
        <hr>

        {{-- UPLOADED LOAN FILES --}}
        <div class="mb-3">
            <label class="form-label">Uploaded Loan Files</label>
            @if($loan && $loan->files->count() > 0)
                <ul>
                    @foreach($loan->files as $file)
                        <li>
                            <a href="{{ asset('storage/' . $file->filepath) }}" target="_blank">
                                {{ $file->filename }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            @else
                <p>No attachments uploaded.</p>
            @endif
        </div>

        <button type="submit" class="btn btn-primary">Save Loan</button>
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
$(document).ready(function () {

    /* ADD MORE KYC FILES */
    $('#add-kyc-file').on('click', function () {
        $('#kyc-file-inputs').append('<input type="file" name="kyc_files[]" class="form-control mb-2">');
    });

    /* BANK NAME AUTOCOMPLETE */
    $('#bank-name').on('keyup', function () {
        let q = $(this).val().trim();
        if (q.length < 1) { $('#bank-name-suggestions').hide(); return; }
        $.get('{{ route("bank-name.autocomplete") }}', { q: q }, function (data) {
            let html = '';
            data.forEach(item => { html += `<a href="#" class="list-group-item list-group-item-action bank-item">${item}</a>`; });
            $('#bank-name-suggestions').html(html).show();
        });
    });
    $('#bank-name-suggestions').on('click', '.bank-item', function (e) {
        e.preventDefault();
        $('#bank-name').val($(this).text());
        $('#bank-name-suggestions').hide();
        $('#percentage').val('');
        $('#interest-amount-display').val('');
        $('#interest-amount').val('');
    });

    /* STAFF NAME AUTOCOMPLETE */
    $('#staff-name').on('keyup', function () {
        let q = $(this).val().trim();
        if (q.length < 1) { $('#staff-name-suggestions').hide(); return; }
        $.get('{{ route("staff-name.autocomplete") }}', { q: q }, function (data) {
            let html = '';
            if (data.length === 0) html = '<div class="list-group-item text-muted">No results found</div>';
            data.forEach(item => {
                html += `<a href="#" class="list-group-item list-group-item-action staff-item"
                            data-staff="${item.staff_name}" data-bank="${item.bank_name}" data-percentage="${item.percentage}">
                            <strong>${item.staff_name}</strong>
                            <small class="text-muted ms-2">— ${item.bank_name}</small>
                         </a>`;
            });
            $('#staff-name-suggestions').html(html).show();
        });
    });
    $('#staff-name-suggestions').on('click', '.staff-item', function (e) {
        e.preventDefault();
        $('#staff-name').val($(this).data('staff'));
        $('#bank-name').val($(this).data('bank'));
        $('#percentage').val($(this).data('percentage'));
        $('#staff-name-suggestions').hide();
        updateInterest();
    });

    /* ============================================================
       INTEREST CALCULATION
       - interest-amount-display : shows ₹ formatted value (readonly, visible)
       - interest-amount          : hidden field with clean number sent to server
    ============================================================ */
    function updateInterest() {
        let amount  = parseFloat($('#original-amount').val()) || 0;
        let percent = parseFloat($('#percentage').val()) || 0;

        if (amount > 0) {
            let interest = (amount * percent) / 100;
            // Display: ₹1,000.00  (human readable)
            $('#interest-amount-display').val('₹' + interest.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ','));
            // Hidden: 1000.00  (clean number → server)
            $('#interest-amount').val(interest.toFixed(2));
        } else {
            $('#interest-amount-display').val('');
            $('#interest-amount').val('');
        }
    }

    $('#percentage').on('input', updateInterest);

    // Run on page load if percentage already filled (edit mode)
    if ($('#percentage').val() !== '') {
        updateInterest();
    }

    /* CLOSE SUGGESTIONS OUTSIDE CLICK */
    $(document).on('click', function (e) {
        if (!$(e.target).closest('#staff-name, #staff-name-suggestions').length) $('#staff-name-suggestions').hide();
        if (!$(e.target).closest('#bank-name, #bank-name-suggestions').length) $('#bank-name-suggestions').hide();
    });

    /* LOADER ON SUBMIT */
    $('form').on('submit', function () {
        $("#loader-overlay").css("display", "flex").hide().fadeIn(200);
    });

});
</script>
@endsection