<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login Page</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            /* background-color: #f4f4f4; */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;

            background: url('{{ asset('images/background-image.png') }}');
            background-size: cover;
            font-family: "Poppins", sans-serif;
        }

        .card-login {
            width: 100%;
            max-width: 450px;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        .form-outline {
            margin-bottom: 1.5rem;
        }

        .btn-primary {
            background-color: #d32f2f;
            border: none;
            width: 100%;
            font-size: 1.1rem;
        }

        .btn-primary:hover {
            background-color: #b71c1c;
        }

        .text-center {
            text-align: center;
        }

        .login-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .login-logo img {
            height: 100px;
        }

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 20px 0;
        }

        .divider::before,
        .divider::after {
            content: "";
            flex: 1;
            border-bottom: 1px solid #ddd;
        }

        .divider:not(:empty)::before {
            margin-right: .25em;
        }

        .divider:not(:empty)::after {
            margin-left: .25em;
        }

        .social-login-buttons {
            display: flex;
            justify-content: space-around;
        }

        .social-login-buttons button {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.2rem;
            width: 48%;
            border: 1px solid #ddd;
            background-color: #fff;
            font-size: 1rem;
            cursor: pointer;
        }

        .social-login-buttons img {
            height: 20px;
            margin-right: 8px;
        }
    </style>
</head>

<body>
    <div class="card card-login">
        <div class="login-logo">
            {{-- <img src="{{ asset('images/7015971.jpg') }}" alt="Logo"> --}}
            <img src="{{ asset('images/logo.jpeg') }}" alt="Logo">
            {{-- <h4 class="mb-4">GEN-LEAD CRM</h4> --}}
        </div>
        {{-- <h5 class="text-center mb-4">Get started with your 15-day free trial.</h5> --}}


        <!-- Display Error Message -->
        @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif

        <form action="{{ url('/admin/login') }}" method="POST">
            @csrf
            <div class="form-outline">
                <label class="form-label" for="form3Example97">Work Username</label>
                <input type="text" id="form3Example97" name="emp_username" class="form-control form-control-lg" required />
            </div>

            <div class="form-outline">
                <label class="form-label" for="form3ExamplePassword"> Password</label>
                <input type="password" id="form3ExamplePassword" name="emp_password" class="form-control form-control-lg" required />
            </div>

            <button type="submit" class="btn btn-primary btn-lg">GET STARTED</button>
        </form>

        <div class="text-center mt-3">
            <a href="{{ route('password.request') }}" class="text-primary">Forgot Password?</a>
        </div>

        {{-- <div class="divider">Or, Create a new account now</div>

        <div class="social-login-buttons">
            <button type="button">
                <img src="https://img.icons8.com/color/48/000000/google-logo.png" alt="Google Icon">
                Sign in with Google
            </button>
            <button type="button">
                <img src="https://img.icons8.com/color/48/000000/linkedin.png" alt="LinkedIn Icon">
                Sign in with LinkedIn
            </button>
        </div> --}}
    </div>

    <!-- Bootstrap JS CDN -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>

