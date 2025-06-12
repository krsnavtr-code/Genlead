<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Genlead - Login</title>
    <meta name="description" content="Login to your Genlead account to manage leads and track your performance.">
    <meta name="keywords" content="Genlead, Login, Agent Portal">
    <meta name="author" content="Genlead">
    <link rel="icon" type="image/png" href="https://genlead.in/images/gen-logo.jpeg">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4cc9f0;
            --light-color: #f8f9fa;
            --dark-color: #212529;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-x: hidden;
        }

        .login-container {
            width: 100%;
            max-width: 1000px;
            margin: 2rem;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 10px 50px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            display: flex;
            min-height: 650px;
        }

        .form-side {
            flex: 1;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .form-header h1 {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .form-header p {
            color: #6c757d;
            margin-bottom: 0;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-control {
            padding: 0.75rem 1rem;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.15);
        }

        .btn-login {
            background: var(--primary-color);
            border: none;
            padding: 0.8rem;
            font-weight: 600;
            font-size: 1rem;
            border-radius: 8px;
            width: 100%;
            margin-top: 1rem;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
        }

        .graphic-side {
            flex: 1;
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            color: white;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .graphic-side::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
            transform: rotate(30deg);
        }

        .graphic-content h2 {
            font-weight: 700;
            margin-bottom: 1rem;
            font-size: 2rem;
        }

        .graphic-content p {
            opacity: 0.9;
            margin-bottom: 2rem;
        }

        .graphic-icon {
            font-size: 5rem;
            margin-bottom: 1.5rem;
            opacity: 0.9;
        }
        .display-sm-none {
            display: none;
        }

        @media (max-width: 992px) {
            .login-container {
                flex-direction: column;
                max-width: 600px;
            }
            
            .graphic-side {
                display: none;
            }
            .display-sm-none {
                display: block;
            }
        }

        .forgot-password {
            text-align: center;
            margin-top: 1.5rem;
        }

        .forgot-password a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .forgot-password a:hover {
            color: var(--secondary-color);
            text-decoration: underline;
        }

        @media (max-width: 425px) {
            .form-side {
                padding: 1rem;
            }
            .login-container {
                margin: 1rem;
            }
        }

    </style>
</head>
<body>
    <div class="login-container">
        <div class="form-side">
            <div class="form-header">
                <img src="{{ asset('images/gen-logo.jpeg') }}" alt="GenLead Logo" style="height: 80px; border-radius: 10px;">
                <h1 style="margin-bottom: 0px;">Welcome Back</h1>
                <p>Sign in to access your account</p>
                <p class="text-bold display-sm-none" style="color: #FA5508;">New Candidate? Check your email for login credentials</p>
            </div>

            <!-- Display Error Message -->
            @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <form action="{{ url('/admin/login') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="form3Example97" class="form-label">Username</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" id="form3Example97" name="emp_username" class="form-control" placeholder="Enter your username" required />
                    </div>
                </div>

                <div class="form-group">
                    <label for="form3ExamplePassword" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" id="form3ExamplePassword" name="emp_password" class="form-control" placeholder="Enter your password" required />
                        <button class="btn btn-outline-secondary toggle-password" type="button" style="border-color: #dee2e6; border-left: 0;">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-login">
                        <i class="fas fa-sign-in-alt me-2"></i> Sign In
                    </button>
                </div>

                <div class="forgot-password">
                    <a href="{{ route('password.request') }}">Forgot Password?</a>
                </div>
            </form>
        </div>

        <div class="graphic-side">
            <div class="graphic-content">
                <div class="graphic-icon">
                    <i class="fas fa-sign-in-alt"></i>
                </div>
                <h2>GenLead</h2>
                <p>Empowering your lead management with powerful tools and insights to grow your business.</p>
                <p class="text-bold" style="color: #FA5508;">New Candidate? Check your email for login credentials</p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.querySelector('.toggle-password');
            const password = document.querySelector('#form3ExamplePassword');
            const icon = togglePassword.querySelector('i');
            
            togglePassword.addEventListener('click', function() {
                // Toggle the type attribute
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                
                // Toggle the eye icon
                if (type === 'password') {
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                } else {
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                }
                
                // Focus the password field
                password.focus();
            });
        });
    </script>
</body>
</html>
