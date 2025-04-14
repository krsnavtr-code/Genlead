<!DOCTYPE html>
<html>
<head>
    <title>Offer Letter</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1, p {
            text-align: left;
        }
        .signature {
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <h1>Offer Letter</h1>
    <p>Dear {{ $employee->name }},</p>
    <p>We are pleased to offer you the position of {{ $employee->designation }} at {{ config('app.name') }}.</p>
    <p>Your joining date will be {{ $employee->doj }}.</p>
    <p>Your salary will be 240000 per annum.</p>
    
    <p>Other terms and conditions of your employment include:</p>
    <ul>
        <li>Your working hours will be from 9:30 AM to 6:30 PM, Monday to Saturday.</li>
        <li>You will be on probation for 3 months, during which your performance will be reviewed.</li>
        <li>You are required to give a 30-day notice period if you plan to leave the company.</li>
        <li>Your salary is confidential, and you should not disclose it to other employees.</li>
    </ul>

    <p>If you have any questions, feel free to reach out to HR.</p>
    
    <div class="signature">
        <p>Best regards,</p>
        <p>{{ config('app.name') }}<br>HR Department</p>
    </div>
</body>
</html>

