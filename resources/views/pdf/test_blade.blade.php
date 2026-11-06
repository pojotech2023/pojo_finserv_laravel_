<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>UNSECURED LOAN PROMISSORY NOTE & SAME-DAY FUNDING AGREEMENT</title>
    <style>
        @page {
            margin: 10pt 18pt 20pt 18pt;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 6.8pt;
            line-height: 1.18;
            color: #1e293b;
        }
        footer {
            position: fixed;
            bottom: -15pt;
            left: 0pt;
            right: 0pt;
            height: 20pt;
            font-size: 6.5pt;
            color: #475569;
            border-top: 0.8pt solid #cbd5e1;
            padding-top: 1.5pt;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1pt;
        }
        .header-table h2 {
            margin: 0;
            color: #0052cc;
            font-size: 14pt;
            line-height: 1.05;
            letter-spacing: 0.5pt;
        }
        .header-subtext {
            font-size: 6.5pt;
            line-height: 1.15;
            color: #334155;
        }
        .doc-title {
            text-align: center;
            font-size: 9pt;
            font-weight: bold;
            color: #0052cc;
            text-transform: uppercase;
            margin: 1pt 0;
            letter-spacing: 0.4pt;
        }
        .doc-subtitle {
            text-align: center;
            font-size: 7.2pt;
            font-style: italic;
            color: #475569;
            margin-bottom: 2pt;
        }
        .section-header {
            font-size: 7.2pt;
            font-weight: bold;
            color: #0052cc;
            text-transform: uppercase;
            background-color: #fff1f2;
            border-left: 2.5pt solid #0052cc;
            padding: 1pt 3pt;
            margin-top: 2pt;
            margin-bottom: 1pt;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1pt;
        }
        .info-table td {
            padding: 1.2pt 3pt;
            vertical-align: middle;
            font-size: 6.8pt;
            line-height: 1.18;
        }
        .label {
            font-weight: bold;
            color: #334155;
        }
        .page-break {
            page-break-after: always;
        }
        .rupee {
            font-family: DejaVu Sans, sans-serif;
        }
        .checkbox {
            font-family: DejaVu Sans, sans-serif;
            font-size: 7.5pt;
        }
        .verify-badge {
            background-color: #dcfce7;
            color: #15803d;
            border: 0.8pt solid #86efac;
            padding: 1pt 3pt;
            border-radius: 2pt;
            font-weight: bold;
            font-size: 6pt;
            display: inline-block;
        }
    </style>
