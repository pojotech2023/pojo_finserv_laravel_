<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload PAN Card</title>
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
            font-size: 1.2rem;
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
        <h3>please Upload your Aadhar Front and Back</h3>
    </div>

    <div class="form-box">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('aadhar.upload.submit') }}" enctype="multipart/form-data">
            @csrf
           

            <div class="mb-3">
                <label for="pan_card" class="form-label">please Upload Aadhar Front Page</label>
                <input type="file" name="aadhar_front" id="addhar_front" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
            </div>
            <div class="mb-3">
                <label for="pan_card" class="form-label">please Upload Aadhar Back Page</label>
                <input type="file" name="aadhar_back" id="aadhar_front" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Upload Addhar</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>
