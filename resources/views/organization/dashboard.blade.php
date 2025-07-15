<!DOCTYPE html>
<html>
<head>
    <title>{{ $organization->name }}'s Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        h1 { color: #2c3e50; }
        .info { margin-top: 20px; }
        .info p {
            margin: 6px 0;
            font-size: 16px;
        }
        .btn {
            display: inline-block;
            padding: 10px 18px;
            margin-top: 10px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .btn:hover { background-color: #1e7e34; }
    </style>
</head>
<body>
    <h1>Welcome to {{ $organization->name }}'s Dashboard</h1>

    <div class="info">
        <p><strong>Email:</strong> {{ $organization->email }}</p>
        <p><strong>Contact Number:</strong> {{ $organization->contact_number }}</p>
        <p><strong>Slug:</strong> {{ $organization->slug }}</p>
        <p><strong>Created At:</strong> {{ $organization->created_at->format('d M Y') }}</p>
    </div>

    <a href="{{ route('logout') }}" class="btn">Logout</a>
</body>
</html>
