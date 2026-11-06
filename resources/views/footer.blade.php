<!-- UNIFIED POJO FINSERV FOOTER (ROYAL BLUE, EMERALD GREEN & GOLD ACCENTS) -->
<footer style="background: #061a38; color: #cbd5e1; padding: 70px 0 30px 0; border-top: 4px solid #008744;">
    <div class="container">
        <div class="row g-4 mb-4">
            <!-- Brand & Description -->
            <div class="col-lg-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <img src="{{ asset('img/logo.png') }}" alt="POJO FINSERV Logo" style="max-height: 55px; border-radius: 8px;" onerror="this.src='{{ asset('images/logo.png') }}';">
                    <span class="fw-bold fs-4 text-white" style="letter-spacing: 0.5px;">POJO <span style="color: #008744;">FINSERV</span></span>
                </div>
                <p class="small leading-relaxed mb-3" style="color: #94a3b8;">POJO FINSERV is your trusted financial growth partner, committed to making transparent gold loans, gold buying, takeover funding, and personalized financial solutions accessible across Tamil Nadu.</p>
                <div class="d-flex gap-3 text-white fs-5">
                    <a href="#" class="hover-brand" style="color: #cbd5e1;"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="hover-brand" style="color: #cbd5e1;"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="hover-brand" style="color: #cbd5e1;"><i class="fab fa-instagram"></i></a>
                    <a href="https://wa.me/919994777004" target="_blank" class="text-success fs-4"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-2 col-md-4">
                <h5 class="fw-bold mb-3" style="color: #f59e0b; font-size: 1.1rem;">Quick Links</h5>
                <ul class="list-unstyled small d-flex flex-column gap-2">
                    <li><a href="{{ route('index') }}" class="text-decoration-none hover-brand" style="color: #ffffff;"><i class="fas fa-chevron-right me-1" style="font-size: 10px; color: #008744;"></i> Home</a></li>
                    <li><a href="{{ route('about') }}" class="text-decoration-none hover-brand" style="color: #ffffff;"><i class="fas fa-chevron-right me-1" style="font-size: 10px; color: #008744;"></i> About Us</a></li>
                    <li><a href="{{ route('service') }}" class="text-decoration-none hover-brand" style="color: #ffffff;"><i class="fas fa-chevron-right me-1" style="font-size: 10px; color: #008744;"></i> Our Services</a></li>
                    <li><a href="{{ route('index') }}#how-it-works" class="text-decoration-none hover-brand" style="color: #ffffff;"><i class="fas fa-chevron-right me-1" style="font-size: 10px; color: #008744;"></i> How It Works</a></li>
                    <li><a href="{{ route('contact') }}" class="text-decoration-none hover-brand" style="color: #ffffff;"><i class="fas fa-chevron-right me-1" style="font-size: 10px; color: #008744;"></i> Contact Us</a></li>
                    <li><a href="{{ url('/customerlogin') }}" class="text-decoration-none hover-brand" style="color: #ffffff;"><i class="fas fa-chevron-right me-1" style="font-size: 10px; color: #008744;"></i> Customer Login</a></li>
                </ul>
            </div>

            <!-- Our Services -->
            <div class="col-lg-3 col-md-4">
                <h5 class="fw-bold mb-3" style="color: #f59e0b; font-size: 1.1rem;">Financial Solutions</h5>
                <ul class="list-unstyled small d-flex flex-column gap-2">
                    <li><a href="{{ route('service') }}#gold-loan" class="text-decoration-none hover-brand" style="color: #ffffff;"><i class="fas fa-coins me-2" style="color: #f59e0b;"></i> Gold Loan Services</a></li>
                    <li><a href="{{ route('service') }}#gold-buying" class="text-decoration-none hover-brand" style="color: #ffffff;"><i class="fas fa-hand-holding-usd me-2" style="color: #008744;"></i> Gold Buying</a></li>
                    <li><a href="{{ route('service') }}#gold-takeover" class="text-decoration-none hover-brand" style="color: #ffffff;"><i class="fas fa-exchange-alt me-2" style="color: #0052cc;"></i> Gold Loan Takeover Funding</a></li>
                    <li><a href="{{ route('service') }}#personal-loan" class="text-decoration-none hover-brand" style="color: #ffffff;"><i class="fas fa-user-tag me-2" style="color: #f59e0b;"></i> Personal & Business Loans</a></li>
                    <li><a href="{{ route('service') }}#home-loan" class="text-decoration-none hover-brand" style="color: #ffffff;"><i class="fas fa-home me-2" style="color: #008744;"></i> Home & Property Loans</a></li>
                    <li><a href="{{ route('service') }}#car-loan" class="text-decoration-none hover-brand" style="color: #ffffff;"><i class="fas fa-car me-2" style="color: #0052cc;"></i> Vehicle & Education Loans</a></li>
                </ul>
            </div>

            <!-- Contact Information -->
            <div class="col-lg-3 col-md-4">
                <h5 class="fw-bold mb-3" style="color: #f59e0b; font-size: 1.1rem;">Contact Info</h5>
                <ul class="list-unstyled small d-flex flex-column gap-3 mb-0">
                    <li class="d-flex gap-2">
                        <i class="fas fa-map-marker-alt mt-1" style="color: #008744;"></i>
                        <span style="color: #ffffff;">D.No- 02/628, Mahil Nagar, Athappampalayam, Mel thindal, Erode - 638012</span>
                    </li>
                    <li class="d-flex gap-2">
                        <i class="fas fa-phone-alt mt-1" style="color: #008744;"></i>
                        <a href="tel:9994777004" class="text-white fw-bold text-decoration-none">9994777004</a>
                    </li>
                    <li class="d-flex gap-2">
                        <i class="fas fa-envelope mt-1" style="color: #008744;"></i>
                        <a href="mailto:Pojofinserv@outlook.com" class="text-white text-decoration-none">Pojofinserv@outlook.com</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Regulatory Disclaimer Box -->
        <div class="p-3 mb-4 rounded-3 text-start small" style="background: rgba(0, 0, 0, 0.35); border: 1px solid rgba(0, 135, 68, 0.4); color: #94a3b8;">
            <p class="mb-1 fw-bold text-warning"><i class="fas fa-exclamation-circle me-1"></i> Disclaimer:</p>
            <p class="mb-0" style="font-size: 0.82rem; line-height: 1.5;">
                POJO FINSERV provides financial services and loan assistance subject to applicable eligibility criteria, documentation, lender policies and approval requirements. Loan approval, interest rates, tenure, charges and other terms are determined by the respective lender/product provider, wherever applicable. Submission of an application does not guarantee loan approval or disbursement. Gold-related transactions and services are subject to applicable KYC, documentation, valuation and legal/regulatory requirements. Terms and conditions apply.
            </p>
        </div>

        <hr class="my-3" style="border-color: rgba(255, 255, 255, 0.1);">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center small text-white">
            <p class="mb-2 mb-md-0">&copy; {{ date('Y') }} <strong style="color: #00d068;">POJO FINSERV</strong>. Your Financial Growth Partner. All Rights Reserved.</p>
            <div class="d-flex gap-3">
                <span style="color: #94a3b8;">Serving Across Tamil Nadu</span>
            </div>
        </div>
    </div>
</footer>

<style>
.hover-brand:hover { color: #00d068 !important; }
</style>
