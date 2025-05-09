@extends('main')

@section('title', 'Pending Payments')

@section('content')

<style>
    .pending-payment-container {
        margin: 15px auto;
        max-width: 1000px;
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

<!-- Content Header (Page header) -->
<div class="content-header sty-one d-flex justify-content-between align-items-center" style="float: right;">
    <!-- <div>
        <h1>Pending Payment</h1>
    </div> -->
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
