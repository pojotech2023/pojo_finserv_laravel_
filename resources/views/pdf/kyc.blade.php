{{--  pdf/kyc.blade.php  --}}
<!DOCTYPE html>
<html>
<head>
    <title>Download Promissory</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <link  href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body            { margin:0;padding:20px;background:#f8f9fa;display:flex;justify-content:center;align-items:flex-start; }
        .capture        { width:100%;min-height:1123px;background:#fff;padding:20px;box-shadow:0 0 10px rgba(0,0,0,.15);position:relative;box-sizing:border-box; }
        .form-wrapper   { width:100%;font-size:16px;line-height:1.8; }
        .seal-overlay   { position:absolute;top:0;left:0;width:100%;height:100%;opacity:.35;z-index:10;pointer-events:none; }
        .border-input   { border:none;border-bottom:1px solid #ccc;background:transparent;display:inline-block;padding:2px 4px; }
        .logo           { max-height:80px; }
        p               { margin-bottom:4px !important; }
    </style>
</head>
<body>

@php
    /** ----------------------------------------------------
     *  Build the meta line ONCE and pass to the partial.
     *  -------------------------------------------------- */
    use Illuminate\Support\Str;
    use Carbon\Carbon;

    $signedAt = $kyc->signed_at
        ? Carbon::parse($kyc->signed_at)->timezone('Asia/Kolkata')
        : Carbon::parse($kyc->created_at)->timezone('Asia/Kolkata');

    $metaLine = sprintf(
        'Signed on %s from IP %s | Device: %s',
        $signedAt->format('d-m-Y H:i'),
        $kyc->ip_address ?? '—',
        Str::limit($kyc->device_id ?? 'Unknown Device', 50, '…')
    );
@endphp

<div class="capture" id="form-wrapper">
    {{-- Pass BOTH the $kyc object and the freshly‑made $metaLine --}}
    @include('promissory_image_convert', ['kyc' => $kyc, 'metaLine' => $metaLine])
</div>

<script>
window.onload = () => {
    setTimeout(() => {
        html2canvas(document.getElementById('form-wrapper'), {
            backgroundColor: '#ffffff',
            scale: 2,
            useCORS: true,
            scrollX: 0, scrollY: 0,
            windowWidth: 1000
        }).then(canvas => {
            const link    = document.createElement('a');
            link.href     = canvas.toDataURL('image/png');
            link.download = 'Stamped_Promissory_{{ Str::slug($kyc->full_name) }}.png';
            link.click();
            setTimeout(() => window.close(), 500);
        });
    }, 700);
};
</script>

</body>
</html>
