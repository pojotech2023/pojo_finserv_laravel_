<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gold Loan Bill Upload - POJO FINSERV</title>
    
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
            <h3 class="fw-bold text-dark mb-1">Upload Gold Loan Pledge Receipts</h3>
            <p class="text-muted small">POJO FINSERV - Process</p>
        </div>

        <div class="form-box">
            @if(session('success'))
                <div class="alert alert-success rounded-3">{{ session('success') }}</div>
            @endif

            <form action="{{ route('loan.upload.submit') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="kyc_id" value="{{ $kyc->id }}">

                <div class="mb-4">
                    <label for="gold_loan_bill" class="form-label fw-semibold">Select Gold Loan Pledge Receipt Files (Optional)</label>
                    <input type="file" name="gold_loan_bill[]" id="gold_loan_bill" class="form-control" multiple accept=".pdf,.jpg,.jpeg,.png">
                    <div class="form-text mt-2 small">You can upload multiple files if you have more than one pledge receipt.</div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-lg rounded-pill fw-bold text-white shadow-sm" style="background: linear-gradient(135deg, #0052cc, #003b95); border: 1px solid #d4af37;">
                        {{ ($kyc->service_type === 'sell_gold') ? 'Continue to Agreement' : 'Upload & Continue' }} <i class="fas fa-arrow-right ms-2" style="color: #fbbf24;"></i>
                    </button>
                    @if($kyc->service_type === 'sell_gold')
                        <div class="text-center mt-2">
                            <a href="{{ route('promissory.form', ['kyc_id' => $kyc->id]) }}" class="text-decoration-none small text-muted text-nowrap" style="font-size: 13px;">
                                No pledge receipts? <span class="text-danger fw-bold">Skip directly to Agreement &rarr;</span>
                            </a>
                        </div>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Unified Homepage Footer -->
    @include('footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
