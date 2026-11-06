<!DOCTYPE html>
<html>
<head>
    <title>Download Promissory</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 20px;
            background: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }

        .capture {
            width: 100%;  /* Exact A4 width at 96 DPI */
            min-height: 1123px;  /* Exact A4 height */
            background: #ffffff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            position: relative;
            box-sizing: border-box;
        }

        .form-wrapper {
            width: 100%;
            font-size: 16px;
            line-height: 1.8;
        }

        .seal-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0.35;
            z-index: 10;
            pointer-events: none;
        }

        .border-input {
            border: none;
            border-bottom: 1px solid #ccc;
            background: transparent;
            display: inline-block;
            padding: 2px 4px;
        }

        .logo {
            max-height: 80px;
        }

        p {
            margin-bottom: 4px !important;
        }
    </style>
</head>
<body>

<div class="capture" id="form-wrapper">
    @include('stamp', ['kyc' => $kyc])
    @if(file_exists(storage_path('app/public/promissory_stamps/705c1bee-fd33-47dd-8dfe-9fc27aade96a.png')))
    <img src="{{ asset('storage/promissory_stamps/705c1bee-fd33-47dd-8dfe-9fc27aade96a.png') }}" class="seal-overlay" />
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
    // Fetch IP address from a public API
    fetch('https://api.ipify.org?format=json')
        .then(res => res.json())
        .then(data => {
            if(document.getElementById('ip_borrower')) document.getElementById('ip_borrower').textContent = data.ip;
            if(document.getElementById('ip_witness')) document.getElementById('ip_witness').textContent = data.ip;
        })
        .catch(() => {});

    // Set local time
    const now = new Date().toLocaleString('en-IN', {
        timeZone: 'Asia/Kolkata',
        hour12: true,
        year: 'numeric', month: 'long', day: 'numeric',
        hour: '2-digit', minute: '2-digit', second: '2-digit'
    });

    if(document.getElementById('time_borrower')) document.getElementById('time_borrower').textContent = now;
    if(document.getElementById('time_witness')) document.getElementById('time_witness').textContent = now;
});

window.onload = function () {
    setTimeout(() => {
        const target = document.getElementById('form-wrapper');
        if (typeof html2canvas === 'undefined') {
            console.error('html2canvas library not loaded');
            return;
        }
        html2canvas(target, {
            backgroundColor: '#ffffff',
            scale: 2,
            useCORS: true,
            allowTaint: true,
            scrollX: 0,
            scrollY: 0,
            windowWidth: 1000  // Ensures full right-side content is rendered
        }).then(canvas => {
            const link = document.createElement('a');
            link.href = canvas.toDataURL('image/png');
            link.download = "Stamped_Promissory_{{ Str::slug($kyc->full_name ?? 'customer') }}.png";
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            setTimeout(() => window.close(), 1500);
        }).catch(err => {
            console.error('html2canvas error during capture:', err);
        });
    }, 700);
}
</script>

</body>
</html>
