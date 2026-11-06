<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Investment Plans - POJO FINSERV</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; color: #334155; background-color: #f8fafc; }
        .page-header {
            background: linear-gradient(135deg, rgba(7, 35, 72, 0.95), rgba(0, 82, 204, 0.9)), 
                        url('https://images.unsplash.com/photo-1559526324-4b87b5e36e44?auto=format&fit=crop&w=1920&q=80') center/cover no-repeat;
            color: #ffffff;
            padding: 80px 0;
            text-align: center;
            border-bottom: 3px solid #d4af37;
        }
        .plan-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 40px 30px;
            box-shadow: 0 10px 30px rgba(0, 82, 204, 0.08);
            border: 2px solid #e2e8f0;
            transition: transform 0.3s ease;
        }
        .plan-card:hover { transform: translateY(-8px); border-color: #d4af37; }
    </style>
</head>
<body>

    @include('header')

    <section class="page-header">
        <div class="container">
            <h1 class="fw-bold display-5 mb-2" style="color: #fbbf24;">High-Return Investment Plans</h1>
            <p class="lead text-light mb-0">POJO FINSERV - Secure Capital & Guaranteed Growth Opportunities</p>
        </div>
    </section>

    <section class="py-5">
        <div class="container py-4">
            <div class="row g-4 justify-content-center">
                <div class="col-lg-4 col-md-6">
                    <div class="plan-card text-center">
                        <span class="badge text-white px-3 py-2 rounded-pill mb-3" style="background: linear-gradient(135deg, #0052cc, #003b95);">Popular Plan</span>
                        <h3 class="fw-bold text-dark mb-2">Monthly Income Plan</h3>
                        <p class="text-muted small">Ideal for regular cash flow with monthly payouts.</p>
                        <div class="display-6 fw-bold my-3" style="color: #0052cc;">12% - 15% <span class="fs-6 text-muted">/ p.a.</span></div>
                        
                        <ul class="list-unstyled text-start my-4 d-flex flex-column gap-2 small">
                            <li><i class="fas fa-check-circle me-2" style="color: #d97706;"></i> Monthly interest credited to bank account</li>
                            <li><i class="fas fa-check-circle me-2" style="color: #d97706;"></i> Guaranteed capital safety</li>
                            <li><i class="fas fa-check-circle me-2" style="color: #d97706;"></i> Stamped legal promissory note provided</li>
                            <li><i class="fas fa-check-circle me-2" style="color: #d97706;"></i> Minimum Tenure: 1 Year</li>
                        </ul>

                        <a href="{{ route('contact') }}" class="btn text-white w-100 rounded-pill fw-bold shadow-sm" style="background: linear-gradient(135deg, #0052cc, #003b95); border: 1px solid #d4af37;">Inquire Investment</a>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="plan-card text-center">
                        <span class="badge text-dark px-3 py-2 rounded-pill mb-3" style="background: linear-gradient(135deg, #f59e0b, #d4af37);">High Yield</span>
                        <h3 class="fw-bold text-dark mb-2">Growth Plan</h3>
                        <p class="text-muted small">Compounded annual growth for long-term wealth.</p>
                        <div class="display-6 fw-bold my-3" style="color: #d97706;">18% <span class="fs-6 text-muted">/ p.a.</span></div>
                        
                        <ul class="list-unstyled text-start my-4 d-flex flex-column gap-2 small">
                            <li><i class="fas fa-check-circle me-2" style="color: #d97706;"></i> Annual compounding payout</li>
                            <li><i class="fas fa-check-circle me-2" style="color: #d97706;"></i> Backed by gold collateral security</li>
                            <li><i class="fas fa-check-circle me-2" style="color: #d97706;"></i> 100% legal & transparent terms</li>
                            <li><i class="fas fa-check-circle me-2" style="color: #d97706;"></i> Minimum Tenure: 2 Years</li>
                        </ul>

                        <a href="{{ route('contact') }}" class="btn btn-warning w-100 rounded-pill fw-bold text-dark shadow-sm" style="background: linear-gradient(135deg, #f59e0b, #d4af37);">Inquire Investment</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- UNIFIED FOOTER -->
    @include('footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>