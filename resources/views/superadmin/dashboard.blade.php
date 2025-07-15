<!DOCTYPE html>
<html>
<head>
    <title>Superadmin Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        h1, h2 { color: #333; }
        .btn {
            display: inline-block;
            padding: 10px 18px;
            margin: 10px 5px 20px 0;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .btn:hover { background-color: #0056b3; }
        table { border-collapse: collapse; width: 100%; margin-top: 10px; }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }
        th { background-color: #f5f5f5; }
    </style>
</head>
<body>
    <h1>Welcome, Superadmin!</h1>

    <a href="{{ route('logout') }}" class="btn">Logout</a>
    <a href="{{ route('organization.register') }}" class="btn">Register Organization</a>

    <h2>All Organizations</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Contact Number</th>
                <th>Role No</th>
                <th>Slug</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($organizations as $org)
                <tr>
                    <td>{{ $org->id }}</td>
                    <td>{{ $org->name }}</td>
                    <td>{{ $org->email }}</td>
                    <td>{{ $org->contact_number }}</td>
                    <td>{{ $org->role_no }}</td>
                    <td>{{ $org->slug }}</td>
                    <td>{{ $org->created_at->format('d M Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
