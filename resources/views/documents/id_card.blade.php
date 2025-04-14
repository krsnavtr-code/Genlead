<!DOCTYPE html>
<html>
<head>
    <title>ID Card</title>
    <style>
        .id-card {
            border: 1px solid #000;
            padding: 20px;
            width: 300px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="id-card">
        <h2>{{ config('app.name') }}</h2>
        <h3>{{ $employee->name }}</h3>
        <p>Employee ID: {{ $employee->id }}</p>
        <p>Designation: {{ $employee->designation }}</p>
        <p>Date of Joining: {{ $employee->doj }}</p>
    </div>
</body>
</html>
