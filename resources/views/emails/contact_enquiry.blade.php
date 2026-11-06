<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Contact Enquiry - POJO FINSERV</title>
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
            width: 35%;
            font-weight: 600;
        }
        td {
            color: #0f172a;
            font-weight: 500;
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
            <p>New Contact Us Message Received</p>
        </div>
        <div class="body-content">
            <div class="badge-notice">
                ✉️ A new message has been submitted via Contact Us page.
            </div>

            <table>
                <tr>
                    <th>Customer Name</th>
                    <td><strong>{{ $enquiry->name ?? 'N/A' }}</strong></td>
                </tr>
                <tr>
                    <th>Mobile Number</th>
                    <td><a href="tel:{{ $enquiry->phone ?? '' }}" style="color: #0052cc; text-decoration: none; font-weight: bold;">{{ $enquiry->phone ?? 'N/A' }}</a></td>
                </tr>
                <tr>
                    <th>Email Address</th>
                    <td>{{ $enquiry->email ?? 'Not provided' }}</td>
                </tr>
                <tr>
                    <th>Service Required</th>
                    <td><strong>{{ $enquiry->service_required ?? 'General Enquiry' }}</strong></td>
                </tr>
                <tr>
                    <th>Message</th>
                    <td style="white-space: pre-line;">{{ $enquiry->message ?? 'No message provided' }}</td>
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
