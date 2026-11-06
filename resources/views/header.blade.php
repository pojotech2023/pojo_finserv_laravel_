<!-- Top Info Bar (Royal Navy & Gold Accent) -->
<div class="py-2 text-white border-bottom border-warning border-opacity-25 d-none d-lg-block" style="font-size: 0.85rem; background-color: #072348 !important;">
    <div class="container-fluid px-lg-4 d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <span><i class="fas fa-map-marker-alt me-1" style="color: #f59e0b;"></i>D.No- 02/628, Mahil Nagar, Athappampalayam, Mel thindal, Erode - 638012</span>
            <span><i class="fas fa-envelope me-1" style="color: #f59e0b;"></i><a href="mailto:Pojofinserv@outlook.com" class="text-white text-decoration-none">Pojofinserv@outlook.com</a></span>
        </div>
        <div class="d-flex align-items-center gap-3">
            <span><i class="fas fa-phone-alt me-1" style="color: #f59e0b;"></i><a href="tel:9994777004" class="text-white fw-bold text-decoration-none">9994777004</a></span>
            <span class="badge px-2 py-1 text-dark fw-bold" style="background: linear-gradient(135deg, #f59e0b, #d4af37);"><i class="fas fa-shield-alt me-1"></i> Your Financial Growth Partner</span>
        </div>
    </div>
</div>

