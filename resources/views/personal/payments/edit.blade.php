@extends('main')

@section('title', 'Edit Payment')

@section('content')

@php
    $emp_job_role = session()->get('emp_job_role');
@endphp

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
    
    .current-screenshot {
        margin-top: 10px;
        margin-bottom: 15px;
    }
    
    .current-screenshot img {
        max-width: 200px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
</style>

@include('navbar')
    

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
        <h2 class="section-title" style="text-align: center;">Edit Payment</h2>
        <div class="lead-info-container">
            <div class="lead-info-item"><span style="font-weight: bold;">Lead Name:</span> {{ $payment->lead->first_name }} {{ $payment->lead->last_name }}</div>
            <div class="lead-info-item"><span style="font-weight: bold;">Email:</span> {{ $payment->lead->email }}</div>
            <div class="lead-info-item"><span style="font-weight: bold;">Phone:</span> {{ $payment->lead->phone }}</div>
            <div class="lead-info-item"><span style="font-weight: bold;">University:</span> {{ $payment->lead->university }}</div>
            <div class="lead-info-item"><span style="font-weight: bold;">Course:</span> {{ $payment->lead->courses }}</div>
            @if(isset($payment->lead->branch))
            <div class="lead-info-item"><span style="font-weight: bold;">Branch:</span> {{ $payment->lead->branch }}</div>
            @endif
            @if(isset($payment->lead->college))
            <div class="lead-info-item"><span style="font-weight: bold;">College:</span> {{ $payment->lead->college }}</div>
            @endif
        </div>
    </div>

    <!-- Payment Form Section -->
    <form action="{{ route('payments.update', $payment->id) }}" method="POST" enctype="multipart/form-data" id="paymentForm">
        @csrf
        @method('PUT')
        <input type="hidden" name="lead_id" value="{{ $payment->lead_id }}">
        
        <div class="payment-section">
            <h1 class="section-title">Update Payment:</h1>

            <div class="form-group">
                <label for="total_amount">Total Amount (₹):</label>
                <input type="number" name="total_amount" id="total_amount" class="form-control" required 
                       value="{{ old('total_amount', $payment->pending_amount + $payment->payment_amount) }}">
            </div>

            <div class="form-group">
                <label for="start_year">Start Year:</label>
                <select name="start_year" id="start_year" class="form-control" required>
                    @for ($year = 2022; $year <= 2034; $year++)
                        <option value="{{ $year }}" {{ old('start_year', $payment->start_year) == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endfor
                </select>
            </div>
            
            <div class="form-group">
                <label for="duration">Duration (in years):</label>
                <select name="duration" id="duration" class="form-control" required>
                    <option value="1" {{ old('duration', $payment->duration) == 1 ? 'selected' : '' }}>1 Year</option>
                    <option value="2" {{ old('duration', $payment->duration) == 2 ? 'selected' : '' }}>2 Years</option>
                    <option value="3" {{ old('duration', $payment->duration) == 3 ? 'selected' : '' }}>3 Years</option>
                    <option value="4" {{ old('duration', $payment->duration) == 4 ? 'selected' : '' }}>4 Years</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="session_duration">Session Duration:</label>
                <input type="text" name="session_duration" id="session_duration" class="form-control" readonly required
                       value="{{ old('session_duration', $payment->session_duration) }}">
            </div>

            <div class="form-group">
                <label for="session">Session Type:</label>
                <select name="session" id="session" class="form-control" required>
                    <option value="regular_session" {{ old('session', $payment->session) == 'regular_session' ? 'selected' : '' }}>Regular Session</option>
                    <option value="special_session" {{ old('session', $payment->session) == 'special_session' ? 'selected' : '' }}>Special Session</option>
                    <option value="summer_session" {{ old('session', $payment->session) == 'summer_session' ? 'selected' : '' }}>Summer Session</option>
                    <option value="winter_session" {{ old('session', $payment->session) == 'winter_session' ? 'selected' : '' }}>Winter Session</option>
                </select>
            </div>

            <div class="form-group">
                <label for="fee_type">Fee Type:</label>
                <select name="fee_type" id="fee_type" class="form-control" required>
                    <option value="semester" {{ old('fee_type', $payment->fee_type) == 'semester' ? 'selected' : '' }}>Semester Fee</option>
                    <option value="year" {{ old('fee_type', $payment->fee_type) == 'year' ? 'selected' : '' }}>Year Fee</option>
                    <option value="full_course" {{ old('fee_type', $payment->fee_type) == 'full_course' ? 'selected' : '' }}>Full Course Fee</option>
                </select>
            </div>

            <div class="form-group">
                <label for="payment_screenshot" class="font-weight-bold">
                    Update Payment Screenshot: 
                    <span class="text-danger d-block" style="font-size: 0.9rem;">
                        Please upload an image as <strong>JPEG, PNG, JPG, or GIF</strong> with a maximum size of <strong>2 MB</strong>.
                    </span>
                </label>
                @if($payment->payment_screenshot)
                <div class="current-screenshot">
                    <p>Current Screenshot:</p>
                    <img src="{{ asset('storage/' . $payment->payment_screenshot) }}" alt="Payment Screenshot">
                </div>
                @endif
                <input type="file" name="payment_screenshot" class="form-control">
                <small class="text-muted">Leave empty to keep the current screenshot.</small>
            </div>

            <div class="form-group">
                <label for="payment_mode">Payment Mode:</label>
                <select name="payment_mode" id="payment_mode" class="form-control" required>
                    <option value="">Select Payment Mode</option>
                    <option value="bank_transfer" {{ $payment->payment_mode == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                    <option value="upi" {{ $payment->payment_mode == 'upi' ? 'selected' : '' }}>UPI</option>
                    <option value="credit_card" {{ $payment->payment_mode == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                    <option value="debit_card" {{ $payment->payment_mode == 'debit_card' ? 'selected' : '' }}>Debit Card</option>
                    <option value="net_banking" {{ $payment->payment_mode == 'net_banking' ? 'selected' : '' }}>Net Banking</option>
                    <option value="cash" {{ $payment->payment_mode == 'cash' ? 'selected' : '' }}>Cash</option>
                </select>
            </div>

            <div class="form-group" id="payment_details" style="{{ $payment->payment_details_input ? 'display: block;' : '' }}">
                <label for="payment_details_input">Payment Details:</label>
                <input type="text" name="payment_details_input" id="payment_details_input" class="form-control"
                       value="{{ old('payment_details_input', $payment->payment_details_input) }}">
            </div>

            <div class="form-group">
                <label for="payment_amount">Payment Amount (₹):</label>
                <input type="number" name="payment_amount" id="payment_amount" class="form-control" required
                       value="{{ old('payment_amount', $payment->payment_amount) }}" oninput="calculatePendingAmount()">
            </div>

            <div class="form-group">
                <label for="pending_amount">Pending Amount (₹):</label>
                <input type="number" name="pending_amount" id="pending_amount" class="form-control" readonly
                       value="{{ $payment->pending_amount }}">
            </div>

            <div class="form-group">
                <label for="utr_no">UTR/Transaction Number:</label>
                <input type="text" name="utr_no" id="utr_no" class="form-control" 
                       value="{{ old('utr_no', $payment->utr_no) }}">
            </div>

            <div class="form-group">
                <label for="reference_number">Reference Number:</label>
                <input type="text" name="reference_number" id="reference_number" class="form-control" 
                       value="{{ old('reference_number', $payment->reference_number) }}">
            </div>

            <div class="form-group">
                <label for="payment_date">Payment Date:</label>
                <input type="date" name="payment_date" id="payment_date" class="form-control" required
                       value="{{ old('payment_date', $payment->payment_date ? $payment->payment_date->format('Y-m-d') : date('Y-m-d')) }}">
            </div>

            <div class="form-group">
                <label for="notes">Notes:</label>
                <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes', $payment->notes) }}</textarea>
            </div>
        </div>

        <div class="loan-section">
            <h1 class="section-title">Loan Process (Optional):</h1>
            <button type="button" class="toggle-button" onclick="toggleLoanFields()">+ Loan Process</button>
            <div id="loan-fields" style="{{ $payment->loan_amount ? 'display: block;' : 'display: none;' }}">
                <div class="form-group">
                    <label for="loan_amount">Loan Amount (₹):</label>
                    <input type="number" name="loan_amount" id="loan_amount" class="form-control"
                           value="{{ old('loan_amount', $payment->loan_amount) }}">
                </div>
                <div class="form-group">
                    <label for="bank">Bank Name:</label>
                    <input type="text" name="bank" id="bank" class="form-control"
                           value="{{ old('bank', $payment->bank) }}">
                </div>
                <div class="form-group">
                    <label for="loan_details">Loan Details:</label>
                    <textarea name="loan_details" id="loan_details" class="form-control" rows="3">{{ old('loan_details', $payment->loan_details) }}</textarea>
                </div>
            </div>
        </div>

        <div class="text-center">
            <button type="submit" class="btn-submit">Update Payment</button>
        </div>
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
        const pendingAmount = totalAmount - paymentAmount;
        
        document.getElementById('pending_amount').value = pendingAmount >= 0 ? pendingAmount : 0;
    }
    
    // Session duration calculation
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

    // Calculate pending amount on page load
    document.addEventListener('DOMContentLoaded', function() {
        calculatePendingAmount();
        
        // Also add event listener for total amount changes
        document.getElementById('total_amount').addEventListener('input', calculatePendingAmount);
        
        // Trigger session duration calculation
        updateSessionDuration();
    });
</script>

@endsection
