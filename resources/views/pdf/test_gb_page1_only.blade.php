<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>GOLD PURCHASE AGREEMENT & CUSTOMER DECLARATION</title>
    <style>
        @page {
            margin: 12pt 18pt 36pt 18pt;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 7.6pt;
            line-height: 1.28;
            color: #1e293b;
        }
        footer {
            position: fixed;
            bottom: -24pt;
            left: 0pt;
            right: 0pt;
            height: 24pt;
            font-size: 6.4pt;
            color: #475569;
            border-top: 0.8pt solid #cbd5e1;
            padding-top: 2pt;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2pt;
        }
        .header-table h2 {
            margin: 0;
            color: #0052cc;
            font-size: 14pt;
            line-height: 1.1;
            letter-spacing: 0.5pt;
        }
        .header-subtext {
            font-size: 6.8pt;
            line-height: 1.25;
            color: #334155;
        }
        .doc-title {
            text-align: center;
            font-size: 9.6pt;
            font-weight: bold;
            color: #0052cc;
            text-transform: uppercase;
            margin: 2.5pt 0 1.5pt 0;
            letter-spacing: 0.4pt;
        }
        .doc-subtitle {
            text-align: center;
            font-size: 7.2pt;
            font-style: italic;
            color: #475569;
            margin-bottom: 3.5pt;
        }
        .section-header {
            font-size: 7.6pt;
            font-weight: bold;
            color: #0052cc;
            text-transform: uppercase;
            background-color: #fff1f2;
            border-left: 2.5pt solid #0052cc;
            padding: 2.2pt 4.5pt;
            margin-top: 4.5pt;
            margin-bottom: 2pt;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2.5pt;
        }
        .info-table td {
            padding: 2.6pt 4pt;
            vertical-align: middle;
            font-size: 7.3pt;
            line-height: 1.25;
        }
        .label {
            font-weight: bold;
            color: #334155;
        }
        .signature-img {
            height: 26pt;
            max-width: 110pt;
        }
        .page-break {
            page-break-after: always;
        }
        .doc-image-wrapper {
            text-align: center;
            margin: 0 auto;
            width: 100%;
            page-break-inside: avoid;
        }
        .doc-image {
            width: 100%;
            max-width: 100%;
            max-height: 750pt;
            height: auto;
            margin: 0 auto;
            border: 1pt solid #cbd5e1;
            border-radius: 4pt;
            display: block;
        }
        .checkbox {
            font-family: DejaVu Sans, sans-serif;
            font-size: 7.5pt;
        }
        .rupee {
            font-family: DejaVu Sans, sans-serif;
        }
        .verify-badge {
            background-color: #dcfce7;
            color: #15803d;
            border: 0.8pt solid #86efac;
            padding: 1.5pt 4pt;
            border-radius: 3pt;
            font-weight: bold;
            font-size: 6.8pt;
            display: inline-block;
            font-family: DejaVu Sans, sans-serif;
        }
        .sig-box {
            border: 0.8pt solid #cbd5e1;
            background-color: #f8fafc;
            border-radius: 3pt;
            padding: 4pt 5pt;
        }
    </style>
