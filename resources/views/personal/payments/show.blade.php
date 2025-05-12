@extends('main')

@section('title', 'Payment Details')

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

    .detail-row {
        display: flex;
        margin-bottom: 15px;
        border-bottom: 1px solid #eee;
        padding-bottom: 10px;
    }

    .detail-label {
        width: 200px;
        font-weight: 600;
        color: #333;
    }

    .detail-value {
        flex: 1;
        color: #555;
    }

    .status-badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 4px;
        font-weight: 500;
        text-transform: capitalize;
    }

    .status-pending {
        background-color: #ffc107;
        color: #000;
    }

    .status-verified {
        background-color: #28a745;
        color: #fff;
    }

    .status-rejected {
        background-color: #dc3545;
        color: #fff;
    }

    .screenshot-container {
        margin: 15px 0;
    }

    .screenshot-container img {
        max-width: 300px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }

    .btn {
        padding: 8px 15px;
        border-radius: 4px;
        font-weight: 500;
        text-decoration: none;
        cursor: pointer;
        border: none;
    }

    .btn-primary {
        background-color: #007bff;
        color: #fff;
    }

    .btn-success {
        background-color: #28a745;
        color: #fff;
    }

    .btn-danger {
        background-color: #dc3545;
        color: #fff;
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
        <h2 class="section-title" style="text-align: center;">Payment Details</h2>
        
        @if(session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
        @endif
        
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

    <!-- Payment Details Section -->
    <div class="payment-details-section">
        <h3 class="section-title">Payment Information</h3>
        
        <div class="detail-row">
            <div class="detail-label">Status:</div>
            <div class="detail-value">
                <span class="status-badge status-{{ $payment->status }}">{{ $payment->status }}</span>
            </div>
        </div>
        
        <div class="detail-row">
            <div class="detail-label">Payment Amount:</div>
            <div class="detail-value">₹{{ number_format($payment->payment_amount, 2) }}</div>
        </div>
        
        <div class="detail-row">
            <div class="detail-label">Pending Amount:</div>
            <div class="detail-value">₹{{ number_format($payment->pending_amount, 2) }}</div>
        </div>
        
        <div class="detail-row">
            <div class="detail-label">Payment Mode:</div>
            <div class="detail-value">{{ ucfirst(str_replace('_', ' ', $payment->payment_mode)) }}</div>
        </div>
        
        @if($payment->utr_no)
        <div class="detail-row">
            <div class="detail-label">UTR/Transaction Number:</div>
            <div class="detail-value">{{ $payment->utr_no }}</div>
        </div>
        @endif
        
        @if($payment->reference_number)
        <div class="detail-row">
            <div class="detail-label">Reference Number:</div>
            <div class="detail-value">{{ $payment->reference_number }}</div>
        </div>
        @endif
        
        <div class="detail-row">
            <div class="detail-label">Start Year:</div>
            <div class="detail-value">{{ $payment->start_year ?? 'N/A' }}</div>
        </div>
        
        <div class="detail-row">
            <div class="detail-label">Duration:</div>
            <div class="detail-value">{{ $payment->duration ? $payment->duration . ' ' . Str::plural('Year', $payment->duration) : 'N/A' }}</div>
        </div>
        
        <div class="detail-row">
            <div class="detail-label">Session Duration:</div>
            <div class="detail-value">{{ $payment->session_duration }}</div>
        </div>
        
        <div class="detail-row">
            <div class="detail-label">Session Type:</div>
            <div class="detail-value">{{ ucfirst(str_replace('_', ' ', $payment->session)) }}</div>
        </div>
        
        <div class="detail-row">
            <div class="detail-label">Fee Type:</div>
            <div class="detail-value">{{ ucfirst(str_replace('_', ' ', $payment->fee_type ?? 'Not specified')) }}</div>
        </div>
        
        <div class="detail-row">
            <div class="detail-label">Payment Date:</div>
            <div class="detail-value">{{ $payment->payment_date ? $payment->payment_date->format('d M Y') : 'N/A' }}</div>
        </div>
        
        <div class="detail-row">
            <div class="detail-label">Created By:</div>
            <div class="detail-value">{{ $payment->agent ? $payment->agent->name : 'N/A' }}</div>
        </div>
        
        <div class="detail-row">
            <div class="detail-label">Created At:</div>
            <div class="detail-value">{{ $payment->created_at->format('d M Y, h:i A') }}</div>
        </div>
        
        @if($payment->verified_by)
        <div class="detail-row">
            <div class="detail-label">Verified By:</div>
            <div class="detail-value">{{ $payment->verifiedBy ? $payment->verifiedBy->name : 'N/A' }}</div>
        </div>
        
        <div class="detail-row">
            <div class="detail-label">Verified At:</div>
            <div class="detail-value">{{ $payment->verified_at ? $payment->verified_at->format('d M Y, h:i A') : 'N/A' }}</div>
        </div>
        @endif
        
        @if($payment->notes)
        <div class="detail-row">
            <div class="detail-label">Notes:</div>
            <div class="detail-value">{{ $payment->notes }}</div>
        </div>
        @endif
        
        @if($payment->payment_screenshot)
        <div class="screenshot-container">
            <h4>Payment Screenshot:</h4>
            <img src="{{ asset('storage/' . $payment->payment_screenshot) }}" alt="Payment Screenshot">
        </div>
        @endif
        
        @if($payment->loan_amount)
        <h3 class="section-title mt-4">Loan Information</h3>
        
        <div class="detail-row">
            <div class="detail-label">Loan Amount:</div>
            <div class="detail-value">₹{{ number_format($payment->loan_amount, 2) }}</div>
        </div>
        
        @if($payment->bank)
        <div class="detail-row">
            <div class="detail-label">Bank:</div>
            <div class="detail-value">{{ $payment->bank }}</div>
        </div>
        @endif
        
        @if($payment->loan_details)
        <div class="detail-row">
            <div class="detail-label">Loan Details:</div>
            <div class="detail-value">{{ $payment->loan_details }}</div>
        </div>
        @endif
        @endif
        
        <div class="action-buttons">
            <a href="{{ route('payments.edit', $payment->id) }}" class="btn btn-primary">Edit Payment</a>
            
            @if($payment->status === 'pending' && auth()->user()->can('verify_payments'))
            <form action="{{ route('payment.confirm', $payment->id) }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-success">Verify Payment</button>
            </form>
            @endif
            
            <a href="{{ url('/i-admin/leads/view/' . $payment->lead_id) }}" class="btn btn-primary">Back to Lead</a>
        </div>
    </div>
</div>

@endsection
