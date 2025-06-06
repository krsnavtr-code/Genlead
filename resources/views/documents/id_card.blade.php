<!DOCTYPE html>
<html>
<head>
    <title>ID Card</title>
    <style>
        @page { margin: 0; padding: 0; }
        body { 
            margin: 0; 
            padding: 10px; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #eaeaea;
        }
        .id-card {
            width: 85.6mm;
            height: 54mm;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.15);
            overflow: hidden;
            margin: 0 auto;
            position: relative;
            display: flex;
            flex-direction: row;
        }
        .left-section {
            width: 30%;
            background-color: #2c3e50;
            color: white;
            padding: 10px 5px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
        }
        .left-section .logo {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .left-section .logo img {
            width: 100%;
            height: auto;
        }
        .left-section .photo {
            width: 70px;
            height: 85px;
            background: #fff;
            border: 2px solid #ccc;
            border-radius: 4px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            color: #888;
        }
        .left-section .footer {
            font-size: 7px;
            text-align: center;
            line-height: 1.2;
        }
        .right-section {
            width: 70%;
            padding: 10px;
            font-size: 9.5px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .right-section .name {
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 2px;
            color: #2c3e50;
            text-transform: uppercase;
        }
        .right-section .id-number {
            font-size: 9px;
            color: #555;
            margin-bottom: 6px;
        }
        .right-section .details p {
            margin: 2px 0;
            line-height: 1.3;
        }   
    </style>
</head>
<body>
    <div class="id-card">
        <div class="left-section">
            <!-- <div class="logo">{{ config('app.name', 'Genlead') }}</div> -->
            <!-- logo image -->
            <div class="logo">
                <img src="{{ asset('storage/logomini.png') }}" alt="Logo">
            </div>
            <div class="footer">
                If found,<br>
                return to<br>
                {{ config('app.name', 'Genlead') }}
            </div>
        </div>
        <div class="right-section">
            <div class="name">{{ $employee->name }}</div>
            <div class="id-number">ID: {{ $employee->id }}</div>
            <div class="details">
                <p><strong>Designation:</strong> {{ $employee->designation }}</p>
                <p><strong>Date of Joining:</strong> {{ \Carbon\Carbon::parse($employee->doj)->format('d M Y') }}</p>
                <p><strong>Valid Until:</strong> {{ \Carbon\Carbon::parse($employee->doj)->addYears(2)->format('d M Y') }}</p>
            </div>
        </div>
    </div>
</body>
</html>
