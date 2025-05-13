@extends('main')

@section('title', 'Payment List')

@section('content')

@php
    $emp_job_role = session()->get('emp_job_role');
@endphp

<style>
    .payment-container {
        width: 100%;
        max-width: 1200px;
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
        text-align: center;
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

    .action-buttons {
        display: flex;
        gap: 5px;
    }

    .btn {
        padding: 5px 10px;
        border-radius: 4px;
        font-weight: 500;
        text-decoration: none;
        cursor: pointer;
        border: none;
        font-size: 12px;
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

    .payment-table {
        width: 100%;
        border-collapse: collapse;
    }

    .payment-table th,
    .payment-table td {
        padding: 10px;
        border: 1px solid #ddd;
        text-align: left;
    }

    .payment-table th {
        background-color: #f2f2f2;
        font-weight: 600;
    }

    .payment-table tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .payment-table tr:hover {
        background-color: #f5f5f5;
    }

    .pagination {
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }

    .pagination .page-item {
        margin: 0 5px;
    }

    .pagination .page-link {
        padding: 5px 10px;
        border: 1px solid #ddd;
        color: #007bff;
        text-decoration: none;
        border-radius: 4px;
    }

    .pagination .page-item.active .page-link {
        background-color: #007bff;
        color: #fff;
        border-color: #007bff;
    }
</style>

@include('navbar')
    

<div class="payment-container">
    <h2 class="section-title">Payment List</h2>
    
    @if(session('success'))
    <div class="alert alert-success" role="alert">
        {{ session('success') }}
    </div>
    @endif
    
    @if($payments->isEmpty())
        <p class="text-center">No payments found.</p>
    @else
        <div class="table-responsive">
            <table class="payment-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Lead</th>
                        <th>Amount</th>
                        <th>Mode</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $payment)
                    <tr>
                        <td>{{ $payment->id }}</td>
                        <td>
                            @if($payment->lead)
                                {{ $payment->lead->first_name }} {{ $payment->lead->last_name }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td>₹{{ number_format($payment->payment_amount, 2) }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_mode)) }}</td>
                        <td>{{ $payment->payment_date ? $payment->payment_date->format('d M Y') : $payment->created_at->format('d M Y') }}</td>
                        <td>
                            <span class="status-badge status-{{ $payment->status }}">
                                {{ $payment->status }}
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('payments.show', $payment->id) }}" class="btn btn-primary">View</a>
                                
                                @if($payment->status === 'pending')
                                    <a href="{{ route('payments.edit', $payment->id) }}" class="btn btn-primary">Edit</a>
                                    
                                    @if(auth()->check() && auth()->user()->can('verify_payments'))
                                    <form action="{{ route('payment.confirm', $payment->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-success" onclick="return confirm('Are you sure you want to verify this payment?')">Verify</button>
                                    </form>
                                    @endif
                                @endif
                                
                                @if($payment->lead)
                                <a href="{{ url('/i-admin/leads/view/' . $payment->lead_id) }}" class="btn btn-primary">Lead</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="pagination">
            {{ $payments->links() }}
        </div>
    @endif
</div>

@endsection
