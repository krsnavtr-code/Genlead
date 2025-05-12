@extends('main')

@section('title', 'Payment System Guide')

@section('content')

<style>
    .guide-container {
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
        font-size: 24px;
        font-weight: 600;
        color: #333;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }

    .subsection-title {
        font-size: 20px;
        font-weight: 600;
        color: #444;
        margin: 20px 0 10px;
    }

    .guide-step {
        margin-bottom: 30px;
        padding-left: 20px;
        border-left: 3px solid #007bff;
    }

    .step-number {
        display: inline-block;
        width: 30px;
        height: 30px;
        background-color: #007bff;
        color: white;
        text-align: center;
        line-height: 30px;
        border-radius: 50%;
        margin-right: 10px;
        font-weight: bold;
    }

    .step-title {
        font-size: 18px;
        font-weight: 600;
        color: #333;
        margin-bottom: 10px;
        display: inline-block;
    }

    .step-description {
        margin-left: 40px;
        color: #555;
        line-height: 1.6;
    }

    .field-explanation {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        margin: 15px 0;
        border-left: 3px solid #6c757d;
    }

    .field-name {
        font-weight: 600;
        color: #333;
    }

    .important-note {
        background-color: #fff3cd;
        padding: 15px;
        border-radius: 5px;
        margin: 15px 0;
        border-left: 3px solid #ffc107;
    }

    .navigation-buttons {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin: 20px 0;
    }

    .nav-button {
        padding: 8px 16px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 4px;
        text-decoration: none;
        font-weight: 500;
    }

    .nav-button:hover {
        background-color: #0069d9;
        color: white;
        text-decoration: none;
    }
</style>

<!-- Payment Navigation -->
<div class="navigation-buttons">
    <a href="{{ route('payments.index') }}" class="nav-button">All Payments</a>
    <a href="{{ url('/i-admin/pending') }}" class="nav-button">Pending Payments</a>
    <a href="{{ route('payment.verify') }}" class="nav-button">Verify Payments</a>
    <a href="{{ route('payment.guide') }}" class="nav-button" style="background-color: #28a745;">Payment Guide</a>
</div>

<div class="guide-container">
    <h1 class="section-title">Complete Payment System Guide</h1>
    
    <div class="important-note">
        <strong>Important:</strong> This guide covers the entire payment process, from recording initial payments to managing subsequent payments and verifying payment records.
    </div>
    
    <h2 class="subsection-title">First-Time Payment Process</h2>
    
    <div class="guide-step">
        <div class="step-number">1</div>
        <div class="step-title">Access the Lead Payment Page</div>
        <div class="step-description">
            <p>Navigate to the lead management section and click on "Payment" for the specific lead you want to process a payment for.</p>
            <p>This will take you to the payment form where you can see the lead's information and payment history (if any).</p>
        </div>
    </div>
    
    <div class="guide-step">
        <div class="step-number">2</div>
        <div class="step-title">Fill in Payment Details</div>
        <div class="step-description">
            <p>Complete all required fields in the payment form:</p>
            
            <div class="field-explanation">
                <p><span class="field-name">Total Amount (₹):</span> The total fee amount for the entire course or program.</p>
                <p><span class="field-name">Start Year:</span> The academic year when the course/program starts.</p>
                <p><span class="field-name">Duration (in years):</span> The length of the course/program in years.</p>
                <p><span class="field-name">Payment Amount (₹):</span> The amount being paid in this transaction.</p>
                <p><span class="field-name">Session Duration:</span> This is automatically calculated based on the Start Year and Duration.</p>
                <p><span class="field-name">Session Type:</span> Select the appropriate session type (Regular, Special, Summer, or Winter).</p>
                <p><span class="field-name">Fee Type:</span> Specify whether this is a Semester Fee, Year Fee, or Full Course Fee.</p>
                <p><span class="field-name">Payment Mode:</span> Select the method of payment (Online Transfer, Cash, Cheque, etc.).</p>
                <p><span class="field-name">UTR/Transaction Number:</span> Enter the unique transaction reference number.</p>
                <p><span class="field-name">Payment Date:</span> The date when the payment was made.</p>
                <p><span class="field-name">Payment Screenshot:</span> Upload proof of payment (receipt, screenshot, etc.).</p>
            </div>
            
            <p>The system will automatically calculate the pending amount based on the total amount and payment amount.</p>
        </div>
    </div>
    
    <div class="guide-step">
        <div class="step-number">3</div>
        <div class="step-title">Submit Payment Details</div>
        <div class="step-description">
            <p>After filling in all required fields, click the "Submit Payment Details" button.</p>
            <p>The system will validate your inputs and create a new payment record.</p>
            <p>You will be redirected to the payment details page where you can see the payment information.</p>
        </div>
    </div>
    
    <h2 class="subsection-title">Subsequent Payment Process</h2>
    
    <div class="guide-step">
        <div class="step-number">1</div>
        <div class="step-title">Access the Lead Payment Page</div>
        <div class="step-description">
            <p>Navigate to the lead management section and click on "Payment" for the lead you want to process an additional payment for.</p>
            <p>You will see the payment history section showing all previous payments.</p>
        </div>
    </div>
    
    <div class="guide-step">
        <div class="step-number">2</div>
        <div class="step-title">Fill in New Payment Details</div>
        <div class="step-description">
            <p>The total amount will be pre-filled based on the previous payment record.</p>
            <p>Enter the new payment amount. The pending amount will be automatically calculated.</p>
            <p>Complete all other required fields as you did for the first payment.</p>
            <p>If there are any changes to the total amount (e.g., fee increase), you can update it accordingly.</p>
        </div>
    </div>
    
    <div class="guide-step">
        <div class="step-number">3</div>
        <div class="step-title">Submit Additional Payment</div>
        <div class="step-description">
            <p>Click the "Submit Payment Details" button to record the additional payment.</p>
            <p>The system will update the lead's payment status based on the new payment.</p>
        </div>
    </div>
    
    <h2 class="subsection-title">Payment Verification Process</h2>
    
    <div class="guide-step">
        <div class="step-number">1</div>
        <div class="step-title">Access Payment Verification Page</div>
        <div class="step-description">
            <p>Navigate to the "Verify Payments" section from the navigation menu.</p>
            <p>You will see a list of all payments pending verification.</p>
        </div>
    </div>
    
    <div class="guide-step">
        <div class="step-number">2</div>
        <div class="step-title">Review Payment Details</div>
        <div class="step-description">
            <p>Click on a payment to view its details, including the payment screenshot and transaction information.</p>
            <p>Verify that the payment information matches with your financial records.</p>
        </div>
    </div>
    
    <div class="guide-step">
        <div class="step-number">3</div>
        <div class="step-title">Verify or Reject Payment</div>
        <div class="step-description">
            <p>If the payment details are correct, click the "Verify Payment" button.</p>
            <p>If there are issues with the payment, click the "Reject Payment" button and provide a reason for rejection.</p>
            <p>The lead's payment status will be updated based on your action.</p>
        </div>
    </div>
    
    <h2 class="subsection-title">Managing Payments</h2>
    
    <div class="guide-step">
        <div class="step-number">1</div>
        <div class="step-title">View All Payments</div>
        <div class="step-description">
            <p>Navigate to the "All Payments" section from the navigation menu.</p>
            <p>You can see a list of all payments with their status, amount, and associated lead.</p>
            <p>Use the search and filter options to find specific payments.</p>
        </div>
    </div>
    
    <div class="guide-step">
        <div class="step-number">2</div>
        <div class="step-title">Edit Payment Details</div>
        <div class="step-description">
            <p>From the payment details page, click the "Edit" button.</p>
            <p>Update the payment information as needed.</p>
            <p>Click "Update Payment" to save your changes.</p>
        </div>
    </div>
    
    <div class="guide-step">
        <div class="step-number">3</div>
        <div class="step-title">View Pending Payments</div>
        <div class="step-description">
            <p>Navigate to the "Pending Payments" section from the navigation menu.</p>
            <p>This shows all leads with outstanding payments.</p>
            <p>You can follow up with these leads to complete their payments.</p>
        </div>
    </div>
    
    <div class="important-note">
        <strong>Note:</strong> The lead's status will automatically update based on payment status:
        <ul>
            <li><strong>Payment Partial:</strong> When some payment has been made but there is still a pending amount.</li>
            <li><strong>Payment Completed:</strong> When the full amount has been paid.</li>
        </ul>
    </div>
</div>

@endsection
