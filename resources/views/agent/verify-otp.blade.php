<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>OTP Verification</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #f0f4ff, #dfe9f3);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .verify-wrapper {
            background-color: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .form-title {
            font-weight: 600;
            font-size: 24px;
            margin-bottom: 1.5rem;
            text-align: center;
            color: #0d6efd;
        }

        .otp-input {
            font-size: 24px;
            text-align: center;
            width: 100%;
            padding: 10px;
            margin: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
    </style>
</head>
<body>

    <div class="verify-wrapper">
        <div class="form-title">OTP Verification</div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('agent.verify-otp') }}">
            @csrf

            <div class="mb-3">
                <label for="otp" class="form-label">Enter OTP</label>
                <div class="d-flex justify-content-center">
                    <input type="text" name="otp" class="otp-input" maxlength="6" required>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const otpInputs = document.querySelectorAll('.otp-input');
                        const fullOtpInput = document.getElementById('full-otp');

                        otpInputs.forEach((input, index) => {
                            input.addEventListener('input', function(e) {
                                // Only allow numbers
                                this.value = this.value.replace(/[^0-9]/g, '');
                                
                                // Move to next input when full
                                if (this.value.length === 1 && index < otpInputs.length - 1) {
                                    otpInputs[index + 1].focus();
                                }
                                
                                // Update full OTP
                                let fullOtp = '';
                                otpInputs.forEach(input => fullOtp += input.value);
                                fullOtpInput.value = fullOtp;
                            });

                            // Handle backspace
                            input.addEventListener('keydown', function(e) {
                                if (e.key === 'Backspace' && this.value === '' && index > 0) {
                                    otpInputs[index - 1].focus();
                                }
                            });
                        });
                    });
                </script>
                <small class="text-muted">Check your email for the OTP</small>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary">
                    Verify OTP
                </button>
            </div>

            <div class="text-center mt-3">
                <a href="{{ route('agent.register.form') }}" class="text-decoration-none">
                    Resend OTP
                </a>
            </div>
        </form>
    </div>

</body>
</html>