</head>
<body>

    @php
        $borrowerSignaturePath = $kyc->borrower_signature ? storage_path('app/public/' . $kyc->borrower_signature) : null;
        $borrowerSigBase64 = ($borrowerSignaturePath && file_exists($borrowerSignaturePath) && filesize($borrowerSignaturePath) > 50 && @getimagesize($borrowerSignaturePath)) ? base64_encode(file_get_contents($borrowerSignaturePath)) : null;

        $witnessSignaturePath = $kyc->witness_signature ? storage_path('app/public/' . $kyc->witness_signature) : null;
        $witnessSigBase64 = ($witnessSignaturePath && file_exists($witnessSignaturePath) && filesize($witnessSignaturePath) > 50 && @getimagesize($witnessSignaturePath)) ? base64_encode(file_get_contents($witnessSignaturePath)) : null;

        $profileImageSrc = null;
        if (!empty($kyc->profile_image)) {
            if (str_starts_with($kyc->profile_image, 'data:image')) {
                $profileImageSrc = $kyc->profile_image;
            } elseif (str_starts_with($kyc->profile_image, '/9j/') || str_starts_with($kyc->profile_image, 'iVBOR') || strlen($kyc->profile_image) > 100) {
                $profileImageSrc = 'data:image/jpeg;base64,' . $kyc->profile_image;
            } elseif (file_exists(storage_path('app/public/' . $kyc->profile_image))) {
                $profileImageSrc = 'data:image/jpeg;base64,' . base64_encode(file_get_contents(storage_path('app/public/' . $kyc->profile_image)));
            }
        }
        if (!$profileImageSrc && $kyc->eye_blink_image && file_exists(public_path($kyc->eye_blink_image))) {
            $profileImageSrc = 'data:image/png;base64,' . base64_encode(file_get_contents(public_path($kyc->eye_blink_image)));
        }

        $rawAddress = $kyc->address;
        $address = is_string($rawAddress) && json_decode($rawAddress, true) ? json_decode($rawAddress, true) : $rawAddress;
        $formattedAddress = is_array($address) 
            ? (($address['house'] ?? '') . ', ' . ($address['street'] ?? '') . ', ' . ($address['vtc'] ?? '') . ', ' . ($address['dist'] ?? '') . ' ' . ($address['post'] ?? '') . ', ' . ($address['state'] ?? '') . ', ' . ($address['country'] ?? '') . ' ' . ($kyc->zip ?? ''))
            : ($address . ' ' . ($kyc->zip ?? ''));

        $amount = (float)($kyc->amount ?? 163200);

        if (!function_exists('pdfNumberToWords')) {
            function pdfNumberToWords($num) {
                if (!$num || $num <= 0) return '';
                $num = (int)$num;
                $a = array('', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten', 
                           'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen');
                $b = array('', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety');

                $inWords = function($n) use (&$inWords, $a, $b) {
                    if ($n < 20) return $a[$n];
                    if ($n < 100) return $b[(int)($n / 10)] . ($n % 10 !== 0 ? ' ' . $a[$n % 10] : '');
                    if ($n < 1000) return $a[(int)($n / 100)] . ' Hundred' . ($n % 100 !== 0 ? ' ' . $inWords($n % 100) : '');
                    if ($n < 100000) return $inWords((int)($n / 1000)) . ' Thousand' . ($n % 1000 !== 0 ? ' ' . $inWords($n % 1000) : '');
                    if ($n < 10000000) return $inWords((int)($n / 100000)) . ' Lakh' . ($n % 100000 !== 0 ? ' ' . $inWords($n % 100000) : '');
                    return $inWords((int)($n / 10000000)) . ' Crore' . ($n % 10000000 !== 0 ? ' ' . $inWords($n % 10000000) : '');
                };
                return 'Rupees ' . $inWords($num) . ' only';
            }
        }

        $amountWords = pdfNumberToWords($amount);
    @endphp

    <!-- Running Page Footer on ALL Pages -->
    <footer>
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 55%; text-align: left; vertical-align: middle;">
                    <strong style="color: #0052cc;">POJO FINSERV</strong> &bull; Digitally Signed via Aadhaar OTP &bull;
                    <strong>IP:</strong> {{ $kyc->ip_address ?? request()->ip() }} &bull; 
                    <strong>Date:</strong> {{ \Carbon\Carbon::parse($kyc->created_at ?? now())->format('d-m-Y h:i A') }}
                </td>
                <td style="width: 45%; text-align: right; vertical-align: middle;">
                    <span style="font-weight: bold; text-transform: uppercase;">{{ strtoupper($kyc->verified_name ?? $kyc->full_name ?? 'CUSTOMER') }}</span> &bull; 
                    <span style="color: #15803d; font-family: DejaVu Sans, sans-serif; font-weight: bold;">&#10004; Aadhaar Verified</span>
                </td>
            </tr>
        </table>
    </footer>

    <!-- PAGE 1: ALL DETAILS COMBINED ON ONE PAGE -->
    <table class="header-table">
        <tr>
            <td style="width: 78%; vertical-align: middle;">
                <h2>POJO FINSERV</h2>
                <div style="font-size: 8pt; color: #64748b; margin-top: 1pt;">(A Licensed Private Financier)</div>
                <div class="header-subtext">Address: D.No- 02/628, Mahil Nagar, Athappampalayam, Mel thindal, Erode - 638012.</div>
                <div class="header-subtext">Mobile: 9994777004 | Email: Pojofinserv@outlook.com</div>
            </td>
            <td style="width: 22%; text-align: right; vertical-align: middle;">
                @if($profileImageSrc)
                    <img src="{{ $profileImageSrc }}" style="height: 50pt; width: 50pt; border-radius: 4pt; border: 1pt solid #0052cc;">
                    <div style="font-size: 7pt; color: #475569; margin-top: 2pt;">
                        IP: {{ $kyc->ip_address ?? request()->ip() }}<br>
                        {{ \Carbon\Carbon::parse($kyc->created_at ?? now())->format('d/m/Y h:i A') }}
                    </div>
                @endif
            </td>
        </tr>
    </table>

    <div class="doc-title">GOLD PURCHASE AGREEMENT / CUSTOMER DECLARATION</div>
    <div class="doc-subtitle">Direct Gold Buying & Purchase Service</div>

    <table class="info-table" style="background-color: #f8fafc; border: 1pt solid #cbd5e1; margin-bottom: 5pt;">
        <tr>
            <td style="width: 32%; padding: 3pt 6pt;"><span class="label">Purchase No.:</span> GP-{{ $kyc->id }}-{{ date('Ymd') }}</td>
            <td style="width: 24%; padding: 3pt 6pt;"><span class="label">Date:</span> {{ date('d / m / Y') }}</td>
            <td style="width: 24%; padding: 3pt 6pt;"><span class="label">Place:</span> COIMBATORE</td>
            <td style="width: 20%; padding: 3pt 6pt; text-align: right;"><span class="label">Branch:</span> ERODE</td>
        </tr>
    </table>

    <!-- 1. CUSTOMER DETAILS -->
    <div class="section-header">1. CUSTOMER DETAILS</div>
    <table class="info-table">
        <tr>
            <td style="width: 50%;"><span class="label">Full Name:</span> {{ strtoupper($kyc->full_name ?? '') }}</td>
            <td style="width: 50%;"><span class="label">Father / Husband Name (S/o / W/o):</span> {{ strtoupper($kyc->care_of ?? '') }}</td>
        </tr>
        <tr>
            <td><span class="label">Mobile No.:</span> {{ $kyc->phone }}</td>
            <td><span class="label">PAN No.:</span> {{ strtoupper($kyc->pan_number ?? '') }}</td>
        </tr>
        <tr>
            <td colspan="2"><span class="label">Aadhaar No.:</span> {{ $kyc->aadhaar_number }}</td>
        </tr>
        <tr>
            <td colspan="2"><span class="label">Residing Address:</span> {{ strtoupper($formattedAddress) }}</td>
        </tr>
    </table>
    <p style="margin: 2pt 0 4pt 0; font-size: 8pt; font-style: italic;">
        hereby confirm that I have voluntarily sold the following gold ornaments to <strong>POJO FINSERV</strong>.
    </p>

    <!-- 2. GOLD DETAILS -->
    <div class="section-header">2. GOLD DETAILS</div>
    <table class="info-table">
        <tr>
            <td style="width: 50%;"><span class="label">Gold Article:</span> {{ strtoupper($kyc->gold_article ?? 'Gold Ornaments') }}</td>
            <td style="width: 50%;"><span class="label">Gross Weight:</span> {{ $kyc->gross_weight ?? 'N/A' }} grams</td>
        </tr>
        <tr>
            <td><span class="label">Net Gold Weight:</span> {{ $kyc->net_weight ?? 'N/A' }} grams</td>
            <td><span class="label">Gold Rate:</span> <span class="rupee">&#8377;</span> {{ number_format((float)($kyc->gold_rate ?? 0)) }} per gram</td>
        </tr>
        <tr>
            <td>
                <span class="label">GST Rate:</span> {{ $kyc->gst_percent ? $kyc->gst_percent . '%' : '3%' }}
                @if($kyc->gst_amount)
                    (<span class="rupee">&#8377;</span> {{ number_format((float)$kyc->gst_amount, 2) }})
                @endif
            </td>
            <td><span class="label">Total Purchase Amount:</span> <span class="rupee">&#8377;</span> {{ number_format($amount, 2) }}</td>
        </tr>
        <tr>
            <td colspan="2"><span class="label">Amount in Words:</span> {{ strtoupper($amountWords) }}</td>
        </tr>
    </table>

    <!-- 3. CUSTOMER DECLARATION (ONE BY ONE) -->
    <div class="section-header">3. CUSTOMER DECLARATION</div>
    <p style="margin: 1pt 0 1.5pt 0; font-size: 6.9pt; font-weight: bold; color: #334155;">I hereby declare that:</p>
    <ol style="margin: 1pt 0; padding-left: 13pt; font-size: 6.5pt; line-height: 1.22;">
        <li>The above gold belongs to me / I am legally authorized to sell it.</li>
        <li>The gold is not stolen, pledged, mortgaged or subject to any known dispute or third-party claim.</li>
        <li>I have voluntarily sold the above gold to POJO FINSERV.</li>
        <li>The weight, purity and purchase value have been explained to me and accepted by me.</li>
        <li>I have received the agreed purchase amount from POJO FINSERV.</li>
        <li>I have provided my correct KYC details.</li>
        <li>I have completed Aadhaar OTP authentication for identity/KYC verification at the time of this transaction.</li>
        <li>I hereby provide my consent to POJO FINSERV to contact me from time to time for marketing, promotional, product/service-related information, offers, updates and other business communications.</li>
    </ol>

    <!-- 4. PAYMENT DETAILS -->
    <div class="section-header">4. PAYMENT DETAILS</div>
    <table class="info-table">
        <tr>
            <td style="width: 50%;">
                <span class="label">Payment Mode:</span> 
                <span class="checkbox">{{ strtoupper($kyc->payment_mode ?? 'Bank Transfer') === 'BANK TRANSFER' ? '☑' : '☐' }}</span> Bank Transfer &nbsp;&nbsp; 
                <span class="checkbox">{{ strtoupper($kyc->payment_mode ?? '') === 'UPI' ? '☑' : '☐' }}</span> UPI &nbsp;&nbsp; 
                <span class="checkbox">{{ strtoupper($kyc->payment_mode ?? '') === 'CASH' ? '☑' : '☐' }}</span> CASH
            </td>
            <td style="width: 50%;"><span class="label">Transaction / UTR No.:</span> {{ strtoupper(!empty($kyc->utr_ref) ? $kyc->utr_ref : 'N/A') }}</td>
        </tr>
        <tr>
            <td><span class="label">Name as per Bank Account:</span> {{ strtoupper($kyc->bank_name_ifsc ?? $kyc->full_name ?? '') }}</td>
            <td><span class="label">Amount Paid:</span> <span class="rupee">&#8377;</span> {{ number_format($amount, 2) }}</td>
        </tr>
        <tr>
            <td><span class="label">Bank Account No.:</span> {{ strtoupper($kyc->bank_acc ?? 'N/A') }}</td>
            <td><span class="label">IFSC Code:</span> {{ strtoupper($kyc->ifsc_code ?? 'N/A') }}</td>
        </tr>
    </table>

    <!-- 5. CUSTOMER DIGITAL VERIFICATION (ONE BY ONE) -->
    <div class="section-header">5. CUSTOMER DIGITAL VERIFICATION</div>
    <table class="info-table" style="border: 0.6pt solid #cbd5e1; background-color: #f8fafc; margin-bottom: 2.5pt;">
        <tr>
            <td style="width: 58%; vertical-align: middle; padding: 2.5pt 4pt;">
                <span class="label">Customer Name:</span> {{ strtoupper($kyc->full_name ?? '') }}<br>
                <span class="label">Mobile:</span> {{ $kyc->aadhaar_linked_mobile ?? $kyc->phone ?? 'N/A' }} &nbsp;|&nbsp; <span class="label">Date:</span> {{ date('d / m / Y') }} &nbsp;|&nbsp; <span class="label">Place:</span> COIMBATORE<br>
                <span style="font-size: 5.8pt; color: #64748b;">IP: {{ $kyc->ip_address ?? request()->ip() ?? '223.185.27.16' }} | Timestamp: {{ \Carbon\Carbon::parse($kyc->created_at ?? now())->format('d/m/Y h:i A') }}</span>
            </td>
            <td style="width: 42%; text-align: right; vertical-align: middle; padding: 2.5pt 4pt;">
                <div style="font-family: 'Courier New', Courier, monospace; font-size: 6.2pt; border: 0.8pt solid #000; padding: 2pt 3.5pt; text-align: left; display: inline-block; line-height: 1.15; background-color: #ffffff;">
                    <strong style="text-transform: uppercase;">{{ strtoupper($kyc->full_name ?? '') }}</strong><br>
                    <span style="font-size: 5.5pt; color: #334155;">Date: {{ \Carbon\Carbon::parse($kyc->signed_at ?? $kyc->created_at ?? now())->format('Y.m.d H:i:s') }} (UTC+05:30)</span><br>
                    <span style="color: #15803d; font-family: DejaVu Sans, sans-serif; font-weight: bold;">&#10004; Aadhaar Sign</span><br>
                    <span style="color: #64748b; font-size: 4.8pt; border-top: 0.5pt dashed #94a3b8; display: block; margin-top: 1pt; padding-top: 1pt;">eSign by QuickeKyc / POJO FINSERV</span>
                </div>
            </td>
        </tr>
    </table>

    <!-- 6. FOR POJO FINSERV (ONE BY ONE) -->
    <div class="section-header">6. FOR POJO FINSERV</div>
    <table class="info-table" style="border: 0.6pt solid #cbd5e1; background-color: #f8fafc;">
        <tr>
            <td style="width: 60%; vertical-align: middle; padding: 2.5pt 4pt;">
                <span class="label">Authorised Signatory:</span> Dhanapal G (Authorized Officer)<br>
                <span style="font-size: 5.8pt; color: #64748b;">Company Seal: POJO FINSERV OFFICIAL SEAL &nbsp;|&nbsp; Date: {{ date('d / m / Y') }}</span>
            </td>
            <td style="width: 40%; text-align: right; vertical-align: middle; padding: 2.5pt 4pt;">
                @if(file_exists(public_path('images/sign.png')))
                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/sign.png'))) }}" style="height: 18pt;">
                @else
                    <div style="height: 14pt; border-bottom: 0.8pt dashed #94a3b8; width: 100pt; display: inline-block;"></div>
                    <span style="font-size: 5.2pt; color: #64748b; display: block;">Signature</span>
                @endif
            </td>
        </tr>
    </table>

    </body></html>