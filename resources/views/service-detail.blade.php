<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Service Details - POJO FINSERV</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"> 

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --brand-brown: #072348;
            --brand-brown-light: #003b95;
            --brand-gold: #f59e0b;
            --brand-gold-yellow: #fbbf24;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #334155;
            background-color: #fdfbf7;
        }
        .loan-hero {
            background: linear-gradient(135deg, rgba(54, 0, 0, 0.92), rgba(107, 0, 0, 0.88)), url('https://images.unsplash.com/photo-1610375461246-83df859d849d?auto=format&fit=crop&w=1920&q=80') center/cover no-repeat;
            color: #fff;
            padding: 50px 0 40px 0;
            border-bottom: 3px solid var(--brand-gold);
        }
        .feature-card {
            border-top: 4px solid var(--brand-gold);
            background: #ffffff;
            padding: 18px 15px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(54, 0, 0, 0.06);
            transition: all 0.3s ease;
            height: 100%;
        }
        .feature-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 22px rgba(54, 0, 0, 0.12);
        }
        .sidebar-product-item {
            padding: 10px 14px !important; /* Compact padding to reduce huge gaps */
            font-size: 0.88rem;
            font-weight: 500;
            color: #334155;
            border-left: 3px solid transparent;
            transition: all 0.2s ease;
        }
        .sidebar-product-item:hover, .sidebar-product-item.active-item {
            background-color: #fff8eb !important;
            color: #003b95 !important;
            font-weight: 700;
            border-left-color: var(--brand-gold) !important;
        }
    </style>
</head>

