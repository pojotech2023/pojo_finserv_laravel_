{{-- bankstatement/partials/loan.blade.php --}}
<div class="mt-4">
    <h5>Loan Details</h5>

    @if(isset($loan) && $loan)

        {{-- ===== Loan Info Table ===== --}}
        <div class="table-responsive">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th style="width: 30%;">Loan ID</th>
                        <td>{{ $loan->id }}</td>
                    </tr>
                    <tr>
                        <th>Bank Name</th>
                        <td>{{ $loan->bank_name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Staff Name</th>
                        <td>{{ $loan->staff_name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Customer Name</th>
                        <td>{{ $loan->customer_name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Interest Percentage</th>
                        <td>{{ $loan->percentage ?? 0 }}%</td>
                    </tr>
                    @if($loan->loan_date)
                        <tr>
                            <th>Loan Date</th>
                            <td>{{ \Carbon\Carbon::parse($loan->loan_date)->format('d-m-Y') }}</td>
                        </tr>
                    @endif
                    @if($loan->loan_amount)
                        <tr>
                            <th>Loan Amount</th>
                            <td>₹{{ number_format($loan->loan_amount, 2) }}</td>
                        </tr>
                    @endif
                    @if($loan->interest_period)
                        <tr>
                            <th>Interest Period</th>
                            <td>{{ $loan->period_count ?? '' }} {{ ucfirst($loan->interest_period ?? '') }}</td>
                        </tr>
                    @endif
                    @if($loan->ref_no)
                        <tr>
                            <th>Reference No</th>
                            <td>{{ $loan->ref_no }}</td>
                        </tr>
                    @endif
                    <tr>
                        <th>Approval Status</th>
                        <td>
                            @php $status = $loan->approval_status ?? 'pending'; @endphp
                            <span class="badge
                                {{ $status === 'approved' ? 'bg-success' : ($status === 'rejected' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                {{ ucfirst($status) }}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- ===== KYC Documents List ===== --}}
        <div class="mt-4">
            <h6 class="fw-bold"><i class="fas fa-folder-open"></i> KYC Documents</h6>

            {{-- Global alert for delete/upload --}}
            <div id="kyc-alert-{{ $loan->id }}" class="d-none mb-3"></div>

            <ul class="list-group mb-3" id="kyc-file-list-{{ $loan->id }}">
                @if($loan->files && $loan->files->count() > 0)
                    @foreach($loan->files as $file)
                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap gap-2"
                            id="kyc-item-{{ $file->id }}">
                            <span>
                                <i class="fas fa-file"></i>
                                {{ $file->filename ?? 'Document ' . $loop->iteration }}
                            </span>
                            <div class="d-flex gap-2">
                                <a href="{{ asset('storage/' . $file->filepath) }}"
                                   target="_blank"
                                   class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="{{ asset('storage/' . $file->filepath) }}"
                                   download
                                   class="btn btn-sm btn-success">
                                    <i class="fas fa-download"></i> Download
                                </a>
                                {{-- Delete Button --}}
                                <button type="button"
                                        class="btn btn-sm btn-danger kyc-delete-btn"
                                        data-file-id="{{ $file->id }}"
                                        data-loan-id="{{ $loan->id }}">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>
                        </li>
                    @endforeach
                @else
                    <li class="list-group-item text-muted" id="kyc-empty-msg-{{ $loan->id }}">
                        <i class="fas fa-info-circle"></i> No KYC documents uploaded yet.
                    </li>
                @endif
            </ul>

            {{-- ===== AJAX KYC Upload Card ===== --}}
            <div class="card border-primary mt-3">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-upload"></i> Upload KYC Document
                </div>
                <div class="card-body">

                    {{-- File input rows --}}
                    <div id="kyc-inputs-{{ $loan->id }}">
                        <div class="input-group mb-2 kyc-row">
                            <input type="file"
                                   class="form-control kyc-file-input"
                                   accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                            <button type="button"
                                    class="btn btn-outline-danger remove-kyc-row"
                                    title="Remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-2 align-items-center">
                        <button type="button"
                                id="add-kyc-btn-{{ $loan->id }}"
                                class="btn btn-secondary btn-sm">
                            <i class="fas fa-plus"></i> Add More
                        </button>
                        <button type="button"
                                id="upload-kyc-btn-{{ $loan->id }}"
                                class="btn btn-primary btn-sm">
                            <i class="fas fa-upload"></i> Upload
                        </button>
                        <span id="kyc-spinner-{{ $loan->id }}" class="d-none ms-2">
                            <span class="spinner-border spinner-border-sm text-primary" role="status"></span>
                            <span class="ms-1">Uploading...</span>
                        </span>
                    </div>

                </div>
            </div>
        </div>

        {{-- ===== Loan Documents (Aadhar, PAN, etc.) ===== --}}
        @php
            $loanDocuments = [
                'aadhar_front' => 'Aadhar Front',
                'aadhar_back'  => 'Aadhar Back',
                'pan_card'     => 'PAN Card',
                'live_photo'   => 'Live Photo',
                'cheque_leaf'  => 'Cheque Leaf',
            ];
            $hasLoanDocs = false;
        @endphp

        <div class="mt-4">
            <h6 class="fw-bold"><i class="fas fa-id-card"></i> Loan Documents</h6>
            <ul class="list-group">
                @foreach($loanDocuments as $field => $label)
                    @if(!empty($loan->$field))
                        @php $hasLoanDocs = true; @endphp
                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <span><i class="fas fa-file"></i> {{ $label }}</span>
                            <div class="d-flex gap-2">
                                <a href="{{ asset('storage/' . $loan->$field) }}"
                                   target="_blank"
                                   class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="{{ asset('storage/' . $loan->$field) }}"
                                   download
                                   class="btn btn-sm btn-success">
                                    <i class="fas fa-download"></i> Download
                                </a>
                            </div>
                        </li>
                    @endif
                @endforeach
            </ul>
            @if(!$hasLoanDocs)
                <div class="alert alert-secondary mt-2">
                    <i class="fas fa-info-circle"></i> No loan documents uploaded.
                </div>
            @endif
        </div>

        {{-- ===== Remarks Section ===== --}}
        <div class="mt-4">
            <h6 class="fw-bold"><i class="fas fa-comment-alt"></i> Remarks</h6>
            <form action="{{ route('loan.addRemarks', $loan->id) }}" method="POST">
                @csrf
                <div class="form-group">
                    <textarea name="remarks"
                              id="remarks-{{ $loan->id }}"
                              class="form-control"
                              rows="4"
                              placeholder="Enter your remarks here...">{{ $loan->remarks ?? '' }}</textarea>
                    <small class="form-text text-muted">Add any notes or comments about this loan.</small>
                </div>
                <button type="submit" class="btn btn-primary mt-2">
                    <i class="fas fa-save"></i> Save Remarks
                </button>
            </form>
        </div>

    @else
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i> No loan details found for this reference.
            <br>
            <small>The loan record may not be linked to this statement properly.</small>
        </div>
    @endif
</div>

{{-- ===== AJAX Script (Upload + Delete) ===== --}}
@if(isset($loan) && $loan)
<script>
(function () {
    var loanId      = {{ $loan->id }};
    var uploadUrl   = '{{ route('loan.uploadKyc', $loan->id) }}';
    var deleteBase  = '{{ url('loan/kyc') }}';   // /loan/kyc/{fileId}
    var csrfToken   = '{{ csrf_token() }}';

    var inputsWrap  = document.getElementById('kyc-inputs-'     + loanId);
    var addBtn      = document.getElementById('add-kyc-btn-'    + loanId);
    var uploadBtn   = document.getElementById('upload-kyc-btn-' + loanId);
    var spinner     = document.getElementById('kyc-spinner-'    + loanId);
    var alertBox    = document.getElementById('kyc-alert-'      + loanId);
    var fileList    = document.getElementById('kyc-file-list-'  + loanId);

    // ── Show alert ──────────────────────────────────────────────
    function showAlert(type, message) {
        alertBox.className = 'alert alert-' + type + ' mb-3';
        alertBox.innerHTML = message;
        alertBox.classList.remove('d-none');
        setTimeout(function () { alertBox.classList.add('d-none'); }, 5000);
    }

    // ── Append uploaded file to list ────────────────────────────
    function appendFileToList(fileId, filename, filepath) {
        var emptyMsg = document.getElementById('kyc-empty-msg-' + loanId);
        if (emptyMsg) emptyMsg.remove();

        var li = document.createElement('li');
        li.className = 'list-group-item d-flex justify-content-between align-items-center flex-wrap gap-2';
        li.id = 'kyc-item-' + fileId;
        li.innerHTML =
            '<span><i class="fas fa-file"></i> ' + filename + '</span>' +
            '<div class="d-flex gap-2">' +
                '<a href="' + filepath + '" target="_blank" class="btn btn-sm btn-primary">' +
                    '<i class="fas fa-eye"></i> View</a>' +
                '<a href="' + filepath + '" download class="btn btn-sm btn-success">' +
                    '<i class="fas fa-download"></i> Download</a>' +
                '<button type="button" class="btn btn-sm btn-danger kyc-delete-btn"' +
                    ' data-file-id="' + fileId + '" data-loan-id="' + loanId + '">' +
                    '<i class="fas fa-trash"></i> Delete</button>' +
            '</div>';
        fileList.appendChild(li);
    }

    // ── Upload single file ──────────────────────────────────────
    async function uploadOneFile(file) {
        var formData = new FormData();
        formData.append('kyc_file', file);
        formData.append('_token', csrfToken);

        var response = await fetch(uploadUrl, {
            method:  'POST',
            body:    formData,
            headers: { 'X-CSRF-TOKEN': csrfToken }
        });

        var data = await response.json();
        if (!response.ok || !data.success) {
            throw new Error(data.message || 'Upload failed for ' + file.name);
        }
        return data; // { success, message, filename, filepath, file_id }
    }

    // ── Delete KYC file ─────────────────────────────────────────
    async function deleteKycFile(fileId, btn) {
        if (!confirm('Do you want to delete this KYC document?')) return;

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

        try {
            var response = await fetch(deleteBase + '/' + fileId, {
                method:  'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept':       'application/json',
                    'Content-Type': 'application/json',
                },
            });

            var data = await response.json();

            if (!response.ok || !data.success) {
                throw new Error(data.message || 'Delete failed.');
            }

            // Remove list item from DOM
            var item = document.getElementById('kyc-item-' + fileId);
            if (item) item.remove();

            // Show empty message if no items left
            var remaining = fileList.querySelectorAll('li:not(.text-muted)');
            if (remaining.length === 0) {
                var emptyLi = document.createElement('li');
                emptyLi.className = 'list-group-item text-muted';
                emptyLi.id = 'kyc-empty-msg-' + loanId;
                emptyLi.innerHTML = '<i class="fas fa-info-circle"></i> No KYC documents uploaded yet.';
                fileList.appendChild(emptyLi);
            }

            showAlert('success', '<i class="fas fa-check-circle"></i> Document deleted successfully.');

        } catch (err) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-trash"></i> Delete';
            showAlert('danger', '<i class="fas fa-times-circle"></i> ' + err.message);
        }
    }

    // ── Delegate delete click on fileList ───────────────────────
    fileList.addEventListener('click', function (e) {
        var btn = e.target.closest('.kyc-delete-btn');
        if (!btn) return;
        var fileId = btn.getAttribute('data-file-id');
        deleteKycFile(fileId, btn);
    });

    // ── Add more rows ───────────────────────────────────────────
    addBtn.addEventListener('click', function () {
        var row = document.createElement('div');
        row.className = 'input-group mb-2 kyc-row';
        row.innerHTML =
            '<input type="file" class="form-control kyc-file-input" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">' +
            '<button type="button" class="btn btn-outline-danger remove-kyc-row" title="Remove">' +
            '<i class="fas fa-times"></i></button>';
        inputsWrap.appendChild(row);
    });

    // ── Remove row ──────────────────────────────────────────────
    inputsWrap.addEventListener('click', function (e) {
        var btn = e.target.closest('.remove-kyc-row');
        if (!btn) return;
        var rows = inputsWrap.querySelectorAll('.kyc-row');
        if (rows.length > 1) {
            btn.closest('.kyc-row').remove();
        } else {
            var inp = btn.closest('.kyc-row').querySelector('input[type="file"]');
            if (inp) inp.value = '';
        }
    });

    // ── Upload button ───────────────────────────────────────────
    uploadBtn.addEventListener('click', async function () {
        var inputs = inputsWrap.querySelectorAll('.kyc-file-input');
        var files  = [];
        inputs.forEach(function (inp) {
            if (inp.files && inp.files[0]) files.push(inp.files[0]);
        });

        if (files.length === 0) {
            showAlert('warning', '<i class="fas fa-exclamation-circle"></i> Please select at least one file.');
            return;
        }

        spinner.classList.remove('d-none');
        uploadBtn.disabled = true;
        addBtn.disabled    = true;

        var successCount = 0;
        var errors       = [];

        for (var i = 0; i < files.length; i++) {
            try {
                var result = await uploadOneFile(files[i]);
                // Pass file_id from response (add it in controller)
                appendFileToList(result.file_id ?? '', result.filename, result.filepath);
                successCount++;
            } catch (err) {
                errors.push(err.message);
            }
        }

        spinner.classList.add('d-none');
        uploadBtn.disabled = false;
        addBtn.disabled    = false;

        // Reset inputs
        inputsWrap.innerHTML =
            '<div class="input-group mb-2 kyc-row">' +
            '<input type="file" class="form-control kyc-file-input" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">' +
            '<button type="button" class="btn btn-outline-danger remove-kyc-row" title="Remove">' +
            '<i class="fas fa-times"></i></button></div>';

        if (errors.length === 0) {
            showAlert('success', '<i class="fas fa-check-circle"></i> ' + successCount + ' file(s) uploaded successfully.');
        } else if (successCount > 0) {
            showAlert('warning', '<i class="fas fa-exclamation-triangle"></i> ' + successCount + ' uploaded. Errors: ' + errors.join(', '));
        } else {
            showAlert('danger', '<i class="fas fa-times-circle"></i> Upload failed: ' + errors.join(', '));
        }
    });

})();
</script>
@endif