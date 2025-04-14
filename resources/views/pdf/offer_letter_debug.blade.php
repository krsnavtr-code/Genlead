<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offer Letter</title>
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
        .page-1 {
            background-image: url('{{ asset('templates/offer_letter_template1.jpg') }}'); /* Page 1 template */
        }
        .page-2 {
            background-image: url('{{ asset('templates/offer_letter_template2.jpg') }}'); /* Page 2 template */
        }
        .content {
            position: absolute;
        }
        .name {
            position: absolute;
            top: 75mm;
            left: 100mm;
        }
        .designation {
            position: absolute;
            top: 85mm;
            left: 100mm;
        }
        .doj {
            position: absolute;
            top: 95mm;
            left: 100mm;
        }
        .salary {
            position: absolute;
            top: 105mm;
            left: 100mm;
        }
        /* Additional styling for second and third mentions of the name */
        .name-mention-2 {
            position: absolute;
            top: 120mm; /* Adjust as per layout */
            left: 100mm;
        }
        .name-mention-3 {
            position: absolute;
            top: 135mm; /* Adjust as per layout */
            left: 100mm;
        }
        .address {
            position: absolute;
            top: 150mm; /* Adjust as per layout */
            left: 100mm;
        }
    </style>
</head>
<body>

    <!-- First Page of the Offer Letter with Background Image -->
    <div class="page page-1">
        <!-- Dynamic Data -->
        <div class="content">
            <span class="name"> {{ $employee->name }}</span>
            <span class="designation"> {{ $employee->designation }}</span>
            <span class="doj"> {{ $employee->doj }}</span>
            <span class="salary"> 240000 </span>

            <!-- Second occurrence of the employee's name -->
            <span class="name-mention-2"> {{ $employee->name }}</span>

            <!-- Static address section -->
            <span class="address"> 123, Main Street, Kota, India</span>
        </div>
    </div>

    <!-- Second Page of the Offer Letter with Background Image -->
    <div class="page page-2">
        <!-- You can place content here for the second page as needed -->
        <span class="name-mention-3"> {{ $employee->name }}</span>
    </div>

</body>
</html>
