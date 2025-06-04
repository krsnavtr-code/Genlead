<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>OTP Verification - Agent Registration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

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
                <div id="countdown-container" class="mt-2">
                    Redirecting to login page in <span id="countdown-number">5</span> seconds...
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
            
            <script>
                // Wait for the DOM to be fully loaded
                document.addEventListener('DOMContentLoaded', function() {
                    const countdownContainer = document.getElementById('countdown-container');
                    
                    // Only run if the countdown container exists
                    if (countdownContainer) {
                        let countdown = 5;
                        const countdownElement = document.getElementById('countdown-number');
                        
                        // Start the countdown immediately
                        const timer = setInterval(function() {
                            countdown--;
                            
                            // Update the countdown display
                            if (countdownElement) {
                                countdownElement.textContent = countdown;
                            }
                            
                            // Redirect when countdown reaches 0
                            if (countdown <= 0) {
                                clearInterval(timer);
                                window.location.href = "{{ route('login') }}";
                            }
                        }, 1000);
                        
                        // Clean up the interval if the user navigates away
                        window.addEventListener('beforeunload', function() {
                            clearInterval(timer);
                        });
                    }
                });
            </script>

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
                    <input type="text" 
                           name="otp" 
                           id="otp"
                           class="form-control otp-input text-center @error('otp') is-invalid @enderror" 
                           maxlength="6" 
                           required
                           value="{{ old('otp') }}"
                           autocomplete="one-time-code"
                           autofocus>
                </div>
                @error('otp')
                    <div class="invalid-feedback d-block text-center">
                        {{ $message }}
                    </div>
                @enderror

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
                <div id="resend-otp-container">
                    <span>Didn't receive the OTP? </span>
                    <a href="#" id="resend-otp" class="text-decoration-none">
                        Resend OTP
                    </a>
                    <span id="countdown-text" style="display: none;">
                        Resend OTP in <span id="countdown">30</span>s
                    </span>
                </div>
                <div id="resend-status" class="small mt-1"></div>
            </div>

            <div>
                <p>After Registration you will receive an email with <span style="font-weight: bold;">Login Credentials</span></p>
            </div>
            
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const resendBtn = document.getElementById('resend-otp');
                    const countdownText = document.getElementById('countdown-text');
                    const countdownElement = document.getElementById('countdown');
                    const resendStatus = document.getElementById('resend-status');
                    let countdown = 30;
                    let countdownInterval;
                    
                    // Disable resend button for 30 seconds on page load
                    startCountdown();
                    
                    resendBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        
                        // Show loading state
                        resendStatus.textContent = 'Sending...';
                        resendStatus.className = 'small mt-1 text-primary';
                        
                        // Make AJAX request to resend OTP
                        fetch('{{ route("agent.resend-otp") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({})
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                resendStatus.textContent = data.message;
                                resendStatus.className = 'small mt-1 text-success';
                                startCountdown();
                            } else {
                                resendStatus.textContent = data.message || 'Failed to resend OTP. Please try again.';
                                resendStatus.className = 'small mt-1 text-danger';
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            resendStatus.textContent = 'An error occurred. Please try again.';
                            resendStatus.className = 'small mt-1 text-danger';
                        });
                    });
                    
                    function startCountdown() {
                        // Reset countdown
                        countdown = 30;
                        countdownElement.textContent = countdown;
                        
                        // Disable resend button and show countdown
                        resendBtn.style.display = 'none';
                        countdownText.style.display = 'inline';
                        
                        // Start countdown
                        clearInterval(countdownInterval);
                        countdownInterval = setInterval(() => {
                            countdown--;
                            countdownElement.textContent = countdown;
                            
                            if (countdown <= 0) {
                                clearInterval(countdownInterval);
                                resendBtn.style.display = 'inline';
                                countdownText.style.display = 'none';
                            }
                        }, 1000);
                    }
                });
            </script>
        </form>
    </div>

</body>
</html>
