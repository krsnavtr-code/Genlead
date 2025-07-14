<!DOCTYPE html>
<html>
<head>
    <title>ID Card</title>
    <style>
        @page {
            margin: 0;
            size: 86mm 54mm;
        }
        * {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            width: 86mm;
            height: 54mm;
        }
        .id-card {
            width: 100%;
            height: 100%;
            background: #fff;
            border: 1px solid #ccc;
            overflow: hidden;
            position: relative;
        }
        .top-banner {
            background: linear-gradient(to bottom, #000000 40%, #f57c00 60%);
            text-align: center;
            padding: 8px 0 0 0;
            border-bottom: 3px solid #f57c00;
            position: relative;
        }
        .photo-wrapper {
            width: 60px;
            height: 60px;
            margin: 0 auto;
            border-radius: 50%;
            border: 3px solid #f57c00;
            overflow: hidden;
        }
        .photo-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .name {
            color: white;
            font-weight: bold;
            font-size: 12px;
            margin-top: 5px;
        }
        .designation {
            color: white;
            font-size: 10px;
            margin-bottom: 5px;
        }
        .details {
            padding: 6px 10px;
            font-size: 9px;
            line-height: 1.5;
        }
        .details .row {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px dotted #ccc;
        }
        .details .label {
            font-weight: bold;
            color: #000;
            width: 40%;
        }
        .details .value {
            width: 58%;
            text-align: right;
        }
        .footer {
            background: #000;
            color: #fff;
            font-size: 8px;
            padding: 4px 10px;
            position: absolute;
            bottom: 0;
            width: 100%;
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>
    <div class="id-card">
        <div class="top-banner">
            <div class="photo-wrapper">
                <img src="{{ asset('storage/photos/' . $employee->photo) }}" alt="Employee Photo">
            </div>
            <div class="name">{{ strtoupper($employee->name) }}</div>
            <div class="designation">{{ $employee->designation }}</div>
        </div>
        <div class="details">
            <div class="row"><div class="label">Phone:</div><div class="value">{{ $employee->phone }}</div></div>
            <div class="row"><div class="label">DOB:</div><div class="value">{{ \Carbon\Carbon::parse($employee->dob)->format('d-m-Y') }}</div></div>
            <div class="row"><div class="label">Email:</div><div class="value">{{ $employee->email }}</div></div>
            <div class="row"><div class="label">ID No:</div><div class="value">{{ $employee->id }}</div></div>
        </div>
        <div class="footer">
            <div>Join: {{ \Carbon\Carbon::parse($employee->doj)->format('d-m-Y') }}</div>
            <div>Expire: {{ \Carbon\Carbon::parse($employee->doj)->addYears(25)->format('d-m-Y') }}</div>
        </div>
    </div>
</body>
</html>
