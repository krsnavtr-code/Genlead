<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Joinee Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        .content-wrapper {
            padding: 20px;
        }

        .content-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .content-header h1 {
            font-size: 28px;
            font-weight: bold;
            color: #2c3e50;
        }

        .card {
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 39px;
            max-width: 1000px;
            margin: 0 auto;
        }

        .card h4 {
            font-size: 22px;
            color: #34495e;
            border-bottom: 2px solid #3498db;
            padding-bottom: 8px;
            margin-bottom: 20px;
        }

        .card p {
            font-size: 16px;
            color: #7f8c8d;
            line-height: 1.5;
            margin-bottom: 10px;
        }

        .card p strong {
            color: #2c3e50;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .btn-success {
            background-color: #28a745;
            border: none;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .btn-success:focus {
            outline: none;
        }

        .center-button {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="content-wrapper">
        <div class="content-header sty-one">
            <h1>Welcome, {{ $candidate->name }}</h1>
        </div>

        <div class="content">
            <div class="card">
                <div class="card-body">
                    <h4>Dashboard</h4>
                    <p><strong>Name:</strong> {{ $candidate->name }}</p>
                    <p><strong>Email:</strong> {{ $candidate->email }}</p>
                    <p><strong>Phone:</strong> {{ $candidate->phone }}</p>
                    <p><strong>Location:</strong> {{ $candidate->location }}</p>

                    <div class="center-button">
                        <a href="/admin/add-documents" class="btn btn-success">Upload Documents</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
