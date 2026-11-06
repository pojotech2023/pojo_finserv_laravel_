<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You - POJO FINSERV</title>
    
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

        .thankyou-box {
            width: 100%;
            max-width: 540px;
            margin: 0 auto;
            padding: 45px;
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 82, 204, 0.1);
            border: 2px solid #e2e8f0;
            text-align: center;
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
        <div class="thankyou-box">
            <div class="mb-4 fs-1" style="color: #0052cc;">
                <i class="fas fa-check-circle" style="font-size: 4rem;"></i>
            </div>
            
            <h2 class="fw-bold text-dark mb-2">Thank You!</h2>
            <p class="lead text-secondary mb-4">Your Customer Login & Verification details have been submitted successfully.</p>
            
            <div class="p-3 rounded-3 border mb-4 text-start small" style="background: #f8fafc; border-color: #e2e8f0 !important;">
                <p class="fw-bold text-dark mb-1"><i class="fas fa-info-circle me-1" style="color: #d97706;"></i> What happens next?</p>
                <p class="text-muted mb-0">Our POJO FINSERV executive will review your details and contact you at <strong>9994777004</strong> for doorstep gold loan appraisal and disbursement.</p>
            </div>

            <a href="{{ route('index') }}" class="btn btn-lg rounded-pill px-5 fw-bold text-white shadow-sm" style="background: linear-gradient(135deg, #0052cc, #003b95); border: 1px solid #d4af37;">
                Return to Homepage <i class="fas fa-home ms-2" style="color: #fbbf24;"></i>
            </a>
        </div>
    </div>

    <!-- Unified Homepage Footer -->
    @include('footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
