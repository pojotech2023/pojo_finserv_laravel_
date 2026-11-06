<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Aadhaar OTP - POJO FINSERV</title>
    
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
            max-width: 480px;
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
            <h3 class="fw-bold text-dark mb-1">Aadhaar OTP Verification</h3>
            <p class="text-muted small">POJO FINSERV - Customer Onboarding</p>
        </div>

        <div class="form-box">
            <h5 class="mb-3 text-center fw-bold" style="color: #0052cc;">Step 2: Enter 6-Digit OTP</h5>

            @if(!\App\Models\AppConfig::isLiveMode())
                <div class="alert alert-warning py-2 px-3 text-center rounded-3 mb-3" style="font-size: 13px;">
                    <i class="fas fa-flask me-1 text-dark"></i> <strong>Testing Mode Active:</strong><br>
                    Aadhaar API bypassed. Please enter sample OTP: <span class="badge bg-dark text-warning fs-6 px-2 py-1 mt-1">123456</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger rounded-3">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('aadhaar.verifyOtp', ['request_id' => $request_id]) }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="otp" class="form-label fw-semibold">Enter OTP Sent To Aadhaar Mobile</label>
                    <input type="text" name="otp" id="otp" maxlength="6" required class="form-control form-control-lg fs-6 text-center tracking-widest fw-bold" placeholder="123456" value="{{ !\App\Models\AppConfig::isLiveMode() ? '123456' : old('otp') }}">
                </div>

                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-lg rounded-pill fw-bold text-white shadow-sm" style="background: linear-gradient(135deg, #0052cc 0%, #008744 100%); border: 1px solid #f59e0b;">
                        Verify & Continue <i class="fas fa-arrow-right ms-2" style="color: #fbbf24;"></i>
                    </button>
                </div>
            </form>

            <form action="{{ route('aadhaar.resendOtp') }}" method="POST">
                @csrf
                <input type="hidden" name="request_id" value="{{ $request_id }}">

                <div class="d-grid">
                    <button type="button" id="resendBtn" class="btn btn-warning rounded-pill fw-bold" style="display:none; background: linear-gradient(135deg, #f59e0b, #d4af37); border: none;">
                        Resend OTP
                    </button>
                </div>

                <p id="timerText" class="text-danger text-center mt-3 fw-bold small mb-0"></p>
            </form>
        </div>
    </div>

    <!-- Unified Homepage Footer -->
    @include('footer')

    <script>
        let timer = 59;
        let timerInterval;

        function startTimer() {
            document.getElementById("resendBtn").style.display = "none";
            document.getElementById("timerText").innerHTML = "Resend OTP available in " + timer + "s";

            timerInterval = setInterval(() => {
                timer--;
                document.getElementById("timerText").innerHTML = "Resend OTP available in " + timer + "s";

                if (timer <= 0) {
                    clearInterval(timerInterval);
                    document.getElementById("timerText").innerHTML = "";
                    document.getElementById("resendBtn").style.display = "block";
                }
            }, 1000);
        }

        window.onload = startTimer;
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
