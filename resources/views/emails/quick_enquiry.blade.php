<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Quick Enquiry - POJO FINSERV</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        .header {
            background: linear-gradient(135deg, #0052cc, #003b95);
            color: #ffffff;
            padding: 25px 30px;
            text-align: center;
            border-bottom: 3px solid #d4af37;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 1px;
        }
        .header p {
            margin: 6px 0 0 0;
            font-size: 13px;
            color: #fde047;
        }
        .body-content {
            padding: 30px;
        }
        .badge-notice {
            background-color: #fef2f2;
            border-left: 4px solid #0052cc;
            padding: 10px 14px;
            font-size: 14px;
            color: #0052cc;
            font-weight: bold;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 12px 14px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
            font-size: 14px;
        }
        th {
            background-color: #f8fafc;
            color: #64748b;
            width: 40%;
            font-weight: 600;
        }
        td {
            color: #0f172a;
            font-weight: 500;
        }
        .highlight-amount {
            color: #0052cc;
            font-weight: 700;
            font-size: 16px;
        }
        .footer {
            background-color: #f8fafc;
            padding: 15px 30px;
            text-align: center;
            font-size: 12px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>POJO FINSERV</h1>
            <p>New Quick Enquiry Received from Website</p>
        </div>
        <div class="body-content">
            <div class="badge-notice">
                🔔 A new quick loan/service requirement has been submitted.
            </div>

            <table>
                <tr>
                    <th>Customer Name</th>
                    <td><strong>{{ $name ?? ($enquiry->name ?? 'N/A') }}</strong></td>
                </tr>
                <tr>
                    <th>Service Required</th>
                    <td><strong>{{ $service ?? 'N/A' }}</strong></td>
                </tr>
                <tr>
                    <th>Requirement Amount</th>
                    <td class="highlight-amount">₹{{ number_format((float)($amount ?? 0), 2) }}</td>
                </tr>
                <tr>
                    <th>Contact Phone Number</th>
                    <td><a href="tel:{{ $phone ?? '' }}" style="color: #0052cc; text-decoration: none; font-weight: bold;">{{ $phone ?? 'N/A' }}</a></td>
                </tr>
                <tr>
                    <th>Received At</th>
                    <td>{{ now()->timezone('Asia/Kolkata')->format('d M Y, h:i A') }} (IST)</td>
                </tr>
            </table>
        </div>
        <div class="footer">
            POJO FINSERV Notification System &bull; D.No- 02/628, Mahil Nagar, Athappampalayam, Melthindal, Erode - 638012
        </div>
    </div>
</body>
</html>
