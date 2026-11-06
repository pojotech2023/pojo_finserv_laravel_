<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAN Card Upload - POJO FINSERV</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8fafc;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #334155;
        }

        .customer-header-bar {
            background: #072348;
            padding: 15px 0;
            border-bottom: 3px solid #d4af37;
            text-align: center;
        }

        .logo-img {
            max-height: 75px;
            width: auto;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        .form-box {
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
            padding: 35px;
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 82, 204, 0.1);
            border: 2px solid #e2e8f0;
        }
    </style>
</head>
<body class="bg-light">

    <!-- Top Header with ONLY Logo -->
    <div class="customer-header-bar">
        <div class="container d-flex justify-content-center align-items-center">
            <a href="{{ route('index') }}" class="d-flex align-items-center gap-2 text-decoration-none">
                <img src="{{ asset('img/logo.png') }}" alt="POJO FINSERV Logo" class="logo-img" onerror="this.src='{{ asset('images/logo.png') }}';">
            </a>
        </div>
    </div>

    <div class="container py-5">
        <div class="text-center mb-4">
            <h3 class="fw-bold text-dark mb-1">PAN Card Verification</h3>
            <p class="text-muted small">POJO FINSERV - Customer Onboarding</p>
        </div>

        <div class="form-box">
            <h5 class="mb-3 text-center fw-bold" style="color: #0052cc;">Upload PAN Card</h5>

            @if(session('success'))
                <div class="alert alert-success rounded-3">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger rounded-3">{{ $errors->first() }}</div>
            @endif

            @if(!\App\Models\AppConfig::isLiveMode())
                <div class="alert alert-warning py-2 px-3 text-center rounded-3 mb-3" style="font-size: 13px;">
                    <i class="fas fa-flask me-1 text-dark"></i> <strong>Testing Mode Active:</strong><br>
                    PAN verification API bypassed. Enter any sample PAN card number (e.g. <code>ABCDE1234F</code>) and click <strong>Verify PAN</strong> to auto-verify!
                </div>
            @endif

            <div class="alert alert-info mt-2" style="font-size: 13px; padding: 10px 14px; border-radius: 10px;">
                <strong>Upload Guidelines:</strong><br>
                Files must be less than <strong>2 MB</strong>.<br>
                Allowed types: <strong>jpg, jpeg, png, pdf</strong>.
            </div>

            <!-- PAN Verification Section -->
            <div class="mb-3">
                <label for="pan_number" class="form-label fw-semibold">PAN Card Number</label>
                <div class="input-group">
                    <input type="text" name="pan_number" id="pan_number" class="form-control form-control-lg fs-6 text-uppercase" placeholder="e.g. ABCDE1234F" maxlength="10" value="{{ !\App\Models\AppConfig::isLiveMode() ? 'ABCDE1234F' : '' }}">
                    <button type="button" id="verifyBtn" class="btn fw-bold text-white" style="background-color: #0052cc;" onclick="submitVerifyPan()">
                        Verify PAN
                    </button>
                </div>
                <div id="verifySuccess" class="alert alert-success text-center d-none mt-2 py-2">✅ PAN Verified Successfully</div>
                <div id="verifyError" class="alert alert-danger text-center d-none mt-2 py-2"></div>
            </div>

            <form id="panForm" action="{{ route('panimage.upload.submit') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="kyc_id" value="{{ $kyc_id ?? $kyc->id }}">

                <div class="mb-4">
                    <label for="pan_card" class="form-label fw-semibold">Upload PAN Card Image / PDF</label>
                    <input type="file" name="pan_card_image" id="pan_card" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-lg rounded-pill fw-bold text-white shadow-sm" style="background: linear-gradient(135deg, #0052cc 0%, #008744 100%); border: 1px solid #f59e0b;">
                        Upload & Continue <i class="fas fa-arrow-right ms-2" style="color: #fbbf24;"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Unified Homepage Footer -->
    @include('footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function submitVerifyPan() {
            const pan = document.getElementById('pan_number').value.trim().toUpperCase();
            const kycId = document.querySelector('input[name="kyc_id"]').value;
            const verifyBtn = document.getElementById('verifyBtn');
            const verifySuccess = document.getElementById('verifySuccess');
            const verifyError = document.getElementById('verifyError');

            if (!pan || pan.length !== 10) {
                verifyError.textContent = "Please enter a valid 10-character PAN number.";
                verifyError.classList.remove('d-none');
                verifySuccess.classList.add('d-none');
                return;
            }

            verifyBtn.disabled = true;
            verifyBtn.innerText = 'Verifying...';

            fetch("{{ route('pan.upload.submit') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value,
                    "Content-Type": "application/json",
                    "Accept": "application/json"
                },
                body: JSON.stringify({
                    kyc_id: kycId,
                    pan_number: pan
                }),
            })
            .then(res => res.json())
            .then(data => {
                verifyBtn.disabled = false;
                verifyBtn.innerText = 'Verify PAN';
                if (data.success) {
                    verifySuccess.classList.remove('d-none');
                    verifyError.classList.add('d-none');
                    document.getElementById('pan_number').readOnly = true;
                    verifyBtn.classList.add('d-none');
                } else {
                    verifyError.textContent = data.message || 'PAN verification failed.';
                    verifyError.classList.remove('d-none');
                    verifySuccess.classList.add('d-none');
                }
            })
            .catch(err => {
                verifyBtn.disabled = false;
                verifyBtn.innerText = 'Verify PAN';
                verifyError.textContent = 'PAN verification error. Please try again.';
                verifyError.classList.remove('d-none');
                verifySuccess.classList.add('d-none');
            });
        }
    </script>
</body>
</html>
