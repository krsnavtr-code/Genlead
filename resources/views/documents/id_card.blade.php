<!DOCTYPE html>
<html>
<head>
    <title>ID Card</title>
    <style>
        @page { margin: 0; padding: 0; }
        body { 
            margin: 0; 
            padding: 10px; 
            font-family: Arial, sans-serif; 
            background-color: #f5f5f5;
        }
        .id-card {
            width: 85.6mm;
            height: 54mm;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
            position: relative;
            margin: 0 auto;
        }
        .header {
            background: #2c3e50;
            color: white;
            padding: 8px 0;
            text-align: center;
            font-size: 14px;
            font-weight: bold;
        }
        .logo {
            text-align: center;
            padding: 5px 0;
        }
        .logo img {
            max-width: 50px;
            height: auto;
        }
        .photo {
            width: 25mm;
            height: 30mm;
            margin: 5px auto;
            background: #f0f0f0;
            border: 1px solid #ddd;
            text-align: center;
            line-height: 30mm;
            color: #999;
            font-size: 12px;
        }
        .details {
            padding: 5px 10px;
            font-size: 10px;
        }
        .details p {
            margin: 3px 0;
            line-height: 1.2;
        }
        .name {
            font-weight: bold;
            font-size: 12px;
            text-align: center;
            margin: 5px 0;
            text-transform: uppercase;
        }
        .id-number {
            text-align: center;
            font-size: 10px;
            color: #555;
            margin-bottom: 5px;
        }
        .footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            background: #2c3e50;
            color: white;
            text-align: center;
            font-size: 8px;
            padding: 3px 0;
        }
    </style>
</head>
<body>
    <div class="id-card">
        <div class="header">{{ config('app.name', 'Genlead') }}</div>
        <div class="logo">
            <!-- Add your logo here -->
            <div style="font-size: 24px; font-weight: bold; color: #2c3e50;">GL</div>
        </div>
        <div class="photo">PHOTO</div>
        <div class="name">{{ $employee->name }}</div>
        <div class="id-number">ID: {{ $employee->id }}</div>
        <div class="details">
            <p><strong>Designation:</strong> {{ $employee->designation }}</p>
            <p><strong>DOJ:</strong> {{ $employee->doj }}</p>
            <p><strong>Valid Until:</strong> {{ date('Y-m-d', strtotime('+2 years')) }}</p>
        </div>
        <div class="footer">If found, please return to {{ config('app.name', 'Genlead') }}</div>
    </div>
</body>
</html>
