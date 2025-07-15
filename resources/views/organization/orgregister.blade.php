<!DOCTYPE html>
<html>
<head>
    <title>Register Organization</title>
</head>
<body>
    <h2>Organization Registration</h2>

    @if(session('success'))
        <p style="color: green">{{ session('success') }}</p>
    @endif

    @if($errors->any())
        <ul style="color: red">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('organization.register') }}">
        @csrf
        <label>Organization Name:</label><br>
        <input type="text" name="name" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Contact Number:</label><br>
        <input type="text" name="contact_number" required><br><br>

        <button type="submit">Register</button>
    </form>
</body>
</html>
