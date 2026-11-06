<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POJO FINSERV - Admin Login</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #072348 0%, #003b95 50%, #008744 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }

        .login-card {
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
            width: 100%;
            max-width: 440px;
            padding: 40px 35px;
            border: 2px solid rgba(255, 255, 255, 0.2);
        }

        .logo-img {
            max-height: 65px;
            width: auto;
            border-radius: 8px;
        }

        .btn-brand {
            background: linear-gradient(135deg, #0052cc 0%, #008744 100%);
            color: #ffffff;
            font-weight: 700;
            padding: 12px;
            border-radius: 50px;
            border: 1px solid #f59e0b;
            box-shadow: 0 8px 20px rgba(0, 82, 204, 0.35);
            transition: all 0.25s ease;
        }

        .btn-brand:hover {
            transform: translateY(-2px);
            color: #fbbf24;
            box-shadow: 0 12px 25px rgba(0, 82, 204, 0.45);
        }

        .form-control:focus {
            border-color: #0052cc;
            box-shadow: 0 0 0 0.25rem rgba(0, 82, 204, 0.15);
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="text-center mb-4">
        <img src="{{ asset('img/logo.png') }}" alt="POJO FINSERV" class="logo-img mb-2" onerror="this.src='{{ asset('images/logo.png') }}';">
        <h4 class="fw-bold text-dark mb-0">POJO <span style="color: #008744;">FINSERV</span></h4>
        <p class="text-muted small mt-1">Admin Portal Sign In</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger rounded-3 py-2 px-3 small mb-3">
            <i class="fas fa-exclamation-circle me-1"></i> {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ url('cr-login') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label fw-semibold small text-secondary">Email Address</label>
            <div class="input-group">
                <span class="input-group-text bg-light text-muted border-end-0"><i class="fas fa-envelope"></i></span>
                <input type="email" name="email" class="form-control border-start-0" placeholder="admin@demo.com" value="{{ old('email') }}" required autofocus>
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label fw-semibold small text-secondary">Password</label>
            <div class="input-group">
                <span class="input-group-text bg-light text-muted border-end-0"><i class="fas fa-lock"></i></span>
                <input type="password" name="password" class="form-control border-start-0" placeholder="••••••••" required>
            </div>
        </div>

        <div class="d-grid mb-3">
            <button type="submit" class="btn btn-brand">
                Sign In to Admin Panel <i class="fas fa-arrow-right ms-1"></i>
            </button>
        </div>

        <div class="text-center">
            <a href="{{ route('index') }}" class="text-decoration-none small text-muted">
                <i class="fas fa-arrow-left me-1"></i> Back to POJO FINSERV Website
            </a>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
