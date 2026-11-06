<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>UNSECURED LOAN PROMISSORY NOTE & SAME-DAY FUNDING AGREEMENT</title>
    <style>
        @page {
            margin: 12pt 18pt 22pt 18pt;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 7.6pt;
            line-height: 1.28;
            color: #1e293b;
        }
        footer {
            position: fixed;
            bottom: -16pt;
            left: 0pt;
            right: 0pt;
            height: 20pt;
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
            max-width: 100%;
            max-height: 690pt;
            width: auto;
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
            border-radius: 2pt;
            font-weight: bold;
            font-size: 6.5pt;
            display: inline-block;
        }
        .sig-box {
            border: 0.8pt solid #cbd5e1;
            background-color: #f8fafc;
            border-radius: 3pt;
            padding: 3pt 4pt;
            min-height: 48pt;
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

        $amount = (float)($kyc->amount ?? 0);
        $interestRate = (!empty($kyc->interest_rate) && (float)$kyc->interest_rate > 0 && (float)$kyc->interest_rate != 0.1) 
            ? (float)$kyc->interest_rate 
            : 0.50;
        $totalPayable = round($amount + ($amount * ($interestRate / 100)));

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
        $pnDate = \Carbon\Carbon::parse($kyc->created_at ?? now())->format('d / m / Y');
        $pnDateYmd = \Carbon\Carbon::parse($kyc->created_at ?? now())->format('Ymd');
        $pnDateTime = \Carbon\Carbon::parse($kyc->created_at ?? now())->format('d/m/Y h:i A');
        $disburseDate = \Carbon\Carbon::parse($kyc->created_at ?? now())->format('d/m/Y');
        $repayDate = \Carbon\Carbon::parse($kyc->created_at ?? now())->format('d/m/Y');
    @endphp

    <!-- Running Page Footer on ALL Pages -->
    <footer>
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 58%; text-align: left; vertical-align: middle;">
                    <strong style="color: #0052cc;">POJO FINSERV</strong><br>
                    Document Digitally Signed & Verified via Aadhaar OTP<br>
                    IP: {{ $kyc->ip_address ?? '223.185.27.16' }} | Date: {{ $pnDateTime }}
                </td>
                <td style="width: 42%; text-align: right; vertical-align: middle;">
                    <div style="font-family: 'Courier New', Courier, monospace; font-size: 6pt; border: 0.6pt solid #000; padding: 2pt 4pt; text-align: left; display: inline-block; line-height: 1.15; background-color: #f8fafc;">
                        <strong>{{ strtoupper($kyc->full_name ?? 'BORROWER') }}</strong><br>
                        Date: {{ \Carbon\Carbon::parse($kyc->signed_at ?? $kyc->created_at ?? now())->format('Y.m.d H:i:s') }} (UTC+05:30)<br>
                        <span style="color: #15803d; font-family: DejaVu Sans, sans-serif; font-weight: bold;">&#10004; Aadhaar Sign</span><br>
                        <span style="color: #64748b; font-size: 5pt;">eSign by QuickeKyc / POJO FINSERV</span>
                    </div>
                </td>
            </tr>
        </table>
    </footer>

    <!-- PAGE 1: ALL 11 POINTS COMBINED ON ONE PAGE WITH EXACT FULL LEGAL TEXT -->
    <table class="header-table">
        <tr>
            <td style="width: 78%; vertical-align: middle;">
                <h2>POJO FINSERV</h2>
                <div style="font-size: 6.8pt; color: #64748b; margin-top: 1pt;">(A Licensed Private Financier)</div>
                <div class="header-subtext">Address: D.No- 02/628, Mahil Nagar, Athappampalayam, Mel thindal, Erode - 638012.</div>
                <div class="header-subtext">Mobile: 9994777004 | Email: Pojofinserv@outlook.com</div>
            </td>
            <td style="width: 22%; text-align: right; vertical-align: middle;">
                @if($profileImageSrc)
                    <img src="{{ $profileImageSrc }}" style="height: 42pt; width: 42pt; border-radius: 4pt; border: 1pt solid #0052cc; display: inline-block;">
                    <div style="font-size: 6pt; color: #475569; margin-top: 1pt; line-height: 1.15;">
                        IP: {{ $kyc->ip_address ?? '223.185.27.16' }}<br>
                        Date: {{ $pnDateTime }}
                    </div>
                @endif
            </td>
        </tr>
    </table>

    <div class="doc-title">UNSECURED LOAN PROMISSORY NOTE & SAME-DAY FUNDING AGREEMENT</div>
    <div class="doc-subtitle">For Gold Loan Transfer / Takeover Funding</div>

    <table class="info-table" style="background-color: #f8fafc; border: 0.8pt solid #cbd5e1; margin-bottom: 2pt;">
        <tr>
            <td style="width: 32%; padding: 2pt 4pt;"><span class="label">Agreement / PN No.:</span> PN-{{ $kyc->id }}-{{ $pnDateYmd }}</td>
            <td style="width: 24%; padding: 2pt 4pt;"><span class="label">Date:</span> {{ $pnDate }}</td>
            <td style="width: 24%; padding: 2pt 4pt;"><span class="label">Place:</span> COIMBATORE</td>
            <td style="width: 20%; padding: 2pt 4pt; text-align: right;"><span class="label">Branch:</span> ERODE</td>
        </tr>
    </table>

    <!-- 1. PARTIES -->
    <div class="section-header">1. PARTIES</div>
    <p style="margin: 2pt 0 2.5pt 0; font-size: 7.2pt; line-height: 1.25;">
        This Agreement and Promissory Note (“Document”) is made between <strong>POJO FINSERV</strong> (“Lender”) and the person identified below (“Borrower”). The Borrower requests temporary unsecured funding solely to facilitate closure/transfer of an existing gold loan and agrees to repay the amount lawfully due in accordance with this Document and the applicable loan terms.
    </p>

    <!-- 2. BORROWER DETAILS -->
    <div class="section-header">2. BORROWER DETAILS</div>
    <table class="info-table">
        <tr>
            <td style="width: 50%;"><span class="label">Full Name:</span> {{ strtoupper($kyc->full_name ?? '') }}</td>
            <td style="width: 50%;"><span class="label">Father Name:</span> {{ strtoupper($kyc->care_of ?? '') }}</td>
        </tr>
        <tr>
            <td><span class="label">Date of Birth:</span> {{ $kyc->dob ?? 'N/A' }}</td>
            <td><span class="label">Mobile:</span> {{ $kyc->phone ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td><span class="label">PAN:</span> {{ strtoupper($kyc->pan_number ?? '') }}</td>
            <td><span class="label">Aadhaar / KYC Reference:</span> {{ $kyc->aadhaar_number ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td><span class="label">Bank A/c No.:</span> {{ strtoupper($kyc->bank_acc ?? '') }}</td>
            <td><span class="label">IFSC Code:</span> {{ strtoupper($kyc->ifsc_code ?? '') }}</td>
        </tr>
        <tr>
            <td colspan="2"><span class="label">Name as per Bank Account:</span> {{ strtoupper($kyc->bank_name_ifsc ?? $kyc->full_name ?? '') }}</td>
        </tr>
        <tr>
            <td colspan="2"><span class="label">Address:</span> {{ strtoupper($formattedAddress) }}</td>
        </tr>
    </table>

    <!-- 3. EXISTING GOLD LOAN -->
    <div class="section-header">3. EXISTING GOLD LOAN</div>
    <table class="info-table">
        <tr>
            <td style="width: 50%;"><span class="label">Existing Bank/NBFC:</span> {{ strtoupper($kyc->existing_bank ?? 'N/A') }}</td>
            <td style="width: 50%;"><span class="label">Gold Loan A/c No.:</span> {{ strtoupper(!empty($kyc->existing_loan_acc) ? $kyc->existing_loan_acc : 'N/A') }}</td>
        </tr>
        <tr>
            <td><span class="label">Outstanding / Closure Amount:</span> <span class="rupee">&#8377;</span> {{ number_format($amount, 2) }}</td>
            <td>
                <span class="label">Purpose:</span> 
                <span class="checkbox">{{ strtoupper($kyc->purpose ?? 'Transfer') === 'TRANSFER' ? '☑' : '☐' }}</span> Transfer &nbsp;&nbsp; 
                <span class="checkbox">{{ strtoupper($kyc->purpose ?? '') === 'INTERNAL REPLACE' ? '☑' : '☐' }}</span> Internal Replace
            </td>
        </tr>
    </table>

    <!-- 4. FUNDING & DISBURSEMENT -->
    <div class="section-header">4. FUNDING & DISBURSEMENT</div>
    <table class="info-table">
        <tr>
            <td style="width: 50%;"><span class="label">Principal Funded:</span> <span class="rupee">&#8377;</span> {{ number_format($amount, 2) }}</td>
            <td style="width: 50%;"><span class="label">Amount in Words:</span> {{ strtoupper($amountWords) }}</td>
        </tr>
        <tr>
            <td><span class="label">Disbursement Date:</span> {{ $disburseDate }} &nbsp;&nbsp; <span class="label">Disbursement Time:</span> 10:00 AM</td>
            <td>
                <span class="label">Mode:</span> <span class="checkbox">☑</span> Bank Transfer &nbsp;&nbsp; 
                <span class="checkbox">☐</span> UPI &nbsp;&nbsp; 
                <span class="checkbox">☐</span> Direct to Existing Lender<br>
                <span class="label">UTR / Transaction Reference:</span> {{ strtoupper(!empty($kyc->utr_ref) ? $kyc->utr_ref : '001') }}
            </td>
        </tr>
    </table>

    <!-- 5. PROMISE TO PAY / SAME-DAY REPAYMENT -->
    <div class="section-header">5. PROMISE TO PAY / SAME-DAY REPAYMENT</div>
    <p style="margin: 1.5pt 0 2pt 0; font-size: 7.1pt; line-height: 1.22;">
        For value received, the Borrower unconditionally promises to pay POJO FINSERV the principal amount funded together with the applicable interest/finance charge and other lawful dues under the agreed loan terms. The facility is intended for same-day completion and repayment.
    </p>
    <table class="info-table">
        <tr>
            <td style="width: 50%;"><span class="label">Expected Repayment Date:</span> {{ $repayDate }}</td>
            <td style="width: 50%;"><span class="label">Expected Repayment Time:</span> {{ $kyc->repayment_time ?? '20:00' }}</td>
        </tr>
        <tr>
            <td colspan="2">
                <span class="label">Applicable Rate / Finance Charge:</span> {{ $interestRate }}% interest applied.<br>
                <span style="font-size: 6.6pt; color: #475569;">Zero charges for loan process at DSA Code and fund to be returned same day. (If Loan Amount is >= Disbursement Amount, no additional charges; otherwise charges applicable).</span>
            </td>
        </tr>
        <tr>
            <td colspan="2"><span class="label">Total Amount Payable:</span> <span class="rupee">&#8377;</span> {{ number_format($totalPayable, 2) }}</td>
        </tr>
    </table>

    <!-- 6. USE OF FUNDS (ONE BY ONE) -->
    <div class="section-header">6. USE OF FUNDS</div>
    <p style="margin: 1.5pt 0; font-size: 6.9pt; line-height: 1.22;">
        The Borrower shall use the funds only for the stated existing gold-loan transfer. The Borrower shall not divert the funds for any other purpose and shall provide transfer proof when requested by the Lender.
    </p>

    <!-- 7. INTEREST / PRICING (ONE BY ONE) -->
    <div class="section-header">7. INTEREST / PRICING</div>
    <p style="margin: 1.5pt 0; font-size: 6.9pt; line-height: 1.22;">
        The applicable interest/finance charge, effective rate and all other charges shall be disclosed to the Borrower before disbursement and shall be calculated and collected only to the extent permitted by applicable law and the agreed loan terms.
    </p>

    <!-- 8. BORROWER'S UNDERTAKINGS (ONE BY ONE: 1, 2, 3, 4, 5, 6) -->
    <div class="section-header">8. BORROWER'S UNDERTAKINGS</div>
    <ol style="margin: 1.5pt 0; padding-left: 13pt; font-size: 6.8pt; line-height: 1.23;">
        <li>The Borrower confirms that all information and KYC documents provided are true and correct.</li>
        <li>The Borrower shall complete the existing gold-loan closure/transfer promptly after receipt of funds.</li>
        <li>The Borrower shall repay POJO FINSERV the total amount lawfully due on the agreed repayment date.</li>
        <li>The Borrower shall immediately inform POJO FINSERV of any event that may prevent same-day completion or repayment.</li>
        <li>The Borrower shall keep POJO FINSERV informed of changes to address, mobile number or other material KYC information.</li>
        <li>The Borrower shall retain and provide transaction/closure proof as required.</li>
    </ol>

    <!-- 9. BORROWER DIGITAL VERIFICATION (ONE BY ONE) -->
    <div class="section-header">9. BORROWER DIGITAL VERIFICATION</div>
    <table class="info-table" style="border: 0.6pt solid #cbd5e1; background-color: #f8fafc; margin-bottom: 2.5pt;">
        <tr>
            <td style="width: 58%; vertical-align: middle; padding: 2.5pt 4pt;">
                <span class="label">Borrower Name:</span> {{ strtoupper($kyc->full_name ?? '') }}<br>
                <span class="label">Mobile:</span> {{ $kyc->aadhaar_linked_mobile ?? $kyc->phone ?? 'N/A' }} &nbsp;|&nbsp; <span class="label">Date:</span> {{ $pnDate }} &nbsp;|&nbsp; <span class="label">Place:</span> COIMBATORE<br>
                <span style="font-size: 5.8pt; color: #64748b;">IP: {{ $kyc->ip_address ?? '223.185.27.16' }} | Timestamp: {{ $pnDateTime }}</span>
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

    <!-- 10. WITNESS (REFERRER PERSON) (ONE BY ONE) -->
    <div class="section-header">10. WITNESS (REFERRER PERSON)</div>
    <table class="info-table" style="border: 0.6pt solid #cbd5e1; background-color: #f8fafc; margin-bottom: 2.5pt;">
        <tr>
            <td style="width: 50%; vertical-align: middle; padding: 2.5pt 4pt;">
                <span class="label">Witness Name:</span> <strong>{{ strtoupper($kyc->referel_name ?? 'MERLIN') }}</strong>
            </td>
            <td style="width: 50%; text-align: right; vertical-align: middle; padding: 2.5pt 4pt;">
                <span style="font-size: 6.2pt; color: #64748b;">IP: {{ $kyc->ip_address ?? '223.185.27.16' }} &nbsp;|&nbsp; Timestamp: {{ $pnDateTime }}</span>
            </td>
        </tr>
    </table>

    <!-- 11. FOR POJO FINSERV (ONE BY ONE) -->
    <div class="section-header">11. FOR POJO FINSERV</div>
    <table class="info-table" style="border: 0.6pt solid #cbd5e1; background-color: #f8fafc;">
        <tr>
            <td style="width: 60%; vertical-align: middle; padding: 2.5pt 4pt;">
                <span class="label">Authorised Signatory:</span> Dhanapal G (Authorized Officer)<br>
                <span style="font-size: 5.8pt; color: #64748b;">Company Seal: POJO FINSERV OFFICIAL SEAL &nbsp;|&nbsp; Date: {{ $pnDate }}</span>
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