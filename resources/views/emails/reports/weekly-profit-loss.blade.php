<!DOCTYPE html>
<html>

<head>
    <title>Weekly Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border: 1px solid #e7e7e7;
            border-radius: 8px;
            overflow: hidden;
        }

        .header {
            background-color: #007bff;
            color: #ffffff;
            text-align: center;
            padding: 20px 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .content {
            padding: 20px 30px;
            color: #343a40;
        }

        .content h2 {
            margin-top: 0;
            color: #007bff;
        }

        .reservation-details {
            margin: 20px 0;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .details-table th,
        .details-table td {
            border: 1px solid #e7e7e7;
            padding: 8px 12px;
            text-align: left;
        }

        .details-table th {
            background-color: #f1f1f1;
        }

        .footer {
            background-color: #f8f9fa;
            text-align: center;
            padding: 15px 10px;
            font-size: 14px;
            color: #6c757d;
        }

        .footer a {
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <!-- Header Section -->
        <div class="header">
            <h1>Your weekly report is ready</h1>
        </div>

        <!-- Content Section -->
        <div class="content">
            <h2>Dear {{ $hotel->name }} Manager,</h2>
            <p>We are pleased to inform you that your weekly report is ready for review. This report provides a comprehensive overview of your hotel's performance over the past week.</p>
            <p> **{{ $start_date }}** to **{{ $end_date }}** </p>

            <p><a href="{{asset('storage/reports/weekly_profit_loss_' . $hotel->id . '.pdf')}}">Download Report</a></p>

        </div>

        <!-- Footer Section -->
        <div class="footer">
            <p>Thanks,</p>
            <p><strong>{{ $hotel->name }}</strong></p>
        </div>
    </div>
</body>

</html>