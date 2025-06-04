<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Genlead - Registration</title>
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

        .register-container {
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
            position: relative;
            z-index: 2;
        }

        .form-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .form-header h1 {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .form-header p {
            color: #6c757d;
            margin-bottom: 0;
        }

        .toggle-buttons {
            display: flex;
            background: #e9ecef;
            border-radius: 50px;
            padding: 5px;
            margin-bottom: 2rem;
        }

        .toggle-btn {
            flex: 1;
            padding: 0.75rem;
            border: none;
            background: transparent;
            font-weight: 600;
            color: #6c757d;
            cursor: pointer;
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .toggle-btn.active {
            background: var(--primary-color);
            color: white;
            box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
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
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
        }

        .btn-submit {
            background: var(--primary-color);
            border: none;
            padding: 0.8rem;
            font-weight: 600;
            font-size: 1rem;
            border-radius: 8px;
            width: 100%;
            margin-top: 1rem;
            transition: all 0.3s ease;
        }

        .btn-submit:hover {
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

        @media (max-width: 992px) {
            .register-container {
                flex-direction: column;
                max-width: 600px;
            }
            
            .graphic-side {
                display: none;
            }
        }

        
        .form-divider {
            text-align: center;
            margin: 1.5rem 0;
            position: relative;
        }
        
        .form-divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #dee2e6;
            z-index: 1;
        }
        
        .form-divider span {
            background: white;
            padding: 0 1rem;
            position: relative;
            z-index: 2;
            color: #6c757d;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="form-side">
            <div class="form-header">
                <h1>Create Account</h1>
                <p>Create your agent account today.</p>
            </div>
            
            <!-- Agent Registration Form -->
            <form method="POST" action="{{ route('agent.register') }}" class="registration-form">
                @csrf
                <div class="form-group">
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           name="name" value="{{ old('name') }}" placeholder="Full Name" required autofocus>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                           name="email" value="{{ old('email') }}" placeholder="Email Address" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                           name="phone" value="{{ old('phone') }}" placeholder="Phone Number" required>
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <textarea class="form-control @error('address') is-invalid @enderror" 
                              name="address" rows="2" placeholder="Your Address" required>{{ old('address') }}</textarea>
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <div class="form-group">
                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                           name="password" placeholder="Temporary Password" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group w-50 mx-auto">
                    <button type="submit" class="btn btn-primary btn-submit w-100">
                        Register as Agent
                    </button>
                </div>
            </form>

            <div class="form-footer text-center mt-4">
                <p>Already have an account? <a href="{{ route('login') }}" style="color: var(--primary-color); text-decoration: none; font-weight: 500;">Sign In</a></p>
            </div>
        </div>
        
        <div class="graphic-side">
            <div class="graphic-content">
                <div class="graphic-icon">
                    <i class="fas fa-user-tie"></i>
                </div>
                <h2>Join Our Agent Network</h2>
                <p>By registering as an agent, you can connect with leads and provide admission services.</p>
                <div style="width: 100%; max-width: 300px; margin: 0 auto; text-align: left;">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-check-circle me-2"></i>
                        <span>Access to quality leads</span>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-check-circle me-2"></i>
                        <span>24/7 Availability</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>
