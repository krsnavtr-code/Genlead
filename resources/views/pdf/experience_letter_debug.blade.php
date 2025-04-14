<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Experience Letter</title>
    <style>
        body {
            font-family: Helvetica, Arial, sans-serif;
        }
        .page {
            width: 210mm; /* A4 width */
            height: 297mm; /* A4 height */
            position: relative;
            background-size: cover;
            background-repeat: no-repeat;
            margin-bottom: 20px;
        }
        .experience-page {
            background-image: url('{{ asset('templates/experience_letter.jpg') }}'); /* Background for experience letter */
        }
        .content {
            position: absolute;
        }
        .date {
            position: absolute;
            top: 70mm;
            left: 100mm;
        }
        .name {
            position: absolute;
            top: 80mm;
            left: 100mm;
        }
        .emp-code {
            position: absolute;
            top: 90mm;
            left: 100mm;
        }
        .designation {
            position: absolute;
            top: 110mm;
            left: 100mm;
        }
        .doj {
            position: absolute;
            top: 120mm;
            left: 100mm;
        }
        .dol {
            position: absolute;
            top: 130mm;
            left: 130mm;
        }
        .salary {
            position: absolute;
            top: 140mm;
            left: 100mm;
        }
    </style>
</head>
<body>

    <!-- Page of the Experience Letter with Background Image -->
    <div class="page experience-page">
        <div class="content">
            <span class="date">{{ date('d-m-Y') }}</span>
            <span class="name">{{ $employee->name }}</span>
            <span class="emp-code">{{ $employee->employee_id }}</span>
            <span class="designation">{{ $employee->designation }}</span>
            <span class="doj">{{ $employee->doj }}</span>
            
            <!-- Static Example for Date of Leaving -->
            <span class="dol">31 Dec 2023</span>

            <span class="salary">240000 per annum</span>
        </div>
    </div>

</body>
</html>
