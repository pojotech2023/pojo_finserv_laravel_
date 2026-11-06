<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POJO FINSERV - Admin Portal</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --brand-blue: #0052cc;
            --brand-navy: #072348;
            --brand-green: #008744;
            --brand-gold: #f59e0b;
            --brand-light: #f8fafc;
        }

        html, body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f1f5f9 !important;
            margin: 0;
            padding: 0;
            width: 100%;
            color: #334155;
            overflow-x: hidden;
        }

        /* Navbar */
        .navbar-top {
            background: linear-gradient(90deg, #072348 0%, #003b95 100%);
            border-bottom: 3px solid var(--brand-green);
            padding: 10px 20px;
            z-index: 1040;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: -260px;
            width: 260px;
            height: 100%;
            background: linear-gradient(180deg, #072348 0%, #061a38 100%);
            padding-top: 65px;
            transition: left 0.25s ease;
            z-index: 1030;
            overflow-y: auto;
            border-right: 1px solid rgba(255,255,255,0.08);
            box-shadow: 4px 0 15px rgba(0,0,0,0.1);
        }

        .sidebar.active { left: 0; }

        .sidebar .nav-item-link {
            color: #cbd5e1;
            padding: 14px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.93rem;
            border-left: 4px solid transparent;
            transition: all 0.2s ease;
        }

        .sidebar .nav-item-link:hover, .sidebar .nav-item-link.active {
            background-color: rgba(255, 255, 255, 0.08);
            color: #ffffff;
            border-left-color: var(--brand-gold);
        }

        .sidebar .nav-item-link.active {
            background: linear-gradient(90deg, rgba(0, 135, 68, 0.25) 0%, rgba(0, 82, 204, 0.15) 100%);
            border-left-color: #00d068;
            color: #ffffff;
        }

        .sidebar .close-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            font-size: 24px;
            color: #fff;
            cursor: pointer;
        }

        #main-content {
            min-height: calc(100vh - 65px);
            background-color: #f1f5f9;
            padding: 1.5rem !important;
            box-sizing: border-box;
            width: 100% !important;
            max-width: 100% !important;
            margin-top: 65px;
        }

        @media (min-width: 992px) {
            .sidebar {
                left: 0;
                padding-top: 65px;
            }
            .sidebar.collapsed {
                left: -260px !important;
            }
            .sidebar .close-btn { display: none; }
            #main-content {
                margin-left: 260px !important;
                width: calc(100% - 260px) !important;
                max-width: calc(100% - 260px) !important;
                transition: margin-left 0.25s ease, width 0.25s ease;
            }
            #main-content.expanded {
                margin-left: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
            }
            .desktop-sidebar-toggle {
                display: inline-flex !important;
                align-items: center;
                justify-content: center;
                background: rgba(255,255,255,0.12);
                border: 1px solid rgba(255,255,255,0.25);
                color: #fff;
                padding: 6px 10px;
                border-radius: 8px;
                cursor: pointer;
                transition: all 0.2s;
            }
            .desktop-sidebar-toggle:hover {
                background: rgba(255,255,255,0.25);
                color: #fff;
            }
        }

        .user-card {
            background: linear-gradient(135deg, rgba(0, 82, 204, 0.4) 0%, rgba(0, 135, 68, 0.4) 100%);
            border: 1px solid rgba(255,255,255,0.15);
            color: #fff;
            border-radius: 12px;
            padding: 16px;
            margin: 12px 16px 20px 16px;
            text-align: center;
        }

        .user-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: #ffffff;
            color: var(--brand-navy);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            font-weight: 800;
            margin: 0 auto 8px auto;
            border: 2px solid var(--brand-gold);
        }

        /* Mode Badges */
        .mode-badge-live {
            background-color: #10b981;
            color: #ffffff;
            font-weight: 700;
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 0.82rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.35);
        }

        .mode-badge-test {
            background-color: #f59e0b;
            color: #1e293b;
            font-weight: 800;
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 0.82rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 2px 8px rgba(245, 158, 11, 0.35);
        }

        .hit-counter-pill {
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #ffffff;
            font-size: 0.82rem;
            font-weight: 600;
            padding: 5px 12px;
            border-radius: 50px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
    </style>
</head>
<body>

@php
    $isLive = \App\Models\AppConfig::isLiveMode();
    $aadhaarHits = \App\Models\AppConfig::getAadhaarHits();
    $panHits = \App\Models\AppConfig::getPanHits();
    $user = Auth::guard('cr_user')->user();
@endphp

<!-- Top Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top navbar-top shadow-sm">
    <div class="container-fluid px-2 px-md-3">
        <!-- Toggle button -->
        <button class="desktop-sidebar-toggle d-none d-lg-inline-flex me-2" type="button" onclick="toggleDesktopSidebar()" title="Toggle Sidebar">
            <i class="fas fa-bars"></i>
        </button>
        <button class="navbar-toggler me-2 border-0" type="button" onclick="openSidebar()">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Brand -->
        <a class="navbar-brand d-flex align-items-center gap-2 me-3" href="{{ route('kyc.index') }}">
            <img src="{{ asset('img/logo.png') }}" alt="POJO FINSERV" style="height: 38px; width: auto; border-radius: 6px;" onerror="this.src='{{ asset('images/logo.png') }}';">
            <span class="fw-bold fs-5 text-white">POJO <span style="color: #00d068;">FINSERV</span> <span class="badge bg-light text-dark ms-1 small" style="font-size: 10px; font-weight: 700; vertical-align: middle;">ADMIN</span></span>
        </a>

        <!-- System Mode & API Hit Counters -->
        <div class="d-none d-md-flex align-items-center gap-2 ms-auto me-3">
            <!-- Mode Indicator -->
            @if($isLive)
                <span class="mode-badge-live" title="Live Mode: Real QuickeKYC APIs and Google Drive uploads are active">
                    <i class="fas fa-satellite-dish fa-spin"></i> LIVE MODE
                </span>
            @else
                <span class="mode-badge-test" title="Testing Mode: Real APIs bypassed. Sample OTP is 123456. Google Drive upload is skipped.">
                    <i class="fas fa-flask"></i> TESTING MODE
                </span>
            @endif

            <!-- Aadhaar Hits Badge -->
            <span class="hit-counter-pill" title="Total Aadhaar API calls made in LIVE mode">
                <i class="fas fa-fingerprint text-warning"></i> Aadhaar Hits: <strong>{{ $aadhaarHits }}</strong>
            </span>

            <!-- PAN Hits Badge -->
            <span class="hit-counter-pill" title="Total PAN API calls made in LIVE mode">
                <i class="fas fa-id-card text-info"></i> PAN Hits: <strong>{{ $panHits }}</strong>
            </span>

            <!-- Switch Mode Toggle Form -->
            <form action="{{ route('admin.toggleApiMode') }}" method="POST" class="d-inline m-0">
                @csrf
                <button type="submit" class="btn btn-sm {{ $isLive ? 'btn-outline-warning' : 'btn-success' }} fw-bold rounded-pill px-3 py-1 shadow-sm" title="Click to toggle between Testing Mode and Live Mode">
                    @if($isLive)
                        <i class="fas fa-toggle-on me-1"></i> Switch to Test Mode
                    @else
                        <i class="fas fa-toggle-off me-1"></i> Switch to Live Mode
                    @endif
                </button>
            </form>
        </div>

        <!-- Quick Links & Logout -->
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('index') }}" target="_blank" class="btn btn-sm btn-outline-light rounded-pill px-2.5 py-1 d-none d-sm-inline-flex align-items-center gap-1" title="View Public Website">
                <i class="fas fa-external-link-alt"></i> <span class="d-none d-lg-inline">Website</span>
            </a>
            
            <form id="logout-form" method="POST" action="{{ route('cr.logout') }}" class="m-0">
                @csrf
                <button type="submit" class="btn btn-sm btn-danger rounded-pill px-3 py-1 fw-bold shadow-sm d-inline-flex align-items-center gap-1">
                    <i class="fas fa-sign-out-alt"></i> <span class="d-none d-sm-inline">Logout</span>
                </button>
            </form>
        </div>
    </div>
