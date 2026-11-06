<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Login & Verification - POJO FINSERV</title>
    
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

        .terms-box {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.04);
            border: 1px solid #e2e8f0;
        }
    </style>
</head>
<body class="bg-light">

    <!-- Top Header with ONLY Logo (No Navbar Menus) -->
    <div class="customer-header-bar">
        <div class="container d-flex justify-content-center align-items-center">
            <a href="{{ route('index') }}" class="d-flex align-items-center gap-2 text-decoration-none">
                <img src="{{ asset('img/logo.png') }}" alt="POJO FINSERV Logo" class="logo-img" onerror="this.src='{{ asset('images/logo.png') }}';">
            </a>
        </div>
    </div>

    <div class="container py-5">
        <div class="text-center mb-4">
            <h3 class="fw-bold text-dark mb-1">Customer Login & Verification</h3>
            <p class="text-muted small">POJO FINSERV - Digitized Onboarding</p>
        </div>

        <div class="form-box">
            <div class="alert border-0 rounded-3 mb-4" style="font-size: 13.5px; background-color: #f8fafc; color: #0052cc; border: 1px solid #e2e8f0 !important;">
                <i class="fas fa-info-circle me-1" style="color: #d97706;"></i> <strong>Upload Guidelines:</strong><br>
                Files must be less than <strong>2 MB</strong>. Allowed types: <strong>jpg, jpeg, png, pdf</strong>.
            </div>

            <div class="text-center mb-3">
                @if(session('service_type', request('service', 'takeover')) === 'sell_gold')
                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold fs-6 shadow-sm"><i class="fas fa-coins me-1"></i> Service: Sell Your Gold</span>
                @else
                    <span class="badge text-white px-3 py-2 rounded-pill fw-bold fs-6 shadow-sm" style="background-color: #0052cc;"><i class="fas fa-hand-holding-usd me-1"></i> Service: Gold Take Over</span>
                @endif
            </div>

            <h5 class="mb-3 text-center fw-bold" style="color: #0052cc;">Step 1: Enter Aadhaar Number</h5>

            @if(!\App\Models\AppConfig::isLiveMode())
                <div class="alert alert-warning py-2 px-3 text-center rounded-3 mb-3" style="font-size: 13px;">
                    <i class="fas fa-flask me-1 text-dark"></i> <strong>Testing Mode Active:</strong><br>
                    Aadhaar API bypassed. Enter any 12-digit number (e.g. <code>123456789012</code>) to test.
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger rounded-3">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ url('/aadhaar/send-otp') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="service_type" value="{{ session('service_type', request('service', 'takeover')) }}">

                <div class="mb-3">
                    <label for="aadhaar_number" class="form-label fw-semibold">Aadhaar Number</label>
                    <input type="text" name="aadhaar_number" id="aadhaar_number" maxlength="12" class="form-control form-control-lg fs-6" placeholder="Enter 12-digit Aadhaar number" value="{{ !\App\Models\AppConfig::isLiveMode() ? '123456789012' : old('aadhaar_number') }}">
                </div>

                <div class="mb-3">
                    <label for="aadhar_front" class="form-label fw-semibold">Upload Aadhaar Front Page (Optional)</label>
                    <input type="file" name="aadhar_front" id="aadhar_front" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                </div>

                <div class="mb-4">
                    <label for="aadhar_back" class="form-label fw-semibold">Upload Aadhaar Back Page (Optional)</label>
                    <input type="file" name="aadhar_back" id="aadhar_back" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-lg rounded-pill fw-bold text-white shadow-sm" style="background: linear-gradient(135deg, #0052cc 0%, #008744 100%); border: 1px solid #f59e0b;">
                        Proceed to OTP / Next Step <i class="fas fa-arrow-right ms-2" style="color: #fbbf24;"></i>
                    </button>
                </div>
            </form>
        </div>

        <div class="container max-w-lg mt-4 mb-4">
            <div class="terms-box p-4" style="max-width: 600px; margin: 0 auto;">
                <h6 class="text-center mb-3 fw-bold text-dark">
                    <i class="fas fa-user-shield me-2" style="color: #d97706;"></i> Terms & Authorization
                </h6>

                <p class="text-muted small mb-3 text-center px-2 fw-medium">
                    I hereby provide my consent to POJO FINSERV to contact me from time to time for marketing, promotional, product/service-related information, offers, updates and other business communications.
                </p>

                <div class="p-3 rounded-3 text-center border" style="background: #f8fafc; border-color: #e2e8f0 !important;">
                    <h6 class="fw-bold text-dark mb-1"><i class="fas fa-headset me-2" style="color: #0052cc;"></i> POJO FINSERV Customer Support</h6>
                    <p class="mb-1 small">Address: <strong>D.No- 02/628, Mahil Nagar, Athappampalayam, Mel thindal, Erode - 638012</strong></p>
                    <p class="mb-1 small">Mobile / WhatsApp: <a href="tel:9994777004" class="fw-bold text-decoration-none" style="color: #0052cc;">9994777004</a></p>
                    <p class="mb-0 small">Email: <a href="mailto:Pojofinserv@outlook.com" class="fw-bold text-decoration-none" style="color: #0052cc;">Pojofinserv@outlook.com</a></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Unified Homepage Footer (Red & Gold) -->
    @include('footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
