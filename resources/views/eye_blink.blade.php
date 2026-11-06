<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photo Verification - POJO FINSERV</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8fafc;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #334155;
        }

        .customer-header-bar {
            background: #072348;
            padding: 15px 0;
            border-bottom: 3px solid #d4af37;
            text-align: center;
        }

        .logo-img {
            max-height: 75px;
            width: auto;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        .form-box {
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
            padding: 35px;
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 82, 204, 0.1);
            border: 2px solid #e2e8f0;
        }

        .preview-img {
            max-width: 100%;
            height: auto;
            max-height: 250px;
            border-radius: 12px;
            border: 2px solid #22c55e;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
    </style>
</head>
<body class="bg-light">

    <!-- Top Header with ONLY Logo -->
    <div class="customer-header-bar">
        <div class="container d-flex justify-content-center align-items-center">
            <a href="{{ route('index') }}" class="d-flex align-items-center gap-2 text-decoration-none">
                <img src="{{ asset('img/logo.png') }}" alt="POJO FINSERV Logo" class="logo-img" onerror="this.src='{{ asset('images/logo.png') }}';">
            </a>
        </div>
    </div>

    <div class="container py-5">
        <div class="text-center mb-4">
            <h3 class="fw-bold text-dark mb-1">Live Photo / Eye Blink Verification</h3>
            <p class="text-muted small">POJO FINSERV - Verification Process</p>
        </div>

        <div class="form-box text-center">
            @if(session('success'))
                <div class="alert alert-success rounded-3">{{ session('success') }}</div>
            @endif

            <p class="text-muted small mb-3">Please capture a live photo or blink your eyes in front of the camera (Optional).</p>

            <form action="{{ route('photo.store') }}" method="POST" id="photoForm">
                @csrf
                <input type="hidden" name="kyc_id" value="{{ $kyc_id ?? $kyc->id }}">
                <input type="hidden" name="photo_data" id="photo_data">

                <!-- Live Camera Video -->
                <div class="mb-3" id="videoContainer">
                    <video id="video" width="100%" height="240" autoplay playsinline class="rounded border bg-dark"></video>
                    <canvas id="canvas" style="display:none;" width="320" height="240"></canvas>
                </div>

                <!-- Captured Image Preview Container -->
                <div class="mb-3 d-none" id="previewContainer">
                    <div class="mb-2 fw-semibold text-success"><i class="fas fa-check-circle me-1"></i> Photo Captured Successfully!</div>
                    <img id="preview" class="preview-img" alt="Captured Photo Preview">
                </div>

                <div class="d-grid gap-2">
                    <button type="button" id="captureBtn" class="btn btn-secondary rounded-pill fw-bold" onclick="takeSnapshot()">
                        <i class="fas fa-camera me-1"></i> Capture Photo
                    </button>
                    <button type="button" id="retakeBtn" class="btn btn-warning rounded-pill fw-bold d-none text-dark" onclick="retakePhoto()">
                        <i class="fas fa-redo me-1"></i> Retake Photo
                    </button>
                    <button type="submit" class="btn btn-lg rounded-pill fw-bold text-white shadow-sm" style="background: linear-gradient(135deg, #0052cc, #003b95); border: 1px solid #d4af37;">
                        Submit & Continue <i class="fas fa-arrow-right ms-2" style="color: #fbbf24;"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Unified Homepage Footer -->
    @include('footer')

    <script>
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const photoData = document.getElementById('photo_data');
        const videoContainer = document.getElementById('videoContainer');
        const previewContainer = document.getElementById('previewContainer');
        const preview = document.getElementById('preview');
        const captureBtn = document.getElementById('captureBtn');
        const retakeBtn = document.getElementById('retakeBtn');

        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            navigator.mediaDevices.getUserMedia({ video: true }).then(function(stream) {
                video.srcObject = stream;
                video.play();
            }).catch(function(err) {
                console.log("Camera access error:", err);
            });
        }

        function takeSnapshot() {
            canvas.width = video.videoWidth || 320;
            canvas.height = video.videoHeight || 240;
            const context = canvas.getContext('2d');
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            
            const imageData = canvas.toDataURL('image/png');
            photoData.value = imageData;
            preview.src = imageData;

            // Show captured image preview & Retake button; hide live camera stream & Capture button
            videoContainer.classList.add('d-none');
            previewContainer.classList.remove('d-none');
            captureBtn.classList.add('d-none');
            retakeBtn.classList.remove('d-none');
        }

        function retakePhoto() {
            photoData.value = '';
            preview.src = '';

            // Hide captured preview & Retake button; show live camera stream & Capture button
            previewContainer.classList.add('d-none');
            videoContainer.classList.remove('d-none');
            retakeBtn.classList.add('d-none');
            captureBtn.classList.remove('d-none');
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
