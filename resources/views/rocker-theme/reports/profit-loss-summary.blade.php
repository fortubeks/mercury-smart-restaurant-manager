<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Type Summary</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            margin: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin-top: 20px;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 20px;
        }

        .table,
        .table th,
        .table td {
            border: 1px solid black;
        }

        .table th,
        .table td {
            padding: 8px;
            text-align: right;
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
            text-align: center;
            color: gray;
        }
    </style>
</head>

<body>

    <div class="header">
        <h2>Londa</h2>
        <p><strong>Account Type Summary</strong></p>
        <p><strong>Basis:</strong> Accrual</p>
        <p><strong>Period:</strong> From {{ $from_date }} To {{ $to_date }}</p>
    </div>

    <div class="section-title">Account Type Summary</div>
    <table class="table">
        <thead>
            <tr>
                <th>Account Type</th>
                <th>Debit (NGN)</th>
                <th>Credit (NGN)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($account_types as $account)
            <tr>
                <td>{{ $account['name'] }}</td>
                <td>{{ number_format($account['debit'], 2) }}</td>
                <td>{{ number_format($account['credit'], 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Amount is displayed in your base currency NGN</p>
        <p>Generated on {{ now()->format('F d, Y') }} | Venus Hotel Management System</p>
    </div>

</body>

</html>