@extends('main')

@section('title', 'Pending Payments')

@section('content')

<style>
    .pending-payment-container {
        max-width: 1000px;
        margin: -71px auto;
        padding: 20px;
        background: #ffffff;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .section-title {
        font-size: 24px;
        font-weight: 600;
        color: #333;
        margin-bottom: 20px;
        text-align: center;
    }

    .lead-item {
        border-bottom: 1px solid #ddd;
        padding: 15px 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .lead-info {
        font-size: 16px;
        color: #555;
    }

    .lead-info strong {
        color: #333;
        font-weight: 600;
    }

    .pending-amount {
        font-size: 16px;
        font-weight: 600;
        color: #e74c3c;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
    }

    .btn {
        /* background-color: #007bff; */
        color: #fff;
        padding: 8px 12px;
        border: none;
        border-radius: 4px;
        text-decoration: none;
        font-weight: 500;
        cursor: pointer;
    }

    .btn-follow-up {
        background-color: #28a745;
    }

    .btn-pending-payment {
        background-color: #ff9800;
    }

    .payment-history {
        display: none; /* Hidden by default */
        margin-top: 15px;
        padding: 10px;
        background: #f9f9f9;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

     /* Horizontal Navbar Styles */
     .horizontal-navbar {
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

<div class="horizontal-navbar">
    <a href="{{ url('/i-admin/show-leads') }}">Manage Leads</a>
    <a href="{{ url('/admin/activities/create') }}">Add Activities</a>
    <a href="{{ url('/admin/activities') }}">Manage Activities</a>
    <a href="{{ url('/admin/tasks/create') }}">Create/Add Tasks</a>
    <a href="{{ url('/admin/tasks') }}">Manage Tasks</a>
    <a href="{{ url('/i-admin/pending') }}">Pending Payment</a>
</div>

<!-- Content Header (Page header) -->
<div class="content-header sty-one d-flex justify-content-between align-items-center">
    <div>
        <h1>Pending Payment</h1>
    </div>
    <div>
        <a href="{{ url('/i-admin/leads/add-lead') }}" 
        class="btn btn-danger btn-sm" 
        style=" color: white; padding: 5px 20px; font-size: 16px; font-weight: bold; border-radius: 4px; text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">
     
        <!-- + Mark -->
        <span style="font-size: 20px; font-weight: bold; margin-right: 8px;">&#43;</span>
     
        <!-- Button Text -->
        Add Lead
     </a>
    </div>
</div>

<div class="pending-payment-container">
    <h2 class="section-title">Pending Payments</h2>

    @foreach($leads as $lead)
        <div class="lead-item">
            <div class="lead-info">
                <p><strong>Lead Name:</strong> {{ $lead->first_name }} {{ $lead->last_name }}</p>
                <p><strong>Email:</strong> {{ $lead->email }}</p>
                <p><strong>Phone:</strong> {{ $lead->phone }}</p>
                <p><strong>Pending Amount:</strong> <span class="pending-amount">₹{{ number_format($lead->pending_amount, 2) }}</span></p>
            </div>
            <div class="action-buttons">
                <a href="{{ url('/i-admin/leads/view/'.$lead->id) }}" class="btn btn-follow-up">Follow Up</a>
                <button class="btn btn-pending-payment" onclick="togglePaymentHistory({{ $lead->id }})">Paid Payment</button>
            </div>

            <!-- Payment History Section -->
            <div id="payment-history-{{ $lead->id }}" class="payment-history">
                <h4>Payment History</h4>
                @foreach($lead->payments as $payment)
                    <p>Amount Paid: ₹{{ number_format($payment->payment_amount, 2) }} on {{ $payment->created_at->format('d M Y') }}</p>
                @endforeach
            </div>
        </div>
    @endforeach
</div>

<script>
    // Function to toggle the payment history section
    function togglePaymentHistory(leadId) {
        const historySection = document.getElementById(`payment-history-${leadId}`);
        historySection.style.display = historySection.style.display === 'none' ? 'block' : 'none';
    }
</script>

@endsection