</head>
<body>
    <!-- Running Page Footer on ALL Pages -->
    <footer>
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 58%; text-align: left; vertical-align: middle;">
                    <strong style="color: #0052cc;">POJO FINSERV</strong> &bull; Digitally Signed via Aadhaar OTP &bull;
                    IP: {{ $kyc->ip_address ?? '223.185.27.16' }} &bull; Date: {{ $pnDateTime }}
                </td>
                <td style="width: 42%; text-align: right; vertical-align: middle;">
                    <span style="font-weight: bold; text-transform: uppercase;">{{ strtoupper($kyc->full_name ?? 'BORROWER') }}</span> &bull;
                    <span style="color: #15803d; font-family: DejaVu Sans, sans-serif; font-weight: bold;">&#10004; Aadhaar Sign</span>
                </td>
            </tr>
        </table>
    </footer>

    <!-- PAGE 1 -->
    <table class="header-table">
        <tr>
            <td style="width: 80%; vertical-align: middle;">
                <h2>POJO FINSERV</h2>
                <div style="font-size: 6.5pt; color: #64748b;">(A Licensed Private Financier)</div>
                <div class="header-subtext">Address: D.No- 02/628, Mahil Nagar, Athappampalayam, Mel thindal, Erode - 638012.</div>
                <div class="header-subtext">Mobile: 9994777004 | Email: Pojofinserv@outlook.com</div>
            </td>
            <td style="width: 20%; text-align: right; vertical-align: middle;">
                @if($profileImageSrc)
                    <img src="{{ $profileImageSrc }}" style="height: 38pt; width: 38pt; border-radius: 3pt; border: 1pt solid #0052cc; display: inline-block;">
                    <div style="font-size: 5.5pt; color: #475569; line-height: 1.1;">
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
            <td style="width: 32%; padding: 1.5pt 3pt;"><span class="label">Agreement / PN No.:</span> PN-{{ $kyc->id }}-{{ $pnDateYmd }}</td>
            <td style="width: 24%; padding: 1.5pt 3pt;"><span class="label">Date:</span> {{ $pnDate }}</td>
            <td style="width: 24%; padding: 1.5pt 3pt;"><span class="label">Place:</span> COIMBATORE</td>
            <td style="width: 20%; padding: 1.5pt 3pt; text-align: right;"><span class="label">Branch:</span> ERODE</td>
        </tr>
    </table>

    <!-- 1. PARTIES -->
    <div class="section-header">1. PARTIES</div>
    <p style="margin: 1pt 0; font-size: 6.6pt; line-height: 1.2;">
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
    <p style="margin: 0.8pt 0; font-size: 6.6pt; line-height: 1.18;">
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
                <span style="font-size: 6.2pt; color: #475569;">Zero charges for loan process at DSA Code and fund to be returned same day. (If Loan Amount is >= Disbursement Amount, no additional charges; otherwise charges applicable).</span>
            </td>
        </tr>
        <tr>
            <td colspan="2"><span class="label">Total Amount Payable:</span> <span class="rupee">&#8377;</span> {{ number_format($totalPayable, 2) }}</td>
        </tr>
    </table>

    <!-- 6. USE OF FUNDS (ONE BY ONE) -->
    <div class="section-header">6. USE OF FUNDS</div>
    <p style="margin: 0.8pt 0; font-size: 6.6pt; line-height: 1.18;">
        The Borrower shall use the funds only for the stated existing gold-loan transfer. The Borrower shall not divert the funds for any other purpose and shall provide transfer proof when requested by the Lender.
    </p>

    <!-- 7. INTEREST / PRICING (ONE BY ONE) -->
    <div class="section-header">7. INTEREST / PRICING</div>
    <p style="margin: 0.8pt 0; font-size: 6.6pt; line-height: 1.18;">
        The applicable interest/finance charge, effective rate and all other charges shall be disclosed to the Borrower before disbursement and shall be calculated and collected only to the extent permitted by applicable law and the agreed loan terms.
    </p>

    <!-- 8. BORROWER'S UNDERTAKINGS (ONE BY ONE: 1, 2, 3, 4, 5, 6) -->
    <div class="section-header">8. BORROWER'S UNDERTAKINGS</div>
    <ol style="margin: 0.8pt 0; padding-left: 12pt; font-size: 6.5pt; line-height: 1.16;">
        <li>The Borrower confirms that all information and KYC documents provided are true and correct.</li>
        <li>The Borrower shall complete the existing gold-loan closure/transfer promptly after receipt of funds.</li>
        <li>The Borrower shall repay POJO FINSERV the total amount lawfully due on the agreed repayment date.</li>
        <li>The Borrower shall immediately inform POJO FINSERV of any event that may prevent same-day completion or repayment.</li>
        <li>The Borrower shall keep POJO FINSERV informed of changes to address, mobile number or other material KYC information.</li>
        <li>The Borrower shall retain and provide transaction/closure proof as required.</li>
    </ol>

    <!-- 9. BORROWER DIGITAL VERIFICATION (ONE BY ONE) -->
    <div class="section-header">9. BORROWER DIGITAL VERIFICATION</div>
    <table class="info-table" style="border: 0.6pt solid #cbd5e1; background-color: #f8fafc; margin-bottom: 2pt;">
        <tr>
            <td style="width: 55%; vertical-align: middle;">
                <span class="label">Borrower Name:</span> {{ strtoupper($kyc->full_name ?? '') }}<br>
                <span class="label">Date:</span> {{ $pnDate }} &nbsp;|&nbsp; <span class="label">Place:</span> COIMBATORE<br>
                <span style="font-size: 5.8pt; color: #64748b;">IP: {{ $kyc->ip_address ?? '223.185.27.16' }} | Timestamp: {{ $pnDateTime }}</span>
            </td>
            <td style="width: 45%; text-align: right; vertical-align: middle;">
                <div style="font-family: 'Courier New', Courier, monospace; font-size: 6.2pt; border: 0.8pt solid #000; padding: 2pt 4pt; text-align: left; display: inline-block; line-height: 1.15; background-color: #ffffff;">
                    <strong style="text-transform: uppercase;">{{ strtoupper($kyc->full_name ?? '') }}</strong><br>
                    <span style="font-size: 5.5pt; color: #334155;">Date: {{ \Carbon\Carbon::parse($kyc->signed_at ?? $kyc->created_at ?? now())->format('Y.m.d H:i:s') }} (UTC+05:30)</span><br>
                    <span style="color: #15803d; font-family: DejaVu Sans, sans-serif; font-weight: bold;">&#10004; Aadhaar Sign</span><br>
                    <span style="color: #64748b; font-size: 4.8pt; border-top: 0.5pt dashed #94a3b8; display: block; margin-top: 1pt; padding-top: 1pt;">eSign by QuickeKyc / POJO FINSERV</span>
                </div>
            </td>
        </tr>
    </table>

    <!-- 10. WITNESS & 11. FOR POJO FINSERV -->
    <table style="width: 100%; border-collapse: collapse; margin-top: 1pt;">
        <tr>
            <td style="width: 50%; vertical-align: top; padding-right: 3pt;">
                <div class="section-header" style="margin-top: 0;">10. WITNESS (REFERRER PERSON)</div>
                <table class="info-table" style="border: 0.6pt solid #cbd5e1; background-color: #f8fafc;">
                    <tr>
                        <td style="width: 60%; vertical-align: middle;">
                            <span class="label">Witness Name:</span><br>
                            <strong>{{ strtoupper($kyc->referel_name ?? 'MERLIN') }}</strong><br>
                            <span style="font-size: 5.8pt; color: #64748b;">IP: {{ $kyc->ip_address ?? '223.185.27.16' }}<br>Timestamp: {{ $pnDateTime }}</span>
                        </td>
                        <td style="width: 40%; text-align: center; vertical-align: middle;">
                            @if($witnessSigBase64)
                                <img src="data:image/png;base64,{{ $witnessSigBase64 }}" style="height: 22pt;">
                            @else
                                <div class="verify-badge" style="background-color: #f1f5f9; color: #475569; border: 0.8pt solid #cbd5e1;">
                                    Witness Signature:<br><strong>Optional</strong>
                                </div>
                            @endif
                        </td>
                    </tr>
                </table>
            </td>
            <td style="width: 50%; vertical-align: top; padding-left: 3pt;">
                <div class="section-header" style="margin-top: 0;">11. FOR POJO FINSERV</div>
                <table class="info-table" style="border: 0.6pt solid #cbd5e1; background-color: #f8fafc;">
                    <tr>
                        <td style="width: 65%; vertical-align: middle;">
                            <span class="label">Authorised:</span> Dhanapal G (Officer)<br>
                            <span style="font-size: 5.8pt; color: #64748b;">Seal: POJO FINSERV OFFICIAL SEAL<br>Date: {{ $pnDate }}</span>
                        </td>
                        <td style="width: 35%; text-align: center; vertical-align: middle;">
                            @if(file_exists(public_path('images/sign.png')))
                                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/sign.png'))) }}" style="height: 22pt;">
                            @else
                                <div style="height: 16pt; border-bottom: 0.8pt dashed #94a3b8; width: 70%; margin: 0 auto;"></div>
                                <span style="font-size: 5.5pt; color: #64748b;">Signature</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</body>
</html>