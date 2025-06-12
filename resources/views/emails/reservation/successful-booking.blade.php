<!DOCTYPE html>
<html>

<head>
    <title>Reservation Confirmation</title>
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
            <h1>Reservation Confirmation</h1>
        </div>

        <!-- Content Section -->
        <div class="content">
            <h2>Dear {{ $reservation->guest->name() }},</h2>
            <p>Thank you for choosing <strong>{{ $reservation->hotel->name }}</strong> for your stay. We are excited to host you!</p>

            <!-- Reservation Details -->
            <div class="reservation-details">
                <h3>Your Reservation Details</h3>
                <table class="details-table">
                    <tr>
                        <th>Check-in Date</th>
                        <td>{{ $reservation->checkin_date }}</td>
                    </tr>
                    <tr>
                        <th>Check-out Date</th>
                        <td>{{ $reservation->checkout_date }}</td>
                    </tr>
                    <tr>
                        <th>Room Type</th>
                        <td>{{ $reservation->room->category->name }}</td>
                    </tr>
                </table>
            </div>

            <p>If you have any special requests or questions about your stay, feel free to contact us. We're here to make your experience unforgettable!</p>
        </div>

        <!-- Footer Section -->
        <div class="footer">
            <p>Thank you for booking with us.</p>
            <p><strong>{{ $reservation->hotel->name }}</strong></p>
            <p>
                <a href="{{ $reservation->hotel->website }}">Visit our website</a> |
                <a href="mailto:{{ $reservation->hotel->email }}">Contact us</a>
            </p>
        </div>
    </div>
</body>

</html>