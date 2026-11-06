<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Pan Card OTP Verification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
       <style>
    body {
        background-color: #f8f9fa;
        font-family: Arial, sans-serif;
    }

    .logo {
        max-height: 80px;
    }

    .form-box {
        width: 100%;
        max-width: 420px;
        margin: 20px auto;
        padding: 30px;
        background: #ffffff;
        border-radius: 10px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
    }

    .form-box h3, .form-box h5 {
        font-size: 1.4rem;
    }

    .form-control {
        font-size: 1.1rem;
        padding: 14px 16px;
    }

    .btn {
        font-size: 1.2rem;
        padding: 14px;
    }

    @media (max-width: 576px) {
        .form-box {
            padding: 25px 20px;
        }

        .form-box h3, .form-box h5 {
            font-size: 14px;
        }

        .form-control {
            font-size: 19px;
            padding: 16px;
        }

        .btn {
            font-size: 1.3rem;
            padding: 16px;
        }
    }
</style>

</head>
<body class="bg-light">

    <div class="container py-5">
        <div class="text-center mb-4">
            <img src="{{ asset('images/logo.png') }}" alt="Client Logo" class="logo mb-2">
            <h3>PAN Card Verification</h3>
        </div>

      <div class="form-box">
    <h5 class="mb-3 text-center">Enter PAN Card Number</h5>

    <div id="responseMessage" class="alert d-none text-center"></div>

    <div class="mb-3">
        <label for="pan-number" class="form-label">PAN Card Number</label>
        <input type="text" name="pan-number" id="pan-number" maxlength="10" required class="form-control" placeholder="Enter PAN number">
    </div>

    <div class="d-grid mb-3">
        <button type="button" class="btn btn-primary" onclick="verifyPAN()">Verify PAN</button>
    </div>

    <form id="storePanForm" method="POST" action="{{ url('/store-pan') }}" class="d-none">
        @csrf
        <input type="hidden" name="verified_pan" id="verifiedPanInput">
        <div class="d-grid">
            <button type="submit" class="btn btn-success">Submit</button>
        </div>
    </form>
</div>

   
    </div>

</body>
</html>
