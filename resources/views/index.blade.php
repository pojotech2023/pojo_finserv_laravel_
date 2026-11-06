<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POJO FINSERV - Financial Solutions. Simplified. | Tamil Nadu</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #334155;
            background-color: #f8fafc;
            overflow-x: hidden;
        }

        /* Red & Gold Modern Gradient Hero */
        .hero-section {
            background: linear-gradient(135deg, rgba(7, 35, 72, 0.94) 0%, rgba(0, 82, 204, 0.9) 100%), 
                        url('https://images.unsplash.com/photo-1610375461246-83df859d849d?auto=format&fit=crop&w=1920&q=80') center/cover no-repeat;
            color: #ffffff;
            padding: 90px 0 110px 0;
            position: relative;
        }

        .hero-badge {
            background: rgba(245, 158, 11, 0.2);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(245, 158, 11, 0.5);
            color: #fbbf24;
            padding: 8px 18px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
        }

        .hero-title {
            font-size: 3.2rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 18px;
        }

        .hero-subtitle {
            font-size: 1.15rem;
            color: #e2e8f0;
            margin-bottom: 30px;
            max-width: 620px;
            line-height: 1.6;
        }

        .btn-custom-primary {
            background: linear-gradient(135deg, #0052cc 0%, #008744 100%);
            color: #ffffff;
            padding: 14px 30px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1rem;
            border: 2px solid #f59e0b;
            box-shadow: 0 10px 25px rgba(0, 82, 204, 0.4);
            transition: all 0.3s ease;
        }

        .btn-custom-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(212, 175, 55, 0.4);
            color: #fbbf24;
        }

        .btn-custom-gold {
            background: linear-gradient(135deg, #f59e0b 0%, #d4af37 100%);
            color: #061a38;
            padding: 14px 30px;
            border-radius: 50px;
            font-weight: 800;
            font-size: 1rem;
            border: none;
            box-shadow: 0 10px 25px rgba(245, 158, 11, 0.4);
            transition: all 0.3s ease;
        }

        .btn-custom-gold:hover {
            transform: translateY(-3px);
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            color: #000000;
        }

        /* Stat Counter Card */
        .stat-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 25px 20px;
            box-shadow: 0 15px 35px rgba(0, 82, 204, 0.08);
            border: 2px solid #e2e8f0;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            border-color: #d4af37;
        }

        .stat-number {
            font-size: 2.2rem;
            font-weight: 800;
            color: #0052cc;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #475569;
            font-weight: 600;
            font-size: 0.9rem;
        }

        /* Service Cards */
        .service-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 30px 24px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.04);
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
            height: 100%;
        }

        .service-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px rgba(0, 82, 204, 0.12);
            border-color: #d4af37;
        }

        .service-icon-box {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            background: #f8fafc;
            color: #0052cc;
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .service-card:hover .service-icon-box {
            background: linear-gradient(135deg, #0052cc, #003b95);
            color: #fbbf24;
            border-color: #d4af37;
        }

        /* Step Card */
        .step-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
            height: 100%;
            transition: transform 0.3s ease;
        }
        .step-card:hover {
            transform: translateY(-5px);
            border-color: #d4af37;
        }
        .step-number {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: linear-gradient(135deg, #0052cc, #003b95);
            color: #fbbf24;
            border: 2px solid #d4af37;
            font-weight: 800;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }

        /* FAQ Accordion Styling */
        .accordion-button:not(.collapsed) {
            color: #0052cc;
            background-color: #f8fafc;
            box-shadow: none;
            font-weight: 700;
        }
        .accordion-button:focus {
            box-shadow: none;
            border-color: #e2e8f0;
        }
    </style>
</head>
<body>

    <!-- Top & Main Header -->
    @include('header')

    <!-- HERO SECTION -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <div class="hero-badge">
                        <i class="fas fa-map-marked-alt"></i> Serving Across Tamil Nadu
                    </div>
                    <h1 class="hero-title">Financial Solutions. Simplified.</h1>
                    <p class="hero-subtitle">Fast, Transparent & Customer-Focused Financial Services Across Tamil Nadu. POJO FINSERV provides a wide range of financial solutions designed to meet the needs of individuals, families, professionals, self-employed customers and businesses.</p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ url('/customerlogin') }}" class="btn btn-custom-primary">
                            <i class="fas fa-paper-plane me-2" style="color: #fbbf24;"></i> Apply Now
                        </a>
                        <a href="{{ route('contact') }}" class="btn btn-custom-gold">
                            <i class="fas fa-headset me-2"></i> Contact Us
                        </a>
                    </div>
                </div>
                <div class="col-lg-5 mt-5 mt-lg-0">
                    <div class="card border-0 shadow-lg rounded-4 overflow-hidden" style="background: rgba(255, 255, 255, 0.98); border: 2px solid #d4af37 !important;">
                        <div class="card-header text-white p-3 text-center" style="background: linear-gradient(135deg, #0052cc, #003b95);">
                            <h4 class="fw-bold mb-0" style="color: #fbbf24;"><i class="fas fa-clipboard-check me-2"></i> Quick Enquiry Form</h4>
                        </div>
                        <div class="card-body p-4 text-dark">
                            <p class="text-muted small mb-3 text-center">Share your financial requirement & get immediate guidance.</p>
                            
                            <form action="{{ route('quick.enquiry.submit') }}" method="POST">
                                @csrf

                                @if(session('quick_success'))
                                    <div class="alert alert-success py-2 px-3 small rounded-3 mb-3 d-flex align-items-center">
                                        <i class="fas fa-check-circle me-2 fs-5"></i>
                                        <div>{{ session('quick_success') }}</div>
                                    </div>
                                @endif

                                @if($errors->any())
                                    <div class="alert alert-danger py-2 px-3 small rounded-3 mb-3">
                                        <ul class="mb-0 ps-3">
                                            @foreach($errors->all() as $err)
                                                <li>{{ $err }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-secondary small">Customer Name</label>
                                    <input type="text" name="name" class="form-control fs-6" placeholder="Your Full Name" value="{{ old('name') }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-secondary small">Requirement Amount (₹)</label>
                                    <input type="number" name="amount" class="form-control fs-6" placeholder="e.g. 2,00,000" value="{{ old('amount') }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-secondary small">Contact Mobile Number</label>
                                    <input type="tel" name="phone" class="form-control fs-6" placeholder="Your 10-digit mobile number" value="{{ old('phone') }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-secondary small">Financial Service Required</label>
                                    <select name="service_required" class="form-select fs-6" required>
                                        <optgroup label="Direct Financial Services">
                                            <option value="Gold Loan" {{ old('service_required') == 'Gold Loan' ? 'selected' : '' }}>Gold Loan</option>
                                            <option value="Gold Buying" {{ old('service_required') == 'Gold Buying' ? 'selected' : '' }}>Gold Buying</option>
                                            <option value="Gold Loan Transfer / Takeover Funding" {{ old('service_required') == 'Gold Loan Transfer / Takeover Funding' ? 'selected' : '' }}>Gold Loan Transfer / Takeover Funding</option>
                                        </optgroup>
                                        <optgroup label="Loan Assistance">
                                            <option value="Personal Loan" {{ old('service_required') == 'Personal Loan' ? 'selected' : '' }}>Personal Loan</option>
                                            <option value="Business Loan" {{ old('service_required') == 'Business Loan' ? 'selected' : '' }}>Business Loan</option>
                                            <option value="Home Loan" {{ old('service_required') == 'Home Loan' ? 'selected' : '' }}>Home Loan</option>
                                            <option value="New / Used Car Loan" {{ old('service_required') == 'New / Used Car Loan' ? 'selected' : '' }}>New / Used Car Loan</option>
                                            <option value="Loan Against Property (LAP)" {{ old('service_required') == 'Loan Against Property (LAP)' ? 'selected' : '' }}>Loan Against Property (LAP)</option>
                                            <option value="Mortgage Loan" {{ old('service_required') == 'Mortgage Loan' ? 'selected' : '' }}>Mortgage Loan</option>
                                            <option value="Education Loan" {{ old('service_required') == 'Education Loan' ? 'selected' : '' }}>Education Loan</option>
                                        </optgroup>
                                    </select>
                                </div>
                                <button type="submit" class="btn w-100 py-3 rounded-3 fw-bold fs-6 shadow text-white" style="background: linear-gradient(135deg, #0052cc, #003b95); border: 1px solid #d4af37;">
                                    Submit Requirement <i class="fas fa-arrow-right ms-2" style="color: #fbbf24;"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- STATS OVERVIEW SECTION -->
    <section class="py-5" style="margin-top: -40px; position: relative; z-index: 10;">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="stat-number"><i class="fas fa-city me-1"></i> TN</div>
                        <div class="stat-label">Tamil Nadu Wide Coverage</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="stat-number">10+</div>
                        <div class="stat-label">Financial Services</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="stat-number">100%</div>
                        <div class="stat-label">Transparent Process</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="stat-number">Fast</div>
                        <div class="stat-label">Verification & Processing</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- DIVISION 1: DIRECT FINANCIAL SERVICES -->
    <section class="py-5 bg-white" id="direct-services">
        <div class="container py-3">
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge px-3 py-2 text-dark fw-bold" style="background: linear-gradient(135deg, #f59e0b, #d4af37);">POJO FINSERV DIRECT</span>
            </div>
            <h2 class="display-6 fw-bold text-dark mb-2">Gold Finance & Direct Services</h2>
            <p class="text-secondary lead mb-5">Direct gold financial services with transparent valuation, proper documentation, and secure customer handling.</p>

            <div class="row g-4">
                <!-- Gold Loan -->
                <div class="col-lg-4 col-md-6">
                    <div class="service-card">
                        <div class="service-icon-box">
                            <i class="fas fa-coins"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-3">Gold Loan</h4>
                        <p class="text-muted small mb-4">Get financial assistance against eligible gold ornaments with transparent valuation, digital KYC, proper documentation, and convenient customer service.</p>
                        <ul class="list-unstyled small text-secondary mb-4 d-flex flex-column gap-2">
                            <li><i class="fas fa-check text-success me-2"></i> Transparent gold valuation</li>
                            <li><i class="fas fa-check text-success me-2"></i> Quick processing & digital KYC</li>
                            <li><i class="fas fa-check text-success me-2"></i> Secure documentation & vaulting</li>
                        </ul>
                        <a href="{{ url('/customerlogin') }}" class="btn btn-outline-danger btn-sm rounded-pill fw-bold px-3">Know More <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>

                <!-- Gold Buying -->
                <div class="col-lg-4 col-md-6">
                    <div class="service-card">
                        <div class="service-icon-box">
                            <i class="fas fa-hand-holding-usd"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-3">Gold Buying</h4>
                        <p class="text-muted small mb-4">We purchase eligible gold from customers following applicable KYC, verification, and transaction procedures with immediate valuation clarity.</p>
                        <ul class="list-unstyled small text-secondary mb-4 d-flex flex-column gap-2">
                            <li><i class="fas fa-check text-success me-2"></i> Instant spot valuation</li>
                            <li><i class="fas fa-check text-success me-2"></i> Complete verification compliance</li>
                            <li><i class="fas fa-check text-success me-2"></i> Smooth customer experience</li>
                        </ul>
                        <a href="{{ route('contact') }}" class="btn btn-outline-danger btn-sm rounded-pill fw-bold px-3">Enquire Now <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>

                <!-- Gold Loan Transfer / Takeover Funding -->
                <div class="col-lg-4 col-md-6">
                    <div class="service-card">
                        <div class="service-icon-box">
                            <i class="fas fa-exchange-alt"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-3">Gold Loan Takeover Funding</h4>
                        <p class="text-muted small mb-4">Funding assistance for eligible customers looking to transfer or take over existing gold loans with leading banks and financial institutions.</p>
                        <ul class="list-unstyled small text-secondary mb-4 d-flex flex-column gap-2">
                            <li><i class="fas fa-check text-success me-2"></i> Release pledge from banks/NBFCs</li>
                            <li><i class="fas fa-check text-success me-2"></i> Full documentation assistance</li>
                            <li><i class="fas fa-check text-success me-2"></i> Clear funding process</li>
                        </ul>
                        <a href="{{ route('contact') }}" class="btn btn-outline-danger btn-sm rounded-pill fw-bold px-3">Enquire Now <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- DIVISION 2: LOAN ASSISTANCE & FINANCIAL SOLUTIONS -->
    <section class="py-5" style="background: #f8fafc;" id="loan-assistance">
        <div class="container py-3">
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge px-3 py-2 text-white fw-bold" style="background: linear-gradient(135deg, #0052cc, #003b95);">PARTNER FACILITATION</span>
            </div>
            <h2 class="display-6 fw-bold text-dark mb-2">Loan Assistance & Financial Solutions</h2>
            <p class="text-secondary lead mb-5">Connecting salaried professionals, self-employed customers, and businesses across Tamil Nadu with suitable lending partners.</p>

            <div class="row g-4">
                <!-- Personal Loan -->
                <div class="col-lg-4 col-md-6">
                    <div class="service-card">
                        <div class="service-icon-box text-primary">
                            <i class="fas fa-user-tag"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-2">Personal Loan</h4>
                        <p class="text-muted small mb-3">Financial assistance for eligible personal requirements through our lending partner network based on profile and lender policy.</p>
                        <a href="{{ url('/customerlogin') }}" class="fw-bold text-decoration-none" style="color: #0052cc;">Apply Now <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>

                <!-- Business Loan -->
                <div class="col-lg-4 col-md-6">
                    <div class="service-card">
                        <div class="service-icon-box text-primary">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-2">Business Loan</h4>
                        <p class="text-muted small mb-3">Solutions for eligible businesses, entrepreneurs and self-employed individuals to support growth, working capital, and expansion.</p>
                        <a href="{{ url('/customerlogin') }}" class="fw-bold text-decoration-none" style="color: #0052cc;">Apply Now <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>

                <!-- Home Loan -->
                <div class="col-lg-4 col-md-6">
                    <div class="service-card">
                        <div class="service-icon-box text-primary">
                            <i class="fas fa-home"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-2">Home Loan</h4>
                        <p class="text-muted small mb-3">Assistance for customers purchasing, constructing, or refinancing residential property through appropriate lending partners.</p>
                        <a href="{{ url('/customerlogin') }}" class="fw-bold text-decoration-none" style="color: #0052cc;">Apply Now <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>

                <!-- Car Loan (New & Used) -->
                <div class="col-lg-4 col-md-6">
                    <div class="service-card">
                        <div class="service-icon-box text-primary">
                            <i class="fas fa-car"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-2">New & Used Car Loans</h4>
                        <p class="text-muted small mb-3">Financial facilitation for eligible customers purchasing new pre-owned vehicles subject to vehicle and customer profile eligibility.</p>
                        <a href="{{ url('/customerlogin') }}" class="fw-bold text-decoration-none" style="color: #0052cc;">Apply Now <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>

                <!-- Loan Against Property -->
                <div class="col-lg-4 col-md-6">
                    <div class="service-card">
                        <div class="service-icon-box text-primary">
                            <i class="fas fa-building"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-2">Loan Against Property</h4>
                        <p class="text-muted small mb-3">Loan assistance against eligible residential, commercial, or approved property for higher-value personal or business needs.</p>
                        <a href="{{ url('/customerlogin') }}" class="fw-bold text-decoration-none" style="color: #0052cc;">Apply Now <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>

                <!-- Education Loan -->
                <div class="col-lg-4 col-md-6">
                    <div class="service-card">
                        <div class="service-icon-box text-primary">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-2">Education Loan</h4>
                        <p class="text-muted small mb-3">Loan assistance for eligible students and families for higher education requirements in India and abroad.</p>
                        <a href="{{ url('/customerlogin') }}" class="fw-bold text-decoration-none" style="color: #0052cc;">Apply Now <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- WHY CHOOSE POJO FINSERV SECTION -->
    <section class="py-5 bg-white" id="why-choose-us">
        <div class="container py-3">
            <div class="text-center max-w-2xl mx-auto mb-5">
                <span class="fw-bold text-uppercase" style="color: #0052cc; letter-spacing: 1px;">Why POJO FINSERV</span>
                <h2 class="display-6 fw-bold text-dark mt-1">One Stop Solution for All Your Financial Needs</h2>
                <p class="text-muted">Why customers across Tamil Nadu place their trust in POJO FINSERV.</p>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="p-4 rounded-4 border border-warning h-100 bg-light">
                        <div class="text-danger fs-3 mb-2"><i class="fas fa-boxes" style="color: #0052cc;"></i></div>
                        <h5 class="fw-bold text-dark">Wide Product Range</h5>
                        <p class="text-muted small mb-0">From Gold finance and gold buying to personal, business, home, vehicle, property, and education loans under one platform.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 rounded-4 border border-warning h-100 bg-light">
                        <div class="text-danger fs-3 mb-2"><i class="fas fa-handshake" style="color: #0052cc;"></i></div>
                        <h5 class="fw-bold text-dark">Transparent Process</h5>
                        <p class="text-muted small mb-0">Clear communication regarding eligibility, documentation requirements, charges, and applicable terms before proceeding.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 rounded-4 border border-warning h-100 bg-light">
                        <div class="text-danger fs-3 mb-2"><i class="fas fa-map-marked-alt" style="color: #0052cc;"></i></div>
                        <h5 class="fw-bold text-dark">Tamil Nadu Coverage</h5>
                        <p class="text-muted small mb-0">Serving customers across major districts and locations in Tamil Nadu through our service network and partner ecosystem.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 rounded-4 border border-warning h-100 bg-light">
                        <div class="text-danger fs-3 mb-2"><i class="fas fa-user-heart" style="color: #0052cc;"></i></div>
                        <h5 class="fw-bold text-dark">Customer-Focused Service</h5>
                        <p class="text-muted small mb-0">We understand your requirement and actively help you identify the most suitable option matching your profile.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 rounded-4 border border-warning h-100 bg-light">
                        <div class="text-danger fs-3 mb-2"><i class="fas fa-laptop-code" style="color: #0052cc;"></i></div>
                        <h5 class="fw-bold text-dark">Digital & Convenient</h5>
                        <p class="text-muted small mb-0">Technology-enabled customer onboarding, digital KYC verification, and fast communication wherever applicable.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 rounded-4 border border-warning h-100 bg-light">
                        <div class="text-danger fs-3 mb-2"><i class="fas fa-shield-alt" style="color: #0052cc;"></i></div>
                        <h5 class="fw-bold text-dark">Responsible & Compliant</h5>
                        <p class="text-muted small mb-0">We adhere to proper documentation, verification, and procedural standards for all customer requests.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- HOW IT WORKS SECTION (6-STEP WORKFLOW) -->
    <section class="py-5" style="background: #f8fafc;" id="how-it-works">
        <div class="container py-3">
            <div class="text-center max-w-2xl mx-auto mb-5">
                <span class="fw-bold text-uppercase" style="color: #0052cc; letter-spacing: 1px;">Step-by-Step</span>
                <h2 class="display-6 fw-bold text-dark mt-1">How It Works</h2>
                <p class="text-muted">A clear, transparent 6-step process from enquiry to disbursement.</p>
            </div>

            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="step-card">
                        <div class="step-number">01</div>
                        <h5 class="fw-bold text-dark">Tell Us Your Requirement</h5>
                        <p class="text-muted small mb-0">Share your financial requirement, amount needed, and preferred product with our team.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="step-card">
                        <div class="step-number">02</div>
                        <h5 class="fw-bold text-dark">Customer & Document Verification</h5>
                        <p class="text-muted small mb-0">Our team collects required basic information and documents based on your selected service.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="step-card">
                        <div class="step-number">03</div>
                        <h5 class="fw-bold text-dark">Eligibility Assessment</h5>
                        <p class="text-muted small mb-0">We assess your requirement and identify suitable available financial options.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="step-card">
                        <div class="step-number">04</div>
                        <h5 class="fw-bold text-dark">Lender / Product Selection</h5>
                        <p class="text-muted small mb-0">Where applicable, we connect you with the appropriate lending partner based on eligibility.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="step-card">
                        <div class="step-number">05</div>
                        <h5 class="fw-bold text-dark">Application & Processing</h5>
                        <p class="text-muted small mb-0">The application is processed systematically as per applicable lender or process guidelines.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="step-card">
                        <div class="step-number">06</div>
                        <h5 class="fw-bold text-dark">Approval & Disbursement</h5>
                        <p class="text-muted small mb-0">Upon approval by the respective lender or authorized process, funds are disbursed promptly.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SERVICE AREA & WHO WE SERVE -->
    <section class="py-5 bg-white">
        <div class="container py-3">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6">
                    <span class="fw-bold text-uppercase" style="color: #0052cc; letter-spacing: 1px;"><i class="fas fa-users me-1"></i> Who We Serve</span>
                    <h3 class="fw-bold text-dark display-6 mt-1 mb-3">Serving Diverse Needs Across Tamil Nadu</h3>
                    <p class="text-muted mb-4">POJO FINSERV provides financial assistance and loan facilitation for:</p>
                    
                    <div class="row g-2 font-weight-semibold text-secondary small">
                        <div class="col-6"><i class="fas fa-check-circle text-success me-2"></i> Salaried Professionals</div>
                        <div class="col-6"><i class="fas fa-check-circle text-success me-2"></i> Self-Employed Individuals</div>
                        <div class="col-6"><i class="fas fa-check-circle text-success me-2"></i> Business Owners</div>
                        <div class="col-6"><i class="fas fa-check-circle text-success me-2"></i> Entrepreneurs</div>
                        <div class="col-6"><i class="fas fa-check-circle text-success me-2"></i> Families & Property Owners</div>
                        <div class="col-6"><i class="fas fa-check-circle text-success me-2"></i> Students & Parents</div>
                        <div class="col-6"><i class="fas fa-check-circle text-success me-2"></i> Vehicle Buyers</div>
                        <div class="col-6"><i class="fas fa-check-circle text-success me-2"></i> Gold Finance Customers</div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="p-4 rounded-4 text-white shadow-lg" style="background: linear-gradient(135deg, #061a38 0%, #0052cc 100%); border: 2px solid #d4af37;">
                        <h4 class="fw-bold mb-2" style="color: #fbbf24;"><i class="fas fa-map-marker-alt me-2"></i> Our Service Area Across Tamil Nadu</h4>
                        <p class="small text-light mb-3">POJO FINSERV aims to provide financial services and loan facilitation across major locations in Tamil Nadu, including:</p>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <span class="badge bg-white text-dark">Chennai</span>
                            <span class="badge bg-white text-dark">Coimbatore</span>
                            <span class="badge bg-white text-dark">Erode</span>
                            <span class="badge bg-white text-dark">Tiruppur</span>
                            <span class="badge bg-white text-dark">Salem</span>
                            <span class="badge bg-white text-dark">Madurai</span>
                            <span class="badge bg-white text-dark">Trichy</span>
                            <span class="badge bg-white text-dark">Karur</span>
                            <span class="badge bg-white text-dark">Namakkal</span>
                            <span class="badge bg-white text-dark">Hosur</span>
                            <span class="badge bg-white text-dark">Vellore</span>
                            <span class="badge bg-white text-dark">Thanjavur</span>
                            <span class="badge bg-white text-dark">Tirunelveli</span>
                            <span class="badge bg-white text-dark">Dindigul</span>
                            <span class="badge bg-warning text-dark">+ Other Districts</span>
                        </div>
                        <p class="small mb-0 text-white-50">* Service delivery subject to location eligibility & partner coverage.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FREQUENTLY ASKED QUESTIONS (FAQS) -->
    <section class="py-5" style="background: #f8fafc;" id="faqs">
        <div class="container py-3 max-w-3xl">
            <div class="text-center mb-5">
                <span class="fw-bold text-uppercase" style="color: #0052cc; letter-spacing: 1px;">Got Questions?</span>
                <h2 class="display-6 fw-bold text-dark mt-1">Frequently Asked Questions</h2>
                <p class="text-muted">Clear answers to common questions about POJO FINSERV services.</p>
            </div>

            <div class="accordion shadow-sm rounded-4 overflow-hidden" id="faqAccordion">
                <!-- FAQ 1 -->
                <div class="accordion-item border-0 border-bottom">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button py-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            What financial services does POJO FINSERV provide?
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-secondary small">
                            POJO FINSERV provides Gold Loans, Gold Buying, and Gold Loan Transfer/Takeover Funding, along with assistance for Personal Loans, Business Loans, Home Loans, New and Used Car Loans, Loan Against Property, Mortgage Loans, and Education Loans.
                        </div>
                    </div>
                </div>

                <!-- FAQ 2 -->
                <div class="accordion-item border-0 border-bottom">
                    <h2 class="accordion-header" id="headingTwo">
                        <button class="accordion-button collapsed py-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Does POJO FINSERV provide services across Tamil Nadu?
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-secondary small">
                            Yes. POJO FINSERV provides services across Tamil Nadu through its service network and applicable lending partner ecosystem.
                        </div>
                    </div>
                </div>

                <!-- FAQ 3 -->
                <div class="accordion-item border-0 border-bottom">
                    <h2 class="accordion-header" id="headingThree">
                        <button class="accordion-button collapsed py-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            Can I transfer my existing gold loan?
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-secondary small">
                            Eligible customers may receive assistance for Gold Loan Transfer/Takeover Funding, subject to applicable lender/product terms and eligibility assessment.
                        </div>
                    </div>
                </div>

                <!-- FAQ 4 -->
                <div class="accordion-item border-0 border-bottom">
                    <h2 class="accordion-header" id="headingFour">
                        <button class="accordion-button collapsed py-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            Do you provide personal and business loans?
                        </button>
                    </h2>
                    <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-secondary small">
                            Yes. POJO FINSERV assists eligible customers with Personal and Business Loan requirements through authorized lending partners.
                        </div>
                    </div>
                </div>

                <!-- FAQ 5 -->
                <div class="accordion-item border-0 border-bottom">
                    <h2 class="accordion-header" id="headingFive">
                        <button class="accordion-button collapsed py-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                            Do you provide both new and used car loans?
                        </button>
                    </h2>
                    <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-secondary small">
                            Yes. We provide financial facilitation for eligible New Car and Used Car Loan requirements based on vehicle and applicant eligibility.
                        </div>
                    </div>
                </div>

                <!-- FAQ 6 -->
                <div class="accordion-item border-0 border-bottom">
                    <h2 class="accordion-header" id="headingSix">
                        <button class="accordion-button collapsed py-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                            What documents are required?
                        </button>
                    </h2>
                    <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-secondary small">
                            Documents vary depending on the financial product, customer profile, and lender requirements. Typically, standard KYC (Aadhaar, PAN) and income/property/vehicle/business-related documents are required.
                        </div>
                    </div>
                </div>

                <!-- FAQ 7 -->
                <div class="accordion-item border-0">
                    <h2 class="accordion-header" id="headingSeven">
                        <button class="accordion-button collapsed py-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                            Is loan approval guaranteed?
                        </button>
                    </h2>
                    <div id="collapseSeven" class="accordion-collapse collapse" aria-labelledby="headingSeven" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-secondary small">
                            No. Loan approval is subject to customer eligibility, documentation verification, credit assessment, lender policies, and final approval by the respective lender.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CALL TO ACTION BANNER -->
    <section class="py-5 text-white" style="background: linear-gradient(135deg, #061a38, #0052cc); border-top: 3px solid #d4af37; border-bottom: 3px solid #d4af37;">
        <div class="container text-center py-4">
            <h2 class="display-6 fw-bold mb-3" style="color: #fbbf24;">Have a Financial Requirement?</h2>
            <p class="lead text-light mb-4 max-w-xl mx-auto">Tell us what you need. Our team across Tamil Nadu will help you understand available options.</p>
            
            <div class="d-flex flex-wrap justify-content-center gap-3">
                <a href="{{ url('/customerlogin') }}" class="btn btn-custom-gold px-4 py-3">
                    <i class="fas fa-user-edit me-2"></i> Submit Online Enquiry
                </a>
                <a href="tel:9994777004" class="btn btn-outline-light btn-lg px-4 py-3 rounded-pill fw-bold" style="border: 2px solid #d4af37; color: #fbbf24;">
                    <i class="fas fa-phone-alt me-2"></i> Call Us: 9994777004
                </a>
            </div>
        </div>
    </section>

    <!-- UNIFIED FOOTER -->
    @include('footer')

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>