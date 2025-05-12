@extends('main')

@section('title', 'Payment System')

@section('content')

<style>
    .payment-container {
        width: 100%;
        max-width: 900px;
        margin: 0 auto;
        padding: 20px;
        background: #ffffff;
        border: 1px solid #ddd;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }

    .section-title {
        font-size: 20px;
        font-weight: 600;
        color: #333;
        margin-bottom: 15px;
    }

    .lead-info-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        font-family: Arial, sans-serif;
        font-size: 16px;
        color: #333;
        line-height: 1.5;
        margin-bottom: 25px;
    }

    .lead-info-item {
        width: 262px;
        margin-bottom: 10px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    label {
        font-weight: 500;
        color: #333;
        margin-bottom: 5px;
        display: block;
    }

    .form-control {
        border: 1px solid #ddd;
        padding: 10px;
        font-size: 15px;
        border-radius: 4px;
        width: 100%;
    }

    .btn-submit {
        background-color: #007bff;
        color: #fff;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 600;
        font-size: 16px;
        width: 100%;
        max-width: 300px;
        margin-top: 20px;
    }

    .payment-section, .loan-section {
        margin-bottom: 20px;
    }

    #payment_details {
        display: none;
    }

    .toggle-button {
        background-color: #007bff;
        color: #fff;
        padding: 8px 12px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        margin-top: 10px;
    }
</style>


<!-- Horizontal Navbar -->
    <div class="horizontal-navbar d-flex flex-wrap justify-content-around py-2 border-bottom mb-3">
        <a href="{{ url('/i-admin/show-leads') }}" class="btn m-1">Manage Leads</a>
        <a href="{{ url('/admin/activities/create') }}" class="btn m-1">Add Activities</a>
        <a href="{{ url('/admin/activities') }}" class="btn m-1">Manage Activities</a>
        <a href="{{ url('/admin/tasks/create') }}" class="btn m-1">Create/Add Tasks</a>
        <a href="{{ url('/admin/tasks') }}" class="btn m-1">Manage Tasks</a>
        <a href="{{ url('/i-admin/pending') }}" class="btn m-1">Pending Payment</a>
    </div>
    
    <!-- Payment Navigation -->
    <div class="d-flex flex-wrap justify-content-center gap-2 mb-3">
        <a href="{{ route('payments.index') }}" class="btn btn-primary">All Payments</a>
        <a href="{{ url('/i-admin/pending') }}" class="btn btn-primary">Pending Payments</a>
        <a href="{{ route('payment.verify') }}" class="btn btn-primary">Verify Payments</a>
        <a href="{{ route('payment.guide') }}" class="btn btn-info">Payment Guide</a>
    </div>

