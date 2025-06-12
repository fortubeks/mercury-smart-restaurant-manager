<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Night Audit Report</title>
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
        <h2>{{hotel()->name}}</h2>
        <p><strong>Night Audit Report</strong></p>
        <p><strong>Period:</strong> From {{ $from_date }} To {{ $to_date }}</p>
    </div>

    <div class="section-title">Income & Revenue</div>
    <table class="table">
        <tr>
            <th>Sales Point</th>
            <th>Total (NGN)</th>
        </tr>
        <tr>
            <td>Total Operating Income</td>
            <td>{{ number_format($operating_income, 2) }}</td>
        </tr>
    </table>

    <div class="section-title">Cost of Goods Sold</div>
    <table class="table">
        <tr>
            <td>Total Cost of Goods Sold</td>
            <td>{{ number_format($cost_of_goods_sold, 2) }}</td>
        </tr>
    </table>

    <div class="section-title">Gross Profit</div>
    <table class="table">
        <tr>
            <td>Gross Profit</td>
            <td>{{ number_format($gross_profit, 2) }}</td>
        </tr>
    </table>

    <div class="section-title">Operating Expense</div>
    <table class="table">
        <tr>
            <td>Total Operating Expense</td>
            <td>{{ number_format($operating_expense, 2) }}</td>
        </tr>
    </table>

    <div class="section-title">Operating Profit</div>
    <table class="table">
        <tr>
            <td>Operating Profit</td>
            <td>{{ number_format($operating_profit, 2) }}</td>
        </tr>
    </table>

    <div class="section-title">Non-Operating Income</div>
    <table class="table">
        <tr>
            <td>Total Non-Operating Income</td>
            <td>{{ number_format($non_operating_income, 2) }}</td>
        </tr>
    </table>

    <div class="section-title">Non-Operating Expense</div>
    <table class="table">
        <tr>
            <td>Total Non-Operating Expense</td>
            <td>{{ number_format($non_operating_expense, 2) }}</td>
        </tr>
    </table>

    <div class="section-title">Net Profit/Loss</div>
    <table class="table">
        <tr>
            <td><strong>Net Profit/Loss</strong></td>
            <td><strong>{{ number_format($net_profit_loss, 2) }}</strong></td>
        </tr>
    </table>

    <div class="footer">
        <p>Amount is displayed in your base currency NGN</p>
        <p>Generated on {{ now()->format('F d, Y') }} | Venus Hotel Management System</p>
    </div>

</body>

</html>