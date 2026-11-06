<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Details - POJO FINSERV</title>
    
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
            max-width: 580px;
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
            <h3 class="fw-bold text-dark mb-1">Enter Loan Details & Terms</h3>
            <p class="text-muted small">POJO FINSERV - Onboarding Process</p>
        </div>

        <div class="form-box">
            @if ($errors->any())
                <div class="alert alert-danger rounded-3">{{ $errors->first() }}</div>
            @endif

            <form action="{{ route('aadhaar.amount') }}" method="POST">
                @csrf
                <input type="hidden" name="kyc_id" value="{{ $kyc->id }}">

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Borrower Full Name</label>
                        <input type="text" name="full_name" class="form-control" value="{{ old('full_name', $kyc->full_name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Care Of (S/o, D/o, W/o)</label>
                        <input type="text" name="care_of" class="form-control" value="{{ old('care_of', $kyc->care_of) }}" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Full Address</label>
                        <textarea name="address" class="form-control" rows="2" required>{{ old('address', is_array(json_decode($kyc->address, true)) ? implode(', ', json_decode($kyc->address, true)) : $kyc->address) }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Required Amount (₹)</label>
                        <input type="number" name="amount" class="form-control" value="{{ old('amount', $kyc->amount) }}" required placeholder="e.g. 50000">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Contact Phone Number</label>
                        <input type="tel" name="phone" class="form-control" value="{{ old('phone', $kyc->phone) }}" required placeholder="10-digit mobile">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Referral Name</label>
                        <input type="text" name="referel_name" class="form-control" value="{{ old('referel_name', $kyc->referel_name) }}" required placeholder="Referral agent name">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Expected Repayment Date</label>
                        <input type="date" class="form-control bg-light" value="{{ date('Y-m-d') }}" readonly style="pointer-events: none;">
                        <input type="hidden" name="repayment_date" value="{{ date('Y-m-d') }}">
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Repayment Time Preference</label>
                        <input type="time" name="repayment_time" class="form-control" value="{{ old('repayment_time', '20:00') }}" required>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" name="action" value="verify" class="btn btn-lg w-100 rounded-pill fw-bold text-white shadow-sm" style="background: linear-gradient(135deg, #0052cc, #003b95); border: 1px solid #d4af37;">
                        Proceed to Digital Promissory Note <i class="fas fa-arrow-right ms-2" style="color: #fbbf24;"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Unified Homepage Footer -->
    @include('footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
