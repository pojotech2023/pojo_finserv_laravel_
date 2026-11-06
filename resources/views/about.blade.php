<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - POJO FINSERV</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; color: #334155; background-color: #fdfbf7; }
        .page-header {
            background: linear-gradient(135deg, rgba(54, 0, 0, 0.95), rgba(107, 0, 0, 0.9)), 
                        url('https://images.unsplash.com/photo-1521791136064-7986c2920216?auto=format&fit=crop&w=1920&q=80') center/cover no-repeat;
            color: #ffffff;
            padding: 75px 0;
            text-align: center;
            border-bottom: 3px solid #f59e0b;
        }
        .founder-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(54, 0, 0, 0.08);
            border: 2px solid #e2e8f0;
            overflow: hidden;
        }
        .commitment-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.04);
            border-left: 4px solid #003b95;
            transition: transform 0.3s ease;
        }
        .commitment-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 25px rgba(54, 0, 0, 0.1);
        }
        .service-badge {
            background: #f8fafc;
            color: #003b95;
            border: 1px solid #e2e8f0;
            border-radius: 30px;
            padding: 6px 14px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .quote-box {
            background: #fff8eb;
            border-left: 4px solid #f59e0b;
            border-radius: 12px;
            padding: 20px;
        }
    </style>
</head>
<body>

    @include('header')

    <!-- PAGE HEADER -->
    <section class="page-header">
        <div class="container">
            <h1 class="fw-bold display-5 mb-2" style="color: #fbbf24;">ABOUT POJO FINSERV</h1>
            <p class="lead text-light mb-0">Built on Experience. Driven by Trust. Focused on Financial Solutions.</p>
        </div>
    </section>

    <!-- OUR STORY SECTION -->
    <section class="py-5 bg-white">
        <div class="container py-3">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <img src="https://images.unsplash.com/photo-1556761175-5973dc0f32e7?auto=format&fit=crop&w=800&q=80" alt="About POJO FINSERV" class="img-fluid rounded-4 shadow-lg border border-2 border-warning">
                </div>
                <div class="col-lg-6">
                    <span class="fw-bold text-uppercase" style="color: #003b95; letter-spacing: 1px;"><i class="fas fa-shield-alt me-1"></i> Our Story</span>
                    <h2 class="display-6 fw-bold text-dark mt-2 mb-3">Customer-Focused Financial Services Across Tamil Nadu</h2>
                    <p class="text-secondary leading-relaxed mb-3">
                        <strong>POJO FINSERV</strong> is a customer-focused financial services business established with a vision to make financial solutions more accessible, transparent, and convenient for customers across Tamil Nadu.
                    </p>
                    <p class="text-muted leading-relaxed mb-4">
                        Our journey is built on practical experience in the financial services and loan facilitation sector. Through our existing business operations under <strong>Shree Krishna Enterprises</strong>, we have gained valuable experience in understanding different customer requirements and helping individuals, families, self-employed professionals, and businesses explore suitable financial solutions.
                    </p>
                    <p class="text-muted leading-relaxed">
                        With POJO FINSERV, we are bringing this experience together under a professional and customer-focused financial services brand. Our services include <strong>Gold Loans, Gold Buying, Gold Loan Transfer/Takeover Funding</strong>, along with assistance for <strong>Personal Loans, Business Loans, Home Loans, New & Used Car Loans, Loan Against Property, Mortgage Loans, and Education Loans</strong>.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- OUR FOUNDER SECTION -->
    <section class="py-5" style="background-color: #fff8f0;">
        <div class="container py-3">
            <div class="text-center mb-5">
                <span class="fw-bold text-uppercase" style="color: #003b95; letter-spacing: 1px;"><i class="fas fa-user-tie me-1"></i> Leadership</span>
                <h2 class="fw-bold text-dark display-6 mt-1">Our Founder</h2>
                <p class="text-muted">Guided by visionary leadership committed to ethical financial facilitation across Tamil Nadu.</p>
            </div>

            <div class="row align-items-center g-4 justify-content-center">
                <div class="col-lg-10">
                    <div class="founder-card p-4 p-md-5">
                        <div class="row align-items-center g-4">
                            <div class="col-md-5 text-center">
                                <div class="founder-img-wrapper mx-auto rounded-4 shadow border border-3 border-warning overflow-hidden" 
                                     style="width: 100%; max-width: 320px; height: 380px; position: relative; background: #ffffff;">
                                    <img src="{{ asset('images/about/founder.jpeg') }}" 
                                         alt="Dhanapal Gunasekaran - Founder, POJO FINSERV" 
                                         style="width: 100%; height: 100%; object-fit: cover; object-position: center 20%; transform: scale(1.25); transform-origin: center 25%; display: block;"
                                         onerror="this.src='https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=600&q=80';">
                                </div>
                                <div class="mt-3">
                                    <h4 class="fw-bold mb-1 text-dark">Dhanapal Gunasekaran</h4>
                                    <span class="badge px-3 py-2 text-dark fw-bold" style="background: linear-gradient(135deg, #f59e0b, #fbbf24);">Founder, POJO FINSERV</span>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <span class="fw-bold text-uppercase small text-danger" style="letter-spacing: 1px;">Founder's Journey & Vision</span>
                                <h3 class="fw-bold text-dark mt-2 mb-3">Building a Transparent Financial Services Platform</h3>
                                <p class="text-secondary leading-relaxed mb-3">
                                    POJO FINSERV was founded by <strong>Mr. Dhanapal Gunasekaran</strong> with a vision to build a professional financial services platform focused on customer needs, transparency, and long-term relationships.
                                </p>
                                <p class="text-secondary leading-relaxed mb-3">
                                    With experience in the financial services and loan facilitation business, Mr. Dhanapal has developed a practical understanding of the different financial requirements faced by customers — from immediate funding needs and gold finance to personal, business, property, vehicle, and education-related financial requirements.
                                </p>
                                <p class="text-secondary leading-relaxed mb-4">
                                    His experience through <strong>Shree Krishna Enterprises</strong> has provided a strong foundation for understanding customer requirements, financial products, documentation processes, and the importance of providing clear guidance throughout the financing journey.
                                </p>

                                <div class="quote-box">
                                    <i class="fas fa-quote-left fs-3 text-warning mb-2"></i>
                                    <p class="mb-2 fst-italic text-dark fw-semibold" style="font-size: 0.95rem;">
                                        “Our objective is not simply to arrange finance. It is to understand the customer's requirement, provide clear guidance and help them explore a suitable financial solution through a transparent and responsible process.”
                                    </p>
                                    <div class="fw-bold text-dark text-end" style="font-size: 0.88rem;">— Dhanapal Gunasekaran <br><span class="text-muted font-weight-normal">Founder, POJO FINSERV</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- OUR EXPERIENCE SECTION -->
    <section class="py-5 bg-white">
        <div class="container py-3 text-center" style="max-width: 900px;">
            <span class="fw-bold text-uppercase" style="color: #003b95; letter-spacing: 1px;"><i class="fas fa-history me-1"></i> Our Experience</span>
            <h2 class="fw-bold text-dark display-6 mt-1 mb-4">From Experience to a New Vision</h2>
            <p class="text-secondary lead leading-relaxed mb-4">
                Our experience in the financial services sector has taught us that every customer has a different financial requirement.
            </p>
            <p class="text-muted leading-relaxed">
                Some customers may need immediate financial assistance against gold. Others may require funding for personal needs, business expansion, a new or used vehicle, property, a home, or higher education. Instead of treating every requirement the same way, POJO FINSERV focuses on understanding the customer's specific situation and connecting them with an appropriate financial solution. This customer-first approach forms the foundation of our business.
            </p>
        </div>
    </section>

    <!-- WHAT WE DO & LOAN SOLUTIONS -->
    <section class="py-5" style="background-color: #fdfbf7;">
        <div class="container py-3">
            <div class="text-center mb-5">
                <span class="fw-bold text-uppercase" style="color: #003b95; letter-spacing: 1px;"><i class="fas fa-hand-holding-usd me-1"></i> What We Do</span>
                <h2 class="fw-bold text-dark display-6 mt-1">Our Core Services & Financial Solutions</h2>
            </div>

            <!-- Gold Finance -->
            <div class="mb-5">
                <h4 class="fw-bold text-dark mb-4 border-bottom pb-2" style="border-color: #f59e0b !important;"><i class="fas fa-coins me-2 text-warning"></i> Gold Finance & Gold Services</h4>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="commitment-card h-100">
                            <h5 class="fw-bold text-dark"><i class="fas fa-coins text-warning me-2"></i> Gold Loan</h5>
                            <p class="text-muted small mb-0">Financial assistance against eligible gold ornaments, subject to applicable valuation, KYC, documentation and terms.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="commitment-card h-100">
                            <h5 class="fw-bold text-dark"><i class="fas fa-hand-holding-usd text-warning me-2"></i> Gold Buying</h5>
                            <p class="text-muted small mb-0">Purchase of eligible gold from customers following applicable verification, KYC and transaction procedures.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="commitment-card h-100">
                            <h5 class="fw-bold text-dark"><i class="fas fa-exchange-alt text-warning me-2"></i> Gold Loan Transfer</h5>
                            <p class="text-muted small mb-0">Assistance for eligible customers looking to transfer or take over existing gold loans with leading banks and institutions.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loan Solutions -->
            <div>
                <h4 class="fw-bold text-dark mb-4 border-bottom pb-2" style="border-color: #f59e0b !important;"><i class="fas fa-tasks me-2 text-warning"></i> Loan & Financial Solutions</h4>
                <div class="row g-3">
                    <div class="col-md-4"><div class="p-3 bg-white rounded shadow-sm border"><h6 class="fw-bold text-dark mb-1">Personal Loan</h6><p class="text-muted small mb-0">Financial assistance for eligible personal requirements.</p></div></div>
                    <div class="col-md-4"><div class="p-3 bg-white rounded shadow-sm border"><h6 class="fw-bold text-dark mb-1">Business Loan</h6><p class="text-muted small mb-0">Funding solutions for eligible business owners and self-employed.</p></div></div>
                    <div class="col-md-4"><div class="p-3 bg-white rounded shadow-sm border"><h6 class="fw-bold text-dark mb-1">Home Loan</h6><p class="text-muted small mb-0">Assistance to purchase, construct or finance residential property.</p></div></div>
                    <div class="col-md-4"><div class="p-3 bg-white rounded shadow-sm border"><h6 class="fw-bold text-dark mb-1">New Car Loan</h6><p class="text-muted small mb-0">Financial assistance for purchasing a new vehicle.</p></div></div>
                    <div class="col-md-4"><div class="p-3 bg-white rounded shadow-sm border"><h6 class="fw-bold text-dark mb-1">Used Car Loan</h6><p class="text-muted small mb-0">Loan assistance for purchasing a pre-owned vehicle.</p></div></div>
                    <div class="col-md-4"><div class="p-3 bg-white rounded shadow-sm border"><h6 class="fw-bold text-dark mb-1">Loan Against Property</h6><p class="text-muted small mb-0">Funding assistance against eligible residential or commercial property.</p></div></div>
                    <div class="col-md-4"><div class="p-3 bg-white rounded shadow-sm border"><h6 class="fw-bold text-dark mb-1">Mortgage Loan</h6><p class="text-muted small mb-0">Financial solutions against eligible property based on requirements.</p></div></div>
                    <div class="col-md-4"><div class="p-3 bg-white rounded shadow-sm border"><h6 class="fw-bold text-dark mb-1">Education Loan</h6><p class="text-muted small mb-0">Loan assistance for higher education in India and abroad.</p></div></div>
                </div>
            </div>
        </div>
    </section>

    <!-- VISION, MISSION & CORE VALUES -->
    <section class="py-5 bg-white">
        <div class="container py-3">
            <div class="row g-4 mb-5">
                <div class="col-md-6">
                    <div class="p-4 rounded-4 text-white h-100" style="background: linear-gradient(135deg, #072348, #003b95);">
                        <h3 class="fw-bold text-warning mb-3"><i class="fas fa-eye me-2"></i> OUR VISION</h3>
                        <h5 class="fw-bold mb-3">To Build a Trusted Financial Services Brand Across Tamil Nadu</h5>
                        <p class="text-light leading-relaxed mb-0">
                            Our vision is to build POJO FINSERV into a trusted and customer-focused financial services platform serving customers across Tamil Nadu. We aim to make financial solutions <strong>Simple. Transparent. Accessible. Convenient.</strong> We believe customers should be able to understand their available options before making an important financial decision.
                        </p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-4 rounded-4 bg-light border h-100">
                        <h3 class="fw-bold mb-3" style="color: #003b95;"><i class="fas fa-bullseye me-2 text-warning"></i> OUR MISSION</h3>
                        <ul class="list-unstyled mb-0 d-flex flex-column gap-2 text-dark font-weight-semibold small">
                            <li><i class="fas fa-check-circle me-2 text-success"></i> <strong>Understand:</strong> To understand the customer's actual financial requirement before recommending a solution.</li>
                            <li><i class="fas fa-check-circle me-2 text-success"></i> <strong>Guide:</strong> To provide clear and practical guidance throughout the financial application process.</li>
                            <li><i class="fas fa-check-circle me-2 text-success"></i> <strong>Connect:</strong> To connect eligible customers with suitable financial products and lending partners.</li>
                            <li><i class="fas fa-check-circle me-2 text-success"></i> <strong>Simplify:</strong> To make documentation, communication and the overall process as simple and convenient as possible.</li>
                            <li><i class="fas fa-check-circle me-2 text-success"></i> <strong>Build Relationships:</strong> To develop long-term relationships with customers through responsible and transparent service.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- CORE VALUES -->
            <div class="text-center mb-4">
                <h3 class="fw-bold text-dark mb-4">Our Core Values</h3>
                <div class="row g-3">
                    <div class="col-md-4"><div class="commitment-card h-100 text-start"><h6 class="fw-bold text-dark"><i class="fas fa-eye text-warning me-2"></i> Transparency</h6><p class="text-muted small mb-0">Clear understanding of terms, charges, eligibility, and processes.</p></div></div>
                    <div class="col-md-4"><div class="commitment-card h-100 text-start"><h6 class="fw-bold text-dark"><i class="fas fa-user text-warning me-2"></i> Customer Focus</h6><p class="text-muted small mb-0">Listening to the customer before suggesting available options.</p></div></div>
                    <div class="col-md-4"><div class="commitment-card h-100 text-start"><h6 class="fw-bold text-dark"><i class="fas fa-shield-alt text-warning me-2"></i> Integrity</h6><p class="text-muted small mb-0">Honesty, professionalism, and responsibility in all transactions.</p></div></div>
                    <div class="col-md-4"><div class="commitment-card h-100 text-start"><h6 class="fw-bold text-dark"><i class="fas fa-concierge-bell text-warning me-2"></i> Convenience</h6><p class="text-muted small mb-0">Streamlined digital processes making customer interaction easier.</p></div></div>
                    <div class="col-md-4"><div class="commitment-card h-100 text-start"><h6 class="fw-bold text-dark"><i class="fas fa-lock text-warning me-2"></i> Confidentiality</h6><p class="text-muted small mb-0">Appropriate care and privacy for customer information and documents.</p></div></div>
                    <div class="col-md-4"><div class="commitment-card h-100 text-start"><h6 class="fw-bold text-dark"><i class="fas fa-handshake text-warning me-2"></i> Long-Term Relationships</h6><p class="text-muted small mb-0">Building lasting relationships rather than focusing on single transactions.</p></div></div>
                </div>
            </div>
        </div>
    </section>

    <!-- DISCLAIMER & PROMISE -->
    <section class="py-5 text-white" style="background: linear-gradient(135deg, #072348, #003b95); border-top: 3px solid #f59e0b;">
        <div class="container text-center py-3" style="max-width: 900px;">
            <h3 class="fw-bold mb-3 text-warning">OUR PROMISE</h3>
            <p class="text-light lead mb-4">
                At POJO FINSERV, we believe that financial services should be built around <strong>trust, clarity and customer understanding</strong>. We are committed to helping customers navigate their financial requirements with a professional approach, appropriate documentation and clear communication.
            </p>

            <hr class="border-warning my-4 opacity-50">

            <div class="text-start text-light small opacity-75">
                <p class="fw-bold mb-1 text-warning">Disclaimer:</p>
                <p class="mb-0">
                    POJO FINSERV provides financial services and loan assistance subject to applicable eligibility criteria, documentation, lender policies and approval requirements. Loan approval, interest rates, tenure, charges and other terms are determined by the respective lender/product provider, wherever applicable. Submission of an application does not guarantee loan approval or disbursement. Gold-related transactions and services are subject to applicable KYC, documentation, valuation and legal/regulatory requirements. Terms and conditions apply.
                </p>
            </div>
        </div>
    </section>

    <!-- UNIFIED FOOTER -->
    @include('footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>