<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="GenLead | Admin Login">
    <meta name="keywords" content="GenLead, Admin Login">
    <meta name="author" content="GenLead">
    <link rel="icon" href="{{ asset('images/gen-logo.jpeg') }}">
    <title>GenLead | Admin Login</title>

    <!-- Bootstrap CSS CDN -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: url('{{ asset('images/background-image.png') }}') no-repeat center center;
            background-size: cover;
            height: 100vh;
            font-family: 'Poppins', sans-serif;
        }
        .login-card form button {
            max-width: 70%;
            margin: 0 auto;
            transition: all 0.3s ease-in-out;
        }
        .login-card form button:hover{
            max-width: 80%;
            transform: translateX(-5%);
            transform: translateY(-5%);
            transition: all 0.3s ease-in-out;
        }
    </style>
</head>

<body class="d-flex justify-content-center align-items-center">

    <div class="login-card card shadow-lg p-4" style="width: 100%; max-width: 420px; border-radius: 1rem; background-color: #fff;">
        <div class="text-center mb-4">
            <img src="{{ asset('images/gen-logo.jpeg') }}" alt="GenLead Logo" style="height: 80px;">
        </div>

        <!-- Display Error Message -->
        @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif

        <form action="{{ url('/admin/login') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="form3Example97">Username</label>
                <input type="text" id="form3Example97" name="emp_username" class="form-control" placeholder="Enter username" required />
            </div>

            <div class="form-group">
                <label for="form3ExamplePassword">Password</label>
                <input type="password" id="form3ExamplePassword" name="emp_password" class="form-control" placeholder="Enter password" required />
            </div>

            <button type="submit" class="btn text-white btn-block" style="background-color: #FB6005;">Get Started</button>
        </form>

        <!-- <div class="text-center mt-3">
            <a href="{{ route('password.request') }}" class="text-primary">Forgot Password?</a>
        </div> -->
    </div>

    <!-- Bootstrap JS CDN -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
</body>

</html>
