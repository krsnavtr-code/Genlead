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
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
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
            height: 40px;
            margin-right: 10px;
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
            <img src="{{ asset('images/7015971.jpg') }}" alt="Logo">
            <h4class="mb-4">GEN-LEAD CRM</h4>
        </div>
        <h5 class="text-center mb-4">Get started with your 15-day free trial.</h5>

<div class="card card-login">

    @if (session('success'))
    <div class="alert alert-success">
          {{ session('success') }}
   </div>
    @endif

    @if (session('errors'))
    <div class="alert alert-danger">
        <ul>
            @foreach (session('errors')->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
   @endif
   
    <form action="{{ url('admin/password/reset-submit')}}" method="POST">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div class="form-outline mb-4">
            <label class="form-label">New Password</label>
            <input type="text" name="password" class="form-control" required>
        </div>
        <div class="form-outline mb-4">
            <label class="form-label">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
    </form>
      <!-- Back Button -->
      <a href="{{ url('/') }}" class="btn btn-back btn-block">Back to Login</a>
</div>
  <!-- Bootstrap JS CDN -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>