<!-- Main Navigation Bar -->
<nav class="navbar navbar-expand-lg sticky-top shadow-sm py-2" style="background-color: #ffffff; border-bottom: 3px solid #0052cc;">
    <div class="container-fluid px-lg-3">
        <!-- Logo / Brand -->
        <a class="navbar-brand d-flex align-items-center gap-2 me-2" href="{{ route('index') }}" style="white-space: nowrap;">
            <img src="{{ asset('img/logo.png') }}" alt="POJO FINSERV Logo" style="max-height: 48px; width: auto; border-radius: 6px;" onerror="this.src='{{ asset('images/logo.png') }}';">
            <span class="fw-bold fs-5" style="color: #0052cc; letter-spacing: 0.5px;">POJO <span style="color: #008744;">FINSERV</span></span>
        </a>

        <!-- Mobile Toggle Button -->
        <button class="navbar-toggler border-1 shadow-none p-2" type="button" data-bs-toggle="collapse" data-bs-target="#dlNavbar" aria-controls="dlNavbar" aria-expanded="false" aria-label="Toggle navigation" style="border: 2px solid #0052cc !important; background-color: #ffffff;">
            <i class="fas fa-bars fs-4 text-dark d-block"></i>
        </button>

        <!-- Navbar Links -->
        <div class="collapse navbar-collapse" id="dlNavbar">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 fw-semibold align-items-lg-center" style="font-size: 0.88rem; white-space: nowrap;">
                <li class="nav-item px-1 px-xl-2">
                    <a class="nav-link text-dark nav-hover-brand" href="{{ route('index') }}"><i class="fas fa-home me-1" style="color: #0052cc;"></i> Home</a>
                </li>
                <li class="nav-item px-1 px-xl-2">
                    <a class="nav-link text-dark nav-hover-brand" href="{{ route('about') }}"><i class="fas fa-info-circle me-1" style="color: #0052cc;"></i> About Us</a>
                </li>
                <li class="nav-item dropdown px-1 px-xl-2">
                    <a class="nav-link dropdown-toggle text-dark" href="#" id="servicesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-concierge-bell me-1" style="color: #0052cc;"></i> Services
                    </a>
                    <ul class="dropdown-menu border-0 shadow-lg p-2" aria-labelledby="servicesDropdown" style="min-width: 270px; border-radius: 12px;">
                        <li><h6 class="dropdown-header text-uppercase fw-bold" style="color: #0052cc;"><i class="fas fa-coins me-1 text-warning"></i> Gold Finance</h6></li>
                        <li><a class="dropdown-item py-1.5 custom-dropdown-item" href="{{ url('/service/gold-loan') }}"><i class="fas fa-chevron-right me-2 icon-gold" style="font-size: 10px; color: #008744;"></i> Gold Loan</a></li>
                        <li><a class="dropdown-item py-1.5 custom-dropdown-item" href="{{ url('/service/gold-buying') }}"><i class="fas fa-chevron-right me-2 icon-gold" style="font-size: 10px; color: #008744;"></i> Gold Buying</a></li>
                        <li><a class="dropdown-item py-1.5 custom-dropdown-item" href="{{ url('/service/gold-takeover') }}"><i class="fas fa-chevron-right me-2 icon-gold" style="font-size: 10px; color: #008744;"></i> Gold Loan Transfer / Takeover</a></li>
                        <li><hr class="dropdown-divider my-1.5"></li>
                        <li><h6 class="dropdown-header text-uppercase fw-bold" style="color: #0052cc;"><i class="fas fa-hand-holding-usd me-1 text-success"></i> Loan Assistance</h6></li>
                        <li><a class="dropdown-item py-1.5 custom-dropdown-item" href="{{ url('/service/personal-loan') }}"><i class="fas fa-user-tag me-2 icon-gold" style="font-size: 10px; color: #008744;"></i> Personal Loan</a></li>
                        <li><a class="dropdown-item py-1.5 custom-dropdown-item" href="{{ url('/service/business-loan') }}"><i class="fas fa-briefcase me-2 icon-gold" style="font-size: 10px; color: #008744;"></i> Business Loan</a></li>
                        <li><a class="dropdown-item py-1.5 custom-dropdown-item" href="{{ url('/service/home-loan') }}"><i class="fas fa-home me-2 icon-gold" style="font-size: 10px; color: #008744;"></i> Home Loan</a></li>
                        <li><a class="dropdown-item py-1.5 custom-dropdown-item" href="{{ url('/service/car-loan') }}"><i class="fas fa-car me-2 icon-gold" style="font-size: 10px; color: #008744;"></i> Car Loan (New & Used)</a></li>
                        <li><a class="dropdown-item py-1.5 custom-dropdown-item" href="{{ url('/service/property-loan') }}"><i class="fas fa-building me-2 icon-gold" style="font-size: 10px; color: #008744;"></i> Loan Against Property & Mortgage</a></li>
                        <li><a class="dropdown-item py-1.5 custom-dropdown-item" href="{{ url('/service/education-loan') }}"><i class="fas fa-graduation-cap me-2 icon-gold" style="font-size: 10px; color: #008744;"></i> Education Loan</a></li>
                        <li><a class="dropdown-item py-1.5 custom-dropdown-item" href="{{ url('/service/others-loan') }}"><i class="fas fa-plus-circle me-2 icon-gold" style="font-size: 10px; color: #008744;"></i> Others Loan</a></li>
                    </ul>
                </li>
                <li class="nav-item px-1 px-xl-2">
                    <a class="nav-link text-dark nav-hover-brand" href="{{ route('index') }}#how-it-works"><i class="fas fa-tasks me-1" style="color: #0052cc;"></i> How It Works</a>
                </li>
                <li class="nav-item px-1 px-xl-2">
                    <a class="nav-link text-dark nav-hover-brand" href="{{ route('index') }}#why-choose-us"><i class="fas fa-star me-1" style="color: #f59e0b;"></i> Why Us</a>
                </li>
                <li class="nav-item px-1 px-xl-2">
                    <a class="nav-link text-dark nav-hover-brand" href="{{ route('index') }}#faqs"><i class="fas fa-question-circle me-1" style="color: #0052cc;"></i> FAQs</a>
                </li>
                <li class="nav-item px-1 px-xl-2">
                    <a class="nav-link text-dark nav-hover-brand" href="{{ route('contact') }}"><i class="fas fa-address-book me-1" style="color: #0052cc;"></i> Contact Us</a>
                </li>
            </ul>

            <!-- Apply Now CTA Button Dropdown -->
            <div class="ms-lg-2 mt-3 mt-lg-0 dropdown" style="white-space: nowrap;">
                <button class="btn px-3 py-1.5 rounded-pill fw-bold text-white shadow-sm d-inline-flex align-items-center gap-1.5 dropdown-toggle" type="button" id="applyNowDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="background: linear-gradient(135deg, #0052cc 0%, #008744 100%); border: 2px solid #f59e0b; font-size: 0.85rem;">
                    <i class="fas fa-paper-plane" style="color: #fbbf24;"></i> Apply Now
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2 p-1" aria-labelledby="applyNowDropdown" style="background-color: #072348; border-radius: 12px; overflow: hidden; min-width: 190px; border: 1px solid #008744 !important;">
                    <li>
                        <a class="dropdown-item fw-bold text-white py-2 px-3 custom-apply-item d-flex align-items-center" href="{{ url('/customerlogin?service=takeover') }}" style="font-size: 0.88rem;">
                            <i class="fas fa-hand-holding-usd me-2" style="color: #fbbf24;"></i> Gold Take Over
                        </a>
                    </li>
                    <li><hr class="dropdown-divider my-1" style="border-color: rgba(255, 255, 255, 0.15);"></li>
                    <li>
                        <a class="dropdown-item fw-bold text-white py-2 px-3 custom-apply-item d-flex align-items-center" href="{{ url('/customerlogin?service=sell_gold') }}" style="font-size: 0.88rem;">
                            <i class="fas fa-coins me-2" style="color: #fbbf24;"></i> Sell Your Gold
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<style>
.nav-hover-brand:hover {
    color: #0052cc !important;
}

.custom-apply-item {
    transition: all 0.2s ease;
    border-radius: 6px;
}

.custom-apply-item:hover, 
.custom-apply-item:focus, 
.custom-apply-item:active {
    background-color: #0052cc !important;
    color: #ffffff !important;
}

/* Custom Dropdown Styling */
.custom-dropdown-item {
    color: #475569 !important;
    font-size: 0.88rem;
    transition: all 0.2s ease;
    border-radius: 6px;
}
.custom-dropdown-item:hover, 
.custom-dropdown-item:focus, 
.custom-dropdown-item:active,
.dropdown-item.active {
    background-color: #0052cc !important;
    color: #ffffff !important;
}
.custom-dropdown-item:hover .icon-gold,
.custom-dropdown-item:focus .icon-gold,
.custom-dropdown-item:active .icon-gold {
    color: #fbbf24 !important;
}
</style>
