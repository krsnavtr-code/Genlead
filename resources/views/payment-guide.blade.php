@extends('main')

@section('title', 'Payment System Guide')

@section('content')

<style>
    .guide-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 20px;
        background: #ffffff;
        border: 1px solid #ddd;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }
    
    .section-title {
        font-size: 24px;
        font-weight: 600;
        color: #333;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }
    
    .subsection-title {
        font-size: 20px;
        font-weight: 600;
        color: #444;
        margin: 25px 0 15px;
    }
    
    .guide-text {
        font-size: 16px;
        line-height: 1.6;
        color: #555;
        margin-bottom: 15px;
    }
    
    .step {
        margin-bottom: 20px;
        padding: 15px;
        background-color: #f9f9f9;
        border-left: 4px solid #007bff;
        border-radius: 4px;
    }
    
    .step-number {
        font-size: 18px;
        font-weight: 600;
        color: #007bff;
        margin-bottom: 10px;
    }
    
    .step-content {
        font-size: 15px;
        line-height: 1.5;
    }
    
    .navigation-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin: 25px 0;
    }
    
    .nav-button {
        display: inline-block;
        padding: 10px 15px;
        background-color: #007bff;
        color: white;
        text-decoration: none;
        border-radius: 4px;
        font-weight: 500;
        transition: background-color 0.3s;
    }
    
    .nav-button:hover {
        background-color: #0056b3;
        color: white;
        text-decoration: none;
    }
    
    .note-box {
        padding: 15px;
        background-color: #fff3cd;
        border-left: 4px solid #ffc107;
        margin: 20px 0;
        border-radius: 4px;
    }
    
    .note-title {
        font-weight: 600;
        color: #856404;
        margin-bottom: 5px;
    }
    
    .code-block {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 4px;
        font-family: monospace;
        margin: 15px 0;
        overflow-x: auto;
    }
    
    table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
    }
    
    table th, table td {
        padding: 10px;
        border: 1px solid #ddd;
    }
    
    table th {
        background-color: #f2f2f2;
        font-weight: 600;
    }
    
    table tr:nth-child(even) {
        background-color: #f9f9f9;
    }
</style>

@include('navbar')


