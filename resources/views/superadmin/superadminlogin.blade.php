<!DOCTYPE html>
<html>
<head>
    <title>Superadmin Login</title>
</head>
<body>
    <h2>Superadmin Login</h2>

    <form method="POST" action="{{ route('superadmin.login') }}">
        @csrf
        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Login</button>

    </form>
</body>
</html>