</nav>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <span class="close-btn" onclick="closeSidebar()">&times;</span>

    <div class="user-card">
        <div class="user-avatar">
            {{ $user ? strtoupper(substr($user->name, 0, 1)) : 'A' }}
        </div>
        <div class="fw-bold fs-6">{{ $user->name ?? 'Admin Portal' }}</div>
        <span class="badge bg-warning text-dark px-2 py-1 small mt-1">POJO FINSERV</span>
    </div>

    <!-- Navigation (Filtered: Only Customer Details & Contact Enquiries) -->
    <div class="px-2">
        <div class="text-uppercase small px-3 mb-2 fw-bold" style="color: #94a3b8; font-size: 11px; letter-spacing: 0.5px;">Main Modules</div>
        
        <!-- Customer Details (Apply Now: Gold Take Over & Sell Your Gold) -->
        <a href="{{ route('kyc.index') }}" class="nav-item-link {{ request()->routeIs('kyc.*') ? 'active' : '' }}">
            <i class="fas fa-users-cog fa-fw fs-5 text-warning"></i>
            <div>
                <div>Customer Applications</div>
                <div class="small text-muted" style="font-size: 11px;">Gold Take Over & Sell Gold</div>
            </div>
        </a>

        <!-- Customer Contact Enquiries (Frontend forms) -->
        <a href="{{ route('admin.contact.enquiries') }}" class="nav-item-link {{ request()->routeIs('admin.contact.*') ? 'active' : '' }}">
            <i class="fas fa-envelope-open-text fa-fw fs-5 text-info"></i>
            <div>
                <div>Contact Enquiries</div>
                <div class="small text-muted" style="font-size: 11px;">Frontend Enquiries & Leads</div>
            </div>
        </a>

        <hr style="border-color: rgba(255,255,255,0.1);" class="my-3">

        <div class="text-uppercase small px-3 mb-2 fw-bold" style="color: #94a3b8; font-size: 11px; letter-spacing: 0.5px;">Quick Actions</div>

        <a href="{{ url('/customerlogin?service=takeover') }}" target="_blank" class="nav-item-link">
            <i class="fas fa-hand-holding-usd fa-fw fs-5 text-success"></i>
            <div>
                <div>Gold Take Over Form</div>
                <div class="small text-muted" style="font-size: 11px;">Open Customer Apply Form</div>
            </div>
        </a>

        <a href="{{ url('/customerlogin?service=sell_gold') }}" target="_blank" class="nav-item-link">
            <i class="fas fa-coins fa-fw fs-5 text-warning"></i>
            <div>
                <div>Sell Your Gold Form</div>
                <div class="small text-muted" style="font-size: 11px;">Open Sell Gold Form</div>
            </div>
        </a>

        <a href="{{ route('index') }}" target="_blank" class="nav-item-link">
            <i class="fas fa-globe fa-fw fs-5 text-primary"></i>
            <div>
                <div>Visit Website</div>
                <div class="small text-muted" style="font-size: 11px;">POJO FINSERV Frontend</div>
            </div>
        </a>
    </div>

    <!-- Mobile-only mode toggle and counters in sidebar -->
    <div class="d-md-none p-3 mt-3 border-top border-secondary border-opacity-25 text-white">
        <div class="small fw-bold mb-2">System Mode:</div>
        <form action="{{ route('admin.toggleApiMode') }}" method="POST" class="mb-3">
            @csrf
            <button type="submit" class="btn btn-sm w-100 {{ $isLive ? 'btn-outline-warning' : 'btn-success' }} fw-bold">
                {{ $isLive ? 'Switch to Test Mode' : 'Switch to Live Mode' }}
            </button>
        </form>
        <div class="small text-muted">Aadhaar Hits: <strong>{{ $aadhaarHits }}</strong></div>
        <div class="small text-muted">PAN Hits: <strong>{{ $panHits }}</strong></div>
    </div>
</div>

<!-- Main Content -->
<div id="main-content">
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function openSidebar() {
        document.getElementById("sidebar").classList.add("active");
    }
    function closeSidebar() {
        document.getElementById("sidebar").classList.remove("active");
    }
    function toggleDesktopSidebar() {
        var sidebar = document.getElementById("sidebar");
        var mainContent = document.getElementById("main-content");
        if (!sidebar || !mainContent) return;
        var isCollapsed = sidebar.classList.toggle("collapsed");
        mainContent.classList.toggle("expanded", isCollapsed);
        try {
            localStorage.setItem("sidebar_collapsed", isCollapsed ? "1" : "0");
        } catch(e) {}
    }

    document.addEventListener("DOMContentLoaded", function() {
        try {
            if (window.innerWidth >= 992 && localStorage.getItem("sidebar_collapsed") === "1") {
                var s = document.getElementById("sidebar");
                var m = document.getElementById("main-content");
                if (s) s.classList.add("collapsed");
                if (m) m.classList.add("expanded");
            }
        } catch(e) {}
    });
</script>

</body>
</html>
