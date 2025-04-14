<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        .page {
            page-break-after: always;
            text-align: center;
        }
        img {
            width: 100%;
            height: auto;
        }
    </style>
</head>
<body>

    <div class="page">
        <img src="{{ $imagePath1 }}" alt="Offer Letter Page 1">
    </div>
    
    <div class="page">
        <img src="{{ $imagePath2 }}" alt="Offer Letter Page 2">
    </div>

</body>
</html>
