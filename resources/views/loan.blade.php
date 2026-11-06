<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gold Loan & Pledge Release - POJO FINSERV</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; color: #334155; background-color: #f8fafc; }
        .page-header {
            background: linear-gradient(135deg, rgba(7, 35, 72, 0.95), rgba(0, 82, 204, 0.9)), 
                        url('https://images.unsplash.com/photo-1610375461246-83df859d849d?auto=format&fit=crop&w=1920&q=80') center/cover no-repeat;
            color: #ffffff;
            padding: 80px 0;
            text-align: center;
            border-bottom: 3px solid #d4af37;
        }
    </style>
</head>
<body>

    @include('header')

    <section class="page-header">
        <div class="container">
            <h1 class="fw-bold display-5 mb-2" style="color: #fbbf24;">Gold Loans & Bank Pledge Release</h1>
            <p class="lead text-light mb-0">POJO FINSERV - Maximum Gram Rate & Instant Doorstep Service</p>
        </div>
    </section>

    <section class="py-5 bg-white">
        <div class="container py-4">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <img src="https://images.unsplash.com/photo-1610375461246-83df859d849d?auto=format&fit=crop&w=800&q=80" alt="Gold Loan" class="img-fluid rounded-4 shadow-lg border border-2 border-warning">
                </div>
                <div class="col-lg-6">
                    <span class="fw-bold text-uppercase" style="color: #0052cc; letter-spacing: 1px;">Gold Loan Partner</span>
                    <h2 class="display-6 fw-bold text-dark mt-2 mb-3">Release Your Pledged Gold Or Ornaments Instantly</h2>
                    <p class="text-secondary">Are your gold ornaments pledged in banks or private NBFCs at high interest rates? POJO FINSERV helps you release your pledged gold by paying off the principal amount and refinancing it at lower interest rates.</p>
                    
                    <div class="p-4 rounded-4 border my-4" style="background: #f8fafc; border-color: #e2e8f0 !important;">
                        <h5 class="fw-bold text-dark mb-2"><i class="fas fa-check-circle me-2" style="color: #d97706;"></i> Key Highlights</h5>
                        <ul class="list-unstyled mb-0 small text-muted d-flex flex-column gap-2">
                            <li>• Highest per-gram valuation for 22K/24K gold</li>
                            <li>• Instant online Customer Login & digital promissory note</li>
                            <li>• Safe doorstep collection & bank locker storage</li>
                        </ul>
                    </div>

                    <a href="{{ url('/customerlogin') }}" class="btn btn-lg rounded-pill fw-bold text-white shadow" style="background: linear-gradient(135deg, #0052cc, #003b95); border: 1px solid #d4af37;">Apply For Gold Loan Online</a>
                </div>
            </div>
        </div>
    </section>

    <!-- UNIFIED FOOTER -->
    @include('footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>