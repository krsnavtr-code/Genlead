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
            <div class="lead-info-item"><span style="font-weight: bold;">Branch:</span> {{ $lead->branch }}</div>
            <div class="lead-info-item"><span style="font-weight: bold;">College:</span> {{ $lead->college }}</div>
        </div>
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
                       value="{{ old('total_amount', $lead->total_fees ?? '') }}">
            </div>

            <div class="form-group">
                <label for="session_duration">Session Duration:</label>
                <select name="session_duration" id="session_duration" class="form-control" required>
                    @for ($year = 2016; $year <= 2027; $year++)
                        <option value="{{ $year }}-{{ $year + 1 }}">{{ $year }}-{{ $year + 1 }}</option>
                    @endfor
                </select>
            </div>

            <div class="form-group">
                <label for="session">Session Fee:</label>
                <select name="session" id="session" class="form-control" required>
                    <option value="semester">Semester Fee</option>
                    <option value="year">Year Fee</option>
                    <option value="full_course">Full Course Fee</option>
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
                       value="{{ old('payment_amount') }}" oninput="calculatePendingAmount()">
            </div>

            <div class="form-group">
                <label for="pending_amount">Pending Amount (₹):</label>
                <input type="number" name="pending_amount" id="pending_amount" class="form-control" readonly>
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
            <div id="loan-fields">
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
        var paymentDetails = document.getElementById('payment_details');
        if (this.value === 'bank_transfer' || this.value === 'upi' || this.value === 'credit_card' || this.value === 'debit_card' || this.value === 'net_banking') {
            paymentDetails.style.display = 'block';
        } else {
            paymentDetails.style.display = 'none';
        }
    });

    // Calculate pending amount
    function calculatePendingAmount() {
        const totalAmount = parseFloat(document.getElementById('total_amount').value) || 0;
        const paymentAmount = parseFloat(document.getElementById('payment_amount').value) || 0;
        const pendingAmount = Math.max(0, totalAmount - paymentAmount);
        document.getElementById('pending_amount').value = pendingAmount.toFixed(2);
    }

    // Calculate pending amount when total amount changes
    document.getElementById('total_amount').addEventListener('input', calculatePendingAmount);

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

    // Trigger the change event on page load
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('payment_mode').dispatchEvent(new Event('change'));
        calculatePendingAmount();
    });

    // Toggle Loan Fields
    function toggleLoanFields() {
        const loanFields = document.getElementById('loan-fields');
        loanFields.style.display = loanFields.style.display === 'none' ? 'block' : 'none';
    }
</script>

@endsection