<div class="guide-container">
    <h1 class="section-title">Payment System Guide</h1>
    
    <div class="navigation-buttons">
        <a href="{{ route('payments.index') }}" class="nav-button">All Payments</a>
        <a href="{{ url('/i-admin/pending') }}" class="nav-button">Pending Payments</a>
        <a href="{{ route('payment.verify') }}" class="nav-button">Verify Payments</a>
    </div>
    
    <p class="guide-text">
        The Genlead payment system allows you to record, track, and verify payments made by leads. 
        This guide explains how to use the payment system effectively.
    </p>
    
    <h2 class="subsection-title">1. Recording a New Payment</h2>
    
    <div class="step">
        <div class="step-number">Step 1: Access the Payment Form</div>
        <div class="step-content">
            From a lead's profile page, click on the "Add Payment" button. This will take you to the payment form.
            <br>
            <strong>URL:</strong> <code>/i-admin/lead/{leadId}/payment</code>
        </div>
    </div>
    
    <div class="step">
        <div class="step-number">Step 2: Fill in Payment Details</div>
        <div class="step-content">
            <ul>
                <li><strong>Total Amount:</strong> The total fees for the course/program.</li>
                <li><strong>Session Duration:</strong> The academic year (e.g., 2023-2024).</li>
                <li><strong>Session Fee:</strong> Whether this is a semester fee, yearly fee, or full course fee.</li>
                <li><strong>Payment Screenshot:</strong> Upload proof of payment (JPEG, PNG, JPG, or PDF).</li>
                <li><strong>Payment Mode:</strong> Bank Transfer, UPI, Credit Card, etc.</li>
                <li><strong>Payment Amount:</strong> The amount being paid now.</li>
                <li><strong>Pending Amount:</strong> This will be calculated automatically.</li>
                <li><strong>UTR/Transaction Number:</strong> The unique transaction reference.</li>
                <li><strong>Payment Date:</strong> When the payment was made.</li>
            </ul>
            
            <p>For loan payments, you can click the "Loan Process" button to add loan details.</p>
        </div>
    </div>
    
    <div class="step">
        <div class="step-number">Step 3: Submit the Payment</div>
        <div class="step-content">
            Click the "Submit Payment" button to record the payment. The payment will be saved with a status of "pending" until it's verified.
        </div>
    </div>
    
    <div class="note-box">
        <div class="note-title">Note: Pending Amount</div>
        <p>When making subsequent payments for a lead, the system will automatically show the pending amount from previous payments. This makes it easier to track how much is still owed.</p>
    </div>
    
    <h2 class="subsection-title">2. Viewing Payments</h2>
    
    <div class="step">
        <div class="step-number">Option 1: View All Payments</div>
        <div class="step-content">
            Go to the Payments List page to see all payments you have access to.
            <br>
            <strong>URL:</strong> <code>/i-admin/payment</code>
        </div>
    </div>
    
    <div class="step">
        <div class="step-number">Option 2: View Pending Payments</div>
        <div class="step-content">
            Go to the Pending Payments page to see leads with pending payments.
            <br>
            <strong>URL:</strong> <code>/i-admin/pending</code>
        </div>
    </div>
    
    <div class="step">
        <div class="step-number">Option 3: View Payment Details</div>
        <div class="step-content">
            Click on the "View" button next to any payment to see its full details.
            <br>
            <strong>URL:</strong> <code>/i-admin/payment/{id}</code>
        </div>
    </div>
    
    <h2 class="subsection-title">3. Verifying Payments</h2>
    
    <div class="step">
        <div class="step-number">Step 1: Access the Payment Verification Page</div>
        <div class="step-content">
            Go to the Payment Verification page to see all payments pending verification.
            <br>
            <strong>URL:</strong> <code>/admin/lead/payment-verify</code>
        </div>
    </div>
    
    <div class="step">
        <div class="step-number">Step 2: Review Payment Details</div>
        <div class="step-content">
            Review the payment details, including the payment screenshot, to ensure the payment is valid.
        </div>
    </div>
    
    <div class="step">
        <div class="step-number">Step 3: Verify or Reject the Payment</div>
        <div class="step-content">
            Click the "Verify" button to confirm the payment. This will update the payment status to "verified" and update the lead's payment status and pending amount accordingly.
        </div>
    </div>
    
    <div class="note-box">
        <div class="note-title">Note: Payment Status Impact</div>
        <p>When a payment is verified, the system automatically:</p>
        <ul>
            <li>Updates the payment status to "verified"</li>
            <li>Recalculates the lead's total paid amount</li>
            <li>Updates the lead's pending amount</li>
            <li>Changes the lead's status to "payment_completed" if all fees are paid, or "payment_partial" if there's still a pending amount</li>
        </ul>
    </div>
    
    <h2 class="subsection-title">4. Updating Payments</h2>
    
    <div class="step">
        <div class="step-number">Step 1: Find the Payment to Update</div>
        <div class="step-content">
            Go to the Payments List or view a specific lead's payments, then click the "Edit" button next to the payment you want to update.
            <br>
            <strong>URL:</strong> <code>/i-admin/payment/{id}/edit</code>
        </div>
    </div>
    
    <div class="step">
        <div class="step-number">Step 2: Update Payment Details</div>
        <div class="step-content">
            Modify the payment details as needed. You can update the payment amount, payment mode, UTR number, etc.
        </div>
    </div>
    
    <div class="step">
        <div class="step-number">Step 3: Save Changes</div>
        <div class="step-content">
            Click the "Update Payment" button to save your changes. The system will recalculate the pending amount and update the lead's payment status accordingly.
        </div>
    </div>
    
    <h2 class="subsection-title">Payment Status Meanings</h2>
    
    <table>
        <thead>
            <tr>
                <th>Status</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Pending</td>
                <td>Payment has been recorded but not yet verified by an admin or accountant</td>
            </tr>
            <tr>
                <td>Verified</td>
                <td>Payment has been verified and confirmed</td>
            </tr>
            <tr>
                <td>Rejected</td>
                <td>Payment has been rejected (e.g., due to invalid proof or incorrect information)</td>
            </tr>
        </tbody>
    </table>
    
    <div class="navigation-buttons">
        <a href="{{ route('payments.index') }}" class="nav-button">All Payments</a>
        <a href="{{ url('/i-admin/pending') }}" class="nav-button">Pending Payments</a>
        <a href="{{ route('payment.verify') }}" class="nav-button">Verify Payments</a>
    </div>
</div>

@endsection
