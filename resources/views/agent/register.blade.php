<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Genlead - Agent Registration</title>
    <meta name="description" content="Register as an agent to connect with leads and provide admission services.">
    <meta name="keywords" content="Genlead, Agent Registration, Admission Services">
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
                    <div class="input-group">
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" 
                               placeholder="Email" required>
                        <span class="input-group-text">
                            <i class="fas fa-envelope"></i>
                        </span>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <small id="emailHelp" class="form-text"></small>
                </div>

                <div class="form-group">
                    <div class="input-group">
                        <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                               id="phone" name="phone" value="{{ old('phone') }}" 
                               placeholder="Phone" required maxlength="10">
                        <span class="input-group-text">
                            <i class="fas fa-phone"></i>
                        </span>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <small id="phoneHelp" class="form-text"></small>
                </div>

                <div class="form-group">
                    <textarea class="form-control @error('address') is-invalid @enderror" 
                              name="address" rows="1" placeholder="Place" required>{{ old('address') }}</textarea>
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

                <div class="form-group">
                    <div class="input-group">
                        <input type="text" class="form-control @error('referral_code') is-invalid @enderror" 
                               name="referral_code" value="{{ old('referral_code') }}" 
                               placeholder="Referral Code (Optional)">
                        <span class="input-group-text">
                            <i class="fas fa-user-friends"></i>
                        </span>
                        @error('referral_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <small class="form-text text-muted">Enter the referral code if you were referred by an existing agent</small>
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
    <script>
        $(document).ready(function() {
            const csrfToken = $('meta[name="csrf-token"]').attr('content');
            let emailCheckInProgress = false;
            let emailTimeout;

            // Function to check email availability
            function checkEmailAvailability(email) {
                if (!isValidEmail(email)) return;
                
                emailCheckInProgress = true;
                const emailField = $('#email');
                const emailHelp = $('#emailHelp');
                
                emailField.removeClass('is-invalid is-valid');
                emailHelp.removeClass('text-danger text-success')
                    .html('<span class="spinner-border spinner-border-sm" role="status"></span> Checking email...');
                
                $.ajax({
                    url: '{{ route("agent.check-email") }}',
                    type: 'POST',
                    data: {
                        _token: csrfToken,
                        email: email
                    },
                    success: function(response) {
                        if (response.available) {
                            emailField.removeClass('is-invalid');
                            emailField.addClass('is-valid');
                            emailHelp.removeClass('text-danger').addClass('text-success').text('Email is available!');
                        } else {
                            emailField.removeClass('is-valid');
                            emailField.addClass('is-invalid');
                            emailHelp.removeClass('text-success').addClass('text-danger').text('This email is already registered.');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error checking email:', xhr.responseText);
                        emailHelp.removeClass('text-success').addClass('text-danger')
                            .text('Error checking email. Please try again.');
                    },
                    complete: function() {
                        emailCheckInProgress = false;
                    }
                });
            }

            // Simple email validation
            function isValidEmail(email) {
                const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return re.test(email);
            }

            // Check email on input change (with debounce)
            $('#email').on('input', function() {
                const email = $(this).val().trim();
                
                // Clear previous timeout
                clearTimeout(emailTimeout);
                
                // Reset status
                $(this).removeClass('is-valid is-invalid');
                const emailHelp = $('#emailHelp');
                emailHelp.removeClass('text-success text-danger')
                    .text('');
                
                // Only check if email is valid
                if (email.length > 0) {
                    if (!isValidEmail(email)) {
                        $(this).addClass('is-invalid');
                        emailHelp.addClass('text-danger').text('Please enter a valid email address.');
                        return;
                    }
                    
                    emailTimeout = setTimeout(() => {
                        if (!emailCheckInProgress) {
                            checkEmailAvailability(email);
                        }
                    }, 500);
                }
            });

            // Phone number validation
            $('#phone').on('input', function() {
                const phone = $(this).val().trim();
                const phoneHelp = $('#phoneHelp');
                
                // Remove non-numeric characters
                const numericPhone = phone.replace(/\D/g, '');
                $(this).val(numericPhone);
                
                // Reset status
                $(this).removeClass('is-valid is-invalid');
                phoneHelp.removeClass('text-success text-danger');
                
                if (phone.length === 0) {
                    phoneHelp.text('');
                    return;
                }
                
                if (phone.length < 10) {
                    $(this).addClass('is-invalid');
                    phoneHelp.addClass('text-danger').text('Phone number must be 10 digits');
                } else if (phone.length > 10) {
                    $(this).val(phone.substring(0, 10));
                    $(this).addClass('is-valid');
                    phoneHelp.addClass('text-success').text('Valid phone number');
                } else {
                    $(this).addClass('is-valid');
                    phoneHelp.addClass('text-success').text('Valid phone number');
                }
            });
            
            // Prevent non-numeric input
            $('#phone').on('keypress', function(e) {
                const charCode = e.which ? e.which : e.keyCode;
                if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                    return false;
                }
                return true;
            });
        });
    </script>
</body>
</html>
