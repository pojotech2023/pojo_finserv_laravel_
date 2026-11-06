<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Financial Services - POJO FINSERV</title>
    
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
        .service-box {
            background: #ffffff;
            border-radius: 20px;
            padding: 30px 24px;
            box-shadow: 0 10px 30px rgba(0, 82, 204, 0.08);
            border: 1px solid #e2e8f0;
            height: 100%;
            transition: all 0.3s ease;
        }
        .service-box:hover {
            transform: translateY(-5px);
            border-color: #d4af37;
        }
    </style>
</head>
<body>

    @include('header')

    <section class="page-header">
        <div class="container">
            <h1 class="fw-bold display-5 mb-2" style="color: #fbbf24;">Our Financial Services</h1>
            <p class="lead text-light mb-0">POJO FINSERV - Direct Services & Loan Assistance Across Tamil Nadu</p>
        </div>
    </section>

    <!-- DIRECT SERVICES -->
    <section class="py-5 bg-white">
        <div class="container py-3">
            <div class="mb-4">
                <span class="badge px-3 py-2 text-dark fw-bold" style="background: linear-gradient(135deg, #f59e0b, #d4af37);">DIVISION 01</span>
                <h2 class="fw-bold text-dark mt-2">POJO FINSERV Direct Services</h2>
                <p class="text-muted">Direct gold finance and buying services with high valuation and complete security.</p>
            </div>

            <div class="row g-4">
                <div class="col-lg-4 col-md-6" id="gold-loan">
                    <div class="service-box">
                        <div class="fs-1 mb-3" style="color: #0052cc;"><i class="fas fa-coins"></i></div>
                        <h4 class="fw-bold text-dark">Gold Loan</h4>
                        <p class="text-muted small">Get financial assistance against eligible gold ornaments with transparent valuation, digital KYC, proper documentation, and convenient customer service.</p>
                        <a href="{{ url('/customerlogin') }}" class="btn btn-danger btn-sm rounded-pill px-3 fw-bold shadow-sm" style="background: linear-gradient(135deg, #0052cc, #003b95); border: 1px solid #d4af37;">Apply Now</a>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6" id="gold-buying">
                    <div class="service-box">
                        <div class="fs-1 mb-3" style="color: #0052cc;"><i class="fas fa-hand-holding-usd"></i></div>
                        <h4 class="fw-bold text-dark">Gold Buying</h4>
                        <p class="text-muted small">We purchase eligible gold from customers following applicable KYC, verification, and transaction procedures with transparent spot valuation.</p>
                        <a href="{{ route('contact') }}" class="btn btn-warning btn-sm rounded-pill px-3 fw-bold text-dark shadow-sm">Enquire Now</a>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6" id="gold-takeover">
                    <div class="service-box">
                        <div class="fs-1 mb-3" style="color: #0052cc;"><i class="fas fa-exchange-alt"></i></div>
                        <h4 class="fw-bold text-dark">Gold Loan Takeover Funding</h4>
                        <p class="text-muted small">Funding assistance for eligible customers looking to transfer or take over existing gold loans with leading banks and financial institutions.</p>
                        <a href="{{ route('contact') }}" class="btn btn-warning btn-sm rounded-pill px-3 fw-bold text-dark shadow-sm">Enquire Now</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- LOAN ASSISTANCE -->
    <section class="py-5" style="background: #f8fafc;">
        <div class="container py-3">
            <div class="mb-4">
                <span class="badge px-3 py-2 text-white fw-bold" style="background: linear-gradient(135deg, #0052cc, #003b95);">DIVISION 02</span>
                <h2 class="fw-bold text-dark mt-2">Loan Assistance & Financial Facilitation</h2>
                <p class="text-muted">Assistance for personal, business, home, vehicle, property, and education loans through authorized lending partners.</p>
            </div>

            <div class="row g-4">
                <div class="col-lg-4 col-md-6" id="personal-loan">
                    <div class="service-box">
                        <div class="fs-1 text-primary mb-3"><i class="fas fa-user-tag"></i></div>
                        <h4 class="fw-bold text-dark">Personal Loan</h4>
                        <p class="text-muted small">Financial assistance for eligible personal requirements through our lending partner network, subject to profile and lender approval.</p>
                        <a href="{{ url('/customerlogin') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold">Apply Now</a>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6" id="business-loan">
                    <div class="service-box">
                        <div class="fs-1 text-primary mb-3"><i class="fas fa-briefcase"></i></div>
                        <h4 class="fw-bold text-dark">Business Loan</h4>
                        <p class="text-muted small">Financial solutions for eligible businesses, entrepreneurs, and self-employed customers for working capital and growth requirements.</p>
                        <a href="{{ url('/customerlogin') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold">Apply Now</a>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6" id="home-loan">
                    <div class="service-box">
                        <div class="fs-1 text-primary mb-3"><i class="fas fa-home"></i></div>
                        <h4 class="fw-bold text-dark">Home Loan</h4>
                        <p class="text-muted small">Assistance for customers looking to purchase, construct, or finance an eligible residential property through suitable lending partners.</p>
                        <a href="{{ url('/customerlogin') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold">Apply Now</a>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6" id="car-loan">
                    <div class="service-box">
                        <div class="fs-1 text-primary mb-3"><i class="fas fa-car"></i></div>
                        <h4 class="fw-bold text-dark">New & Used Car Loans</h4>
                        <p class="text-muted small">Loan assistance for eligible customers purchasing a new or pre-owned vehicle, subject to vehicle eligibility and lender policies.</p>
                        <a href="{{ url('/customerlogin') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold">Apply Now</a>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6" id="property-loan">
                    <div class="service-box">
                        <div class="fs-1 text-primary mb-3"><i class="fas fa-building"></i></div>
                        <h4 class="fw-bold text-dark">Loan Against Property & Mortgage</h4>
                        <p class="text-muted small">Loan solutions against eligible residential, commercial, or approved property based on property valuation and lender eligibility criteria.</p>
                        <a href="{{ url('/customerlogin') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold">Apply Now</a>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6" id="education-loan">
                    <div class="service-box">
                        <div class="fs-1 text-primary mb-3"><i class="fas fa-graduation-cap"></i></div>
                        <h4 class="fw-bold text-dark">Education Loan</h4>
                        <p class="text-muted small">Loan assistance for eligible students and families for higher education requirements in India and abroad through leading lenders.</p>
                        <a href="{{ url('/service/education-loan') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold">View Details</a>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6" id="others-loan">
                    <div class="service-box">
                        <div class="fs-1 text-primary mb-3"><i class="fas fa-plus-circle"></i></div>
                        <h4 class="fw-bold text-dark">Others Loan</h4>
                        <p class="text-muted small">Tailored financial solutions and custom loans suited for unique personal, medical, agricultural, or commercial requirements.</p>
                        <a href="{{ url('/service/others-loan') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold">View Details</a>
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