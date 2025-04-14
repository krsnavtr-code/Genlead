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
        width: 48%;
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

    /* Horizontal Navbar Styles */
    .horizontal-navbar-payment {
        display: flex;
        justify-content: space-around;
        background-color: #f8f9fa;
        margin-left: 221px;
        padding: 17px 0;
        border-bottom: 1px solid #ddd;
    }

    .horizontal-navbar a {
        color: #007bff;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        padding: 8px 16px;
        border-radius: 4px;
    }

    .horizontal-navbar a:hover {
        background-color: #007bff;
        color: white;
    }
</style>


<div class="horizontal-navbar-payment">
    <a href="{{ url('/i-admin/show-leads') }}">Manage Leads</a>
    <a href="{{ url('/admin/activities/create') }}">Add Activities</a>
    <a href="{{ url('/admin/activities') }}">Manage Activities</a>
    <a href="{{ url('/admin/tasks/create') }}">Create/Add Tasks</a>
    <a href="{{ url('/admin/tasks') }}">Manage Tasks</a>
    <a href="{{ url('/i-admin/pending') }}">Pending Payment</a>
</div>

<div class="payment-container">
    <!-- Lead Info Section -->
    <div class="lead-info-section">
        <h2 class="section-title" style="text-align: center;">Lead Payment Page</h2>
        <div class="lead-info-container">
            <div class="lead-info-item">Lead Name: {{ $lead->first_name }} {{ $lead->last_name }}</div>
            <div class="lead-info-item">Email: {{ $lead->email }}</div>
            <div class="lead-info-item">Phone: {{ $lead->phone }}</div>
            <div class="lead-info-item">University: {{ $lead->university }}</div>
            <div class="lead-info-item">Course: {{ $lead->courses }}</div>
            <div class="lead-info-item">Branch: {{ $lead->branch }}</div>
            <div class="lead-info-item">College: {{ $lead->college }}</div>
        </div>
    </div>

    <!-- Payment Form Section -->
    <form action="{{ url('/i-admin/process-payment') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="payment-section">
            <h1 class="section-title">Payment Process:</h1>

            <!-- Include lead_id as a hidden field -->
            <input type="hidden" name="lead_id" value="{{ $lead->id }}">

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
                <label for="utr_no">Transaction ID/UTR No:</label>
                <input type="text" name="utr_no" class="form-control" required pattern="\d{12}" title="UTR number should be 12 digits" maxlength="12" required>
            </div>

            <div class="form-group">
                <label for="payment_mode">Payment Mode:</label>
                <select name="payment_mode" id="payment_mode" class="form-control" required>
                    <option value="upi">UPI</option>
                    <option value="netbanking">Netbanking</option>
                    <option value="credit_card">Credit Card</option>
                    <option value="debit_card">Debit Card</option>
                    <option value="others">Others</option>
                </select>
            </div>

            <!-- Simple input box for all payment details -->
            <div class="form-group" id="payment_details">
                <label for="payment_details_input">Payment Bank Details:</label>
                <input type="text" name="payment_details_input" class="form-control" placeholder="Enter payment details here">
            </div>

            <div class="form-group">
                <label for="payment_amount">Payment Amount:</label>
                <input type="number" name="payment_amount" class="form-control" required>
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
        const paymentDetails = document.getElementById('payment_details');
        paymentDetails.style.display = 'block';
    });

    // Toggle Loan Fields
    function toggleLoanFields() {
        const loanFields = document.getElementById('loan-fields');
        loanFields.style.display = loanFields.style.display === 'none' ? 'block' : 'none';
    }
</script>

@endsection