<body>
    <!-- Header Start -->
    @include('header')
    <!-- Header End -->

    @php
        $services = [
            'gold-loan' => [
                'title' => 'Gold Loan',
                'subtitle' => 'Unlock the maximum value of your gold jewelry with instant approval & lowest interest rates.',
                'image' => 'https://images.unsplash.com/photo-1610375461246-83df859d849d?auto=format&fit=crop&w=1000&q=80',
                'interest' => 'Starting from 0.75% per month',
                'tenure' => 'Up to 36 Months',
                'amount' => '₹10,000 to ₹50 Lakhs',
                'features' => [
                    'Instant disbursement within 30 minutes',
                    'Minimal documentation & 100% gold security',
                    'Flexible repayment options (Monthly interest or bullet payment)',
                    'Zero foreclosure charges'
                ],
                'eligibility' => 'Any resident Indian aged 18 to 75 owning gold jewelry of 18 karat purity or higher.',
                'documents' => ['Aadhaar Card', 'PAN Card / Form 60', 'Address Proof', '2 Passport Size Photos']
            ],
            'gold-buying' => [
                'title' => 'Gold Buying',
                'subtitle' => 'Instant cash & transparent spot valuation for selling your old or unused gold jewelry.',
                'image' => 'https://images.unsplash.com/photo-1610375461246-83df859d849d?auto=format&fit=crop&w=1000&q=80',
                'interest' => 'Spot Cash Payout',
                'tenure' => 'Instant Process',
                'amount' => 'Up to ₹1 Crore',
                'features' => [
                    'Highest spot valuation as per live market rates',
                    'German caratometer purity testing',
                    'Direct bank transfer or instant payout',
                    '100% legal compliance & KYC verification'
                ],
                'eligibility' => 'Individuals aged 18+ who are rightful owners of gold jewelry with valid government ID.',
                'documents' => ['Aadhaar Card', 'PAN Card', 'Bank Account Details for Payout', 'Purchase Bill (If available)']
            ],
            'gold-takeover' => [
                'title' => 'Gold Loan Transfer / Takeover',
                'subtitle' => 'Release your pledged gold from existing banks or NBFCs and transfer to lower interest rates.',
                'image' => 'https://images.unsplash.com/photo-1559526324-4b87b5e36e44?auto=format&fit=crop&w=1000&q=80',
                'interest' => 'Lowest Transfer Interest',
                'tenure' => 'Flexible EMI Tenure',
                'amount' => 'High LTV Funding',
                'features' => [
                    'We clear your existing bank pledge on your behalf',
                    'Higher loan amount per gram',
                    'Substantial monthly interest savings',
                    'Doorstep pickup & release service'
                ],
                'eligibility' => 'Any customer with existing active gold loans in banks, pledge shops, or NBFCs.',
                'documents' => ['Pledge Card / Original Pawn Receipt', 'Aadhaar & PAN Card', 'Bank Passbook Copy']
            ],
            'personal-loan' => [
                'title' => 'Personal Loan',
                'subtitle' => 'Fulfill all your financial needs with hassle-free & quick personal loans.',
                'image' => 'https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?auto=format&fit=crop&w=1000&q=80',
                'interest' => 'Starting from 10.49% p.a.',
                'tenure' => '12 to 60 Months',
                'amount' => 'Up to ₹25 Lakhs',
                'features' => [
                    'No collateral or security required',
                    'Quick online application & approval',
                    'Customizable EMI options',
                    'Transparent charges with no hidden fees'
                ],
                'eligibility' => 'Salaried individuals and self-employed professionals with a minimum monthly income of ₹15,000.',
                'documents' => ['Salary Slip (Last 3 Months)', 'Bank Statement (Last 6 Months)', 'Aadhaar & PAN Card', 'Employment Verification']
            ],
            'business-loan' => [
                'title' => 'Business Loan',
                'subtitle' => 'Fuel your business expansion, working capital, and equipment upgrades.',
                'image' => 'https://images.unsplash.com/photo-1664575602554-2087b04935a5?auto=format&fit=crop&w=1000&q=80',
                'interest' => 'Starting from 12% p.a.',
                'tenure' => '12 to 84 Months',
                'amount' => 'Up to ₹1 Crore',
                'features' => [
                    'Collateral-free options available',
                    'Fast track processing for MSMEs & SMEs',
                    'Tax benefits on interest paid',
                    'Dedicated relationship manager'
                ],
                'eligibility' => 'Business entities operating for at least 2 years with a positive financial track record.',
                'documents' => ['GST Returns (Last 12 Months)', 'ITR with Computation (2 Years)', 'Business Registration Proof', 'Bank Account Statements']
            ],
            'home-loan' => [
                'title' => 'Home Loan',
                'subtitle' => 'Turn your dream of owning a home into reality with low EMIs and easy processing.',
                'image' => 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=1000&q=80',
                'interest' => 'Starting from 8.35% p.a.',
                'tenure' => 'Up to 30 Years',
                'amount' => 'Up to ₹5 Crores',
                'features' => [
                    'PMAY subsidy benefits applicable',
                    'Attractive interest rates for women applicants',
                    'Easy balance transfer & top-up facility',
                    'Comprehensive legal & technical check support'
                ],
                'eligibility' => 'Salaried and self-employed individuals aged 21 to 65 with a steady income source.',
                'documents' => ['Property Documents & Approved Plan', 'Income Proof & Tax Returns', 'Identity & Address Proof', 'Bank Statements']
            ],
            'car-loan' => [
                'title' => 'Car Loan (New & Used)',
                'subtitle' => 'Drive home your preferred car or vehicle with up to 100% on-road funding.',
                'image' => 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?auto=format&fit=crop&w=1000&q=80',
                'interest' => 'Starting from 8.75% p.a.',
                'tenure' => 'Up to 7 Years',
                'amount' => 'Up to 100% On-Road Price',
                'features' => [
                    'Instant digital pre-approval',
                    'Options for new & pre-owned vehicles',
                    'Lowest processing fees',
                    'Tie-ups with leading auto dealerships'
                ],
                'eligibility' => 'Individuals aged 21 to 65 with stable employment or business history.',
                'documents' => ['Vehicle Quotation / Proforma Invoice', 'Income Documents', 'Identity & Address Proof']
            ],
            'property-loan' => [
                'title' => 'Loan Against Property & Mortgage',
                'subtitle' => 'Leverage the true potential of your residential or commercial property for high-value funding.',
                'image' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1000&q=80',
                'interest' => 'Starting from 9.25% p.a.',
                'tenure' => 'Up to 15 Years',
                'amount' => 'Up to ₹10 Crores',
                'features' => [
                    'High loan-to-value (LTV) ratio',
                    'Loan allowed for residential, commercial & industrial property',
                    'Repay conveniently over a longer tenure',
                    'Flexible usage of funds'
                ],
                'eligibility' => 'Property owners with clear legal title and demonstrated repayment capability.',
                'documents' => ['Original Property Title Deeds', 'Property Tax Receipts', 'KYC Documents', 'Income & Bank Proofs']
            ],
            'education-loan' => [
                'title' => 'Education Loan',
                'subtitle' => 'Fund domestic and international higher education for bright career aspirations.',
                'image' => 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=1000&q=80',
                'interest' => 'Starting from 9.50% p.a.',
                'tenure' => 'Up to 15 Years',
                'amount' => 'Up to ₹1 Crore',
                'features' => [
                    '100% financing including tuition fee, living expenses & travel',
                    'Moratorium period during course duration plus 1 year',
                    'Tax rebate under Section 80E',
                    'No margin money required for select institutes'
                ],
                'eligibility' => 'Students who secured admission in recognized Indian or global universities with a co-applicant.',
                'documents' => ['Admission Offer Letter', 'Fee Structure', 'Co-applicant Income Proof', 'Academic Marksheets']
            ],
            'others-loan' => [
                'title' => 'Others Loan / Custom Financial Assistance',
                'subtitle' => 'Tailored financial solutions and custom loans suited for unique personal, medical, agricultural, or commercial requirements.',
                'image' => 'https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?auto=format&fit=crop&w=1000&q=80',
                'interest' => 'Customized Rates',
                'tenure' => 'Flexible EMI Terms',
                'amount' => 'Based on Eligibility',
                'features' => [
                    'Customized loan terms & repayment schedules',
                    'Quick evaluation for unique financial requirements',
                    'Hassle-free documentation & dedicated financial advisor',
                    'Flexible eligibility criteria tailored to your profile'
                ],
                'eligibility' => 'Salaried, self-employed, or business owners requiring customized loan structuring.',
                'documents' => ['Aadhaar & PAN Card', 'Income Proof / Bank Statements', 'Relevant Collateral / Support Documents (If applicable)']
            ]
        ];

        // Map alternate key aliases
        $keyAliasMap = [
            'loan-against-property' => 'property-loan',
        ];

        $rawKey = strtolower($service_type ?? 'gold-loan');
        $currentKey = $keyAliasMap[$rawKey] ?? $rawKey;

        if (!array_key_exists($currentKey, $services)) {
            $currentKey = 'gold-loan';
        }
        $currentService = $services[$currentKey];
    @endphp

    <!-- Loan Hero Section Start -->
    <div class="container-fluid loan-hero text-center mb-4">
        <div class="container py-3">
            <h1 class="display-5 text-white fw-bold mb-2">{{ $currentService['title'] }}</h1>
            <p class="fs-6 text-brand-gold mx-auto mb-3" style="max-width: 750px;">{{ $currentService['subtitle'] }}</p>
            <div>
                <a href="{{ route('customerlogin') }}" class="btn px-4 py-2.5 rounded-pill shadow fw-bold text-dark" style="background-color: var(--brand-gold-yellow); border: 2px solid var(--brand-gold);">
                    Apply Now for {{ $currentService['title'] }} <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
    <!-- Loan Hero Section End -->

    <!-- Loan Details Start -->
    <div class="container py-3 mb-5">
        <div class="row g-4">
            <!-- Left Column: Content (8 cols) -->
            <div class="col-lg-8">
                <!-- Loan Image -->
                <div class="mb-4 text-center">
                    <img src="{{ $currentService['image'] }}" 
                         alt="{{ $currentService['title'] }}" 
                         class="img-fluid rounded-3 shadow-sm w-100" 
                         style="max-height: 380px; object-fit: cover;"
                         onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?auto=format&fit=crop&w=1000&q=80';">
                </div>

                <!-- Key Highlights Cards (Equal Height) -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="feature-card text-center">
                            <i class="fas fa-percentage fa-2x text-warning mb-2"></i>
                            <div class="text-muted small text-uppercase fw-bold" style="font-size: 0.75rem;">Interest Rate</div>
                            <div class="fw-bold text-dark fs-6 mt-1">{{ $currentService['interest'] }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-card text-center">
                            <i class="fas fa-calendar-alt fa-2x text-warning mb-2"></i>
                            <div class="text-muted small text-uppercase fw-bold" style="font-size: 0.75rem;">Tenure Period</div>
                            <div class="fw-bold text-dark fs-6 mt-1">{{ $currentService['tenure'] }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-card text-center">
                            <i class="fas fa-wallet fa-2x text-warning mb-2"></i>
                            <div class="text-muted small text-uppercase fw-bold" style="font-size: 0.75rem;">Loan Amount</div>
                            <div class="fw-bold text-dark fs-6 mt-1">{{ $currentService['amount'] }}</div>
                        </div>
                    </div>
                </div>

                <!-- Key Features & Benefits -->
                <div class="mb-4 p-4 bg-white rounded-3 shadow-sm border">
                    <h4 class="fw-bold mb-3" style="color: var(--brand-brown);"><i class="fas fa-star me-2 text-warning"></i> Key Features & Benefits</h4>
                    <div class="row g-3">
                        @foreach($currentService['features'] as $feature)
                        <div class="col-md-6">
                            <div class="d-flex align-items-center p-2.5 rounded bg-light border-start border-4 border-warning">
                                <i class="fas fa-check-circle text-success me-2 fs-5"></i>
                                <span class="fw-semibold text-dark small">{{ $feature }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Eligibility Criteria -->
                <div class="mb-4 p-4 bg-white rounded-3 shadow-sm border border-start border-4" style="border-left-color: var(--brand-brown) !important;">
                    <h5 class="fw-bold mb-2" style="color: var(--brand-brown);"><i class="fas fa-user-check me-2 text-warning"></i> Eligibility Criteria</h5>
                    <p class="mb-0 text-dark small leading-relaxed">{{ $currentService['eligibility'] }}</p>
                </div>

                <!-- Required Documents -->
                <div class="mb-4 p-4 bg-white rounded-3 shadow-sm border">
                    <h5 class="fw-bold mb-3" style="color: var(--brand-brown);"><i class="fas fa-folder-open me-2 text-warning"></i> Required Documents</h5>
                    <div class="row g-2">
                        @foreach($currentService['documents'] as $doc)
                        <div class="col-md-6">
                            <div class="p-2 border rounded d-flex align-items-center bg-light">
                                <i class="fas fa-file-alt text-warning me-2"></i>
                                <span class="fw-medium text-dark small">{{ $doc }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right Column: Sidebar Navigation (4 cols) -->
            <div class="col-lg-4">
                <!-- Navigation Widget -->
                <div class="card shadow-sm border-0 mb-4 rounded-3 overflow-hidden">
                    <div class="card-header text-white py-2.5 px-3" style="background-color: var(--brand-brown);">
                        <h6 class="mb-0 text-warning fw-bold"><i class="fas fa-hand-holding-usd me-1.5"></i> Our Financial Products</h6>
                    </div>
                    <div class="list-group list-group-flush">
                        @foreach($services as $key => $serv)
                        <a href="{{ url('/service/' . $key) }}" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center sidebar-product-item {{ $currentKey == $key ? 'active-item' : '' }}">
                            <span>{{ $serv['title'] }}</span>
                            <i class="fas fa-chevron-right text-muted" style="font-size: 10px;"></i>
                        </a>
                        @endforeach
                    </div>
                </div>

                <!-- Quick Assistance Card -->
                <div class="card shadow-sm border-0 rounded-3 text-white text-center p-4" style="background: linear-gradient(135deg, var(--brand-brown), var(--brand-brown-light));">
                    <i class="fas fa-headset fa-3x text-warning mb-2"></i>
                    <h5 class="fw-bold text-white mb-2">Need Expert Help?</h5>
                    <p class="text-light small mb-3">Get in touch with our loan advisors for customized financial assistance.</p>
                    <a href="{{ route('contact') }}" class="btn btn-warning w-100 py-2 rounded-pill fw-bold text-dark shadow-sm">
                        Contact Loan Expert
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Loan Details End -->

    <!-- Footer Start -->
    @include('footer')
    <!-- Footer End -->

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
