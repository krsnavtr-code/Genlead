<!DOCTYPE html>
<html>
<head>
    <title>Experience Letter</title>
</head>
<body>
    <h1>Experience Letter</h1>
    <p>Dear {{ $employee->name }},</p>
    <p>This is to certify that you were employed as {{ $employee->designation }} at {{ config('app.name') }} from {{ $employee->doj }} to {{ $employee->end_date }}.</p>
    <p>...Other Experience Letter Details...</p>
    <p>Best Regards,</p>
    <p>{{ config('app.name') }}</p>
</body>
</html>