<div class="payment-container">
    <!-- Lead Info Section -->
    <div class="lead-info-section">
        <h2 class="section-title" style="text-align: center;">Lead Payment Page</h2>
        <div class="lead-info-container">
            <div class="lead-info-item"><span style="font-weight: bold;">Lead Name:</span> {{ $lead->first_name }} {{ $lead->last_name }}</div>
            <div class="lead-info-item"><span style="font-weight: bold;">Email:</span> {{ $lead->email }}</div>
            <div class="lead-info-item"><span style="font-weight: bold;">Phone:</span> {{ $lead->phone }}</div>
            <div class="lead-info-item"><span style="font-weight: bold;">University:</span> {{ $lead->university }}</div>
            <div class="lead-info-item"><span style="font-weight: bold;">Course:</span> {{ $lead->courses }}</div>
            <div class="lead-info-item"><span style="font-weight: bold;">Branch:</span> {{ $lead->branch ?? 'N/A' }}</div>
            <div class="lead-info-item"><span style="font-weight: bold;">College:</span> {{ $lead->college ?? 'N/A' }}</div>
        </div>
        
        @if(isset($totalPaid) && $totalPaid > 0)
        <div style="margin-top: 15px; padding: 10px; background-color: #f8f9fa; border: 1px solid #ddd; border-radius: 5px;">
            <h3 class="section-title">Payment Summary</h3>
            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                <div><strong>Total Fees:</strong> ₹{{ number_format($totalAmount, 2) }}</div>
                <div><strong>Total Paid:</strong> ₹{{ number_format($totalPaid, 2) }}</div>
                <div><strong>Pending Amount:</strong> <span style="color: #e74c3c; font-weight: bold;">₹{{ number_format($pendingAmount, 2) }}</span></div>
            </div>
        </div>
        @endif
        
        @if(isset($paymentHistory) && count($paymentHistory) > 0)
        <div style="margin-top: 15px;">
            <h3 class="section-title">Payment History</h3>
            <div style="max-height: 200px; overflow-y: auto; border: 1px solid #ddd; border-radius: 5px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f2f2f2;">
                            <th style="padding: 8px; text-align: left; border-bottom: 1px solid #ddd;">Date</th>
                            <th style="padding: 8px; text-align: right; border-bottom: 1px solid #ddd;">Amount</th>
                            <th style="padding: 8px; text-align: center; border-bottom: 1px solid #ddd;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($paymentHistory as $payment)
                        <tr>
                            <td style="padding: 8px; text-align: left; border-bottom: 1px solid #ddd;">{{ $payment['date'] }}</td>
                            <td style="padding: 8px; text-align: right; border-bottom: 1px solid #ddd;">₹{{ number_format($payment['amount'], 2) }}</td>
                            <td style="padding: 8px; text-align: center; border-bottom: 1px solid #ddd;">
                                <span style="padding: 3px 8px; border-radius: 3px; font-size: 12px; text-transform: capitalize;
                                    @if($payment['status'] == 'verified') background-color: #28a745; color: white;
                                    @elseif($payment['status'] == 'pending') background-color: #ffc107; color: black;
                                    @else background-color: #dc3545; color: white;
                                    @endif">
                                    {{ $payment['status'] }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

    <!-- Payment Form Section -->
    <form action="{{ url('/i-admin/process-payment') }}" method="POST" enctype="multipart/form-data" id="paymentForm">
        @csrf
        <input type="hidden" name="lead_id" value="{{ $lead->id }}">
        
        <div class="payment-section">
            <h1 class="section-title">Payment Process:</h1>

            <div class="form-group">
                <label for="total_amount">Total Amount (₹):</label>
                <input type="number" name="total_amount" id="total_amount" class="form-control" required 
                       value="{{ old('total_amount', $totalAmount ?? 0) }}">
                @if(isset($totalPaid) && $totalPaid > 0)
                <small class="text-info">This is the total course fee. You've already paid ₹{{ number_format($totalPaid, 2) }}.</small>
                @endif
            </div>

            <div class="form-group">
                <label for="start_year">Start Year:</label>
                <select name="start_year" id="start_year" class="form-control" required>
                    @for ($year = 2022; $year <= 2034; $year++)
                        <option value="{{ $year }}" {{ old('start_year', $startYear) == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endfor
                </select>
            </div>
            <div class="form-group">
                <label for="duration">Duration (in years):</label>
                <select name="duration" id="duration" class="form-control" required>
                    <option value="1" {{ old('duration', $duration) == 1 ? 'selected' : '' }}>1 Year</option>
                    <option value="2" {{ old('duration', $duration) == 2 ? 'selected' : '' }}>2 Years</option>
                    <option value="3" {{ old('duration', $duration) == 3 ? 'selected' : '' }}>3 Years</option>
                    <option value="4" {{ old('duration', $duration) == 4 ? 'selected' : '' }}>4 Years</option>
                </select>
            </div>
            <div class="form-group">
                <label for="session_duration">Session Duration:</label>
                <input type="text" name="session_duration" id="session_duration" class="form-control" readonly required
                       value="{{ old('session_duration', $sessionDuration) }}">
            </div>
            
            <div class="form-group">
                <label for="session">Session Type:</label>
                <select name="session" id="session" class="form-control" required>
                    <option value="regular_session" {{ old('session', $sessionType) == 'regular_session' ? 'selected' : '' }}>Regular Session</option>
                    <option value="special_session" {{ old('session', $sessionType) == 'special_session' ? 'selected' : '' }}>Special Session</option>
                    <option value="summer_session" {{ old('session', $sessionType) == 'summer_session' ? 'selected' : '' }}>Summer Session</option>
                    <option value="winter_session" {{ old('session', $sessionType) == 'winter_session' ? 'selected' : '' }}>Winter Session</option>
                </select>
            </div>

            <div class="form-group">
                <label for="fee_type">Fee Type:</label>
                <select name="fee_type" id="fee_type" class="form-control" required>
                    <option value="semester" {{ old('fee_type', $feeType) == 'semester' ? 'selected' : '' }}>Semester Fee</option>
                    <option value="year" {{ old('fee_type', $feeType) == 'year' ? 'selected' : '' }}>Year Fee</option>
                    <option value="full_course" {{ old('fee_type', $feeType) == 'full_course' ? 'selected' : '' }}>Full Course Fee</option>
                </select>
            </div>

            <div class="form-group">
                <label for="payment_screenshot" class="font-weight-bold">
                    Upload Payment Screenshot: 
                    <span class="text-danger d-block" style="font-size: 0.9rem;">
                        Please upload an image as <strong>JPEG, PNG, JPG, or GIF</strong> with a maximum size of <strong>2 MB</strong>.
                    </span>
                </label>
                <input type="file" name="payment_screenshot" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="payment_mode">Payment Mode:</label>
                <select name="payment_mode" id="payment_mode" class="form-control" required>
                    <option value="">Select Payment Mode</option>
                    <option value="bank_transfer" {{ old('payment_mode') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                    <option value="upi" {{ old('payment_mode') == 'upi' ? 'selected' : '' }}>UPI</option>
                    <option value="credit_card" {{ old('payment_mode') == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                    <option value="debit_card" {{ old('payment_mode') == 'debit_card' ? 'selected' : '' }}>Debit Card</option>
                    <option value="net_banking" {{ old('payment_mode') == 'net_banking' ? 'selected' : '' }}>Net Banking</option>
                    <option value="cash" {{ old('payment_mode') == 'cash' ? 'selected' : '' }}>Cash</option>
                </select>
            </div>

            <div class="form-group" id="payment_details">
                <label for="payment_details_input">Payment Details:</label>
                <input type="text" name="payment_details_input" id="payment_details_input" class="form-control"
                       value="{{ old('payment_details_input') }}">
            </div>

            <div class="form-group">
                <label for="payment_amount">Payment Amount (₹):</label>
                <input type="number" name="payment_amount" id="payment_amount" class="form-control" required
                       value="{{ old('payment_amount', isset($pendingAmount) && $pendingAmount > 0 ? $pendingAmount : 0) }}" oninput="calculatePendingAmount()">
                @if(isset($pendingAmount) && $pendingAmount > 0)
                <small class="text-info">Suggested amount based on pending balance of ₹{{ number_format($pendingAmount, 2) }}</small>
                @endif
            </div>
            
            <div class="form-group">
                <label for="pending_amount">Pending Amount (₹):</label>
                <input type="number" name="pending_amount" id="pending_amount" class="form-control" readonly
                       value="{{ old('pending_amount', isset($pendingAmount) ? $pendingAmount : 0) }}">
                <small class="text-muted">This will be automatically calculated based on Total Amount and Payment Amount</small>
            </div>

            <div class="form-group">
                <label for="utr_no">UTR/Transaction Number:</label>
                <input type="text" name="utr_no" id="utr_no" class="form-control" value="{{ old('utr_no') }}">
            </div>

            <div class="form-group">
                <label for="payment_details">Payment Details (you can write here about payment brief details):</label>
                <input type="text" name="payment_details" class="form-control" placeholder="This is payment details session 2016-17 of 2ndsemester">
            </div>
        </div>

        <!-- Loan Process Section -->
        <div class="loan-section">
            <h1 class="section-title">Loan Process (Optional):</h1>
            <button type="button" class="toggle-button" onclick="toggleLoanFields()">+ Loan Process</button>
            <div id="loan-fields" style="display: none;">
                <div class="form-group">
                    <label for="loan_amount">Loan Amount:</label>
                    <input type="number" name="loan_amount" class="form-control">
                </div>

                <div class="form-group">
                    <label for="loan_details">Loan Details:</label>
                    <textarea name="loan_details" class="form-control"></textarea>
                </div>
            </div>
        </div>

        <button type="submit" class="btn-submit">Submit Payment Details</button>
    </form>
</div>

<script>
    // Show the payment details input box for all payment modes
    document.getElementById('payment_mode').addEventListener('change', function () {
        const paymentDetails = document.getElementById('payment_details');
        if (this.value) {
            paymentDetails.style.display = 'block';
        } else {
            paymentDetails.style.display = 'none';
        }
    });

    // Toggle loan fields visibility
    function toggleLoanFields() {
        const loanFields = document.getElementById('loan-fields');
        if (loanFields.style.display === 'none') {
            loanFields.style.display = 'block';
        } else {
            loanFields.style.display = 'none';
        }
    }

    // Calculate pending amount based on total and payment amount
    function calculatePendingAmount() {
        const totalAmount = parseFloat(document.getElementById('total_amount').value) || 0;
        const paymentAmount = parseFloat(document.getElementById('payment_amount').value) || 0;
        
        // For subsequent payments, we need to account for already paid amounts
        let alreadyPaid = 0;
        @if(isset($totalPaid) && $totalPaid > 0)
            alreadyPaid = {{ $totalPaid }};
        @endif
        
        // Calculate pending amount after this payment
        const pendingAmount = totalAmount - (alreadyPaid + paymentAmount);
        
        document.getElementById('pending_amount').value = pendingAmount >= 0 ? pendingAmount : 0;
    }

    // Form validation
    document.getElementById('paymentForm').addEventListener('submit', function(e) {
        const totalAmount = parseFloat(document.getElementById('total_amount').value) || 0;
        const paymentAmount = parseFloat(document.getElementById('payment_amount').value) || 0;
        
        if (paymentAmount <= 0) {
            e.preventDefault();
            alert('Payment amount must be greater than 0');
            return false;
        }
        
        if (paymentAmount > totalAmount) {
            e.preventDefault();
            alert('Payment amount cannot be greater than total amount');
            return false;
        }
    });

    // Toggle Loan Fields
    function toggleLoanFields() {
        const loanFields = document.getElementById('loan-fields');
        loanFields.style.display = loanFields.style.display === 'none' ? 'block' : 'none';
    }

    // session duration calculation
    document.getElementById('start_year').addEventListener('change', updateSessionDuration);
    document.getElementById('duration').addEventListener('change', updateSessionDuration);

    function updateSessionDuration() {
        const startYear = parseFloat(document.getElementById('start_year').value);
        const duration = parseFloat(document.getElementById('duration').value);
        
        if (!isNaN(startYear) && !isNaN(duration)) {
            const endYear = startYear + Math.floor(duration);
            document.getElementById('session_duration').value = `${startYear}-${endYear}`;
        }
    }

    // Consolidated event listener for page load
    document.addEventListener('DOMContentLoaded', function() {
        // Calculate pending amount
        calculatePendingAmount();
        
        // Add event listener for total amount changes
        document.getElementById('total_amount').addEventListener('input', calculatePendingAmount);
        document.getElementById('payment_amount').addEventListener('input', calculatePendingAmount);
        
        // Show payment details if a payment mode is already selected
        const paymentMode = document.getElementById('payment_mode');
        if (paymentMode.value) {
            document.getElementById('payment_details').style.display = 'block';
        } else {
            document.getElementById('payment_mode').dispatchEvent(new Event('change'));
        }
        
        // Initialize session duration
        updateSessionDuration();
    });
</script>

@endsection
