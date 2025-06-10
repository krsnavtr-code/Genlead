@extends('main')

@section('title', 'Earning Details')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Earning Details</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.referr-agent-earning.index') }}">My Earnings</a></li>
                        <li class="breadcrumb-item active">Earning Details</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Earning Information</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 30%">Earning ID</th>
                                    <td>#{{ $earning->id }}</td>
                                </tr>
                                <tr>
                                    <th>Agent</th>
                                    <td>{{ $earning->agent->emp_name }}</td>
                                </tr>
                                <tr>
                                    <th>Type</th>
                                    <td>
                                        <span class="badge bg-{{ $earning->type === 'commission' ? 'primary' : 'info' }}">
                                            {{ ucfirst($earning->type) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Amount</th>
                                    <td>₹{{ number_format($earning->amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Earned Date</th>
                                    <td>{{ $earning->earned_date->format('M d, Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @if($earning->is_paid)
                                            <span class="badge bg-success">Paid on {{ $earning->paid_date->format('M d, Y') }}</span>
                                        @else
                                            <span class="badge bg-warning">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                                @if($earning->reference_type && $earning->reference_id)
                                <tr>
                                    <th>Reference</th>
                                    <td>
                                        {{ ucfirst($earning->reference_type) }} #{{ $earning->reference_id }}
                                        @if($earning->reference)
                                            <a href="#" class="btn btn-xs btn-info ml-2">
                                                <i class="fas fa-external-link-alt"></i> View
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Description</h3>
                        </div>
                        <div class="card-body">
                            <p>{{ $earning->description ?? 'No description provided.' }}</p>
                        </div>
                    </div>
                    
                    @if(!$earning->is_paid && (auth()->user()->isAdmin() || auth()->id() === $earning->agent_id))
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Payment Actions</h3>
                        </div>
                        <div class="card-body">
                            <a href="{{ route('admin.referr-agent-earning.index') }}" class="btn btn-default">Back to List</a>
                            <form action="{{ route('admin.referr-agent-earning.payout', $earning->id) }}" method="POST" class="mb-3">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success btn-block" onclick="return confirm('Mark this payment as paid?')">
                                    <i class="fas fa-check-circle mr-1"></i> Mark as Paid
                                </button>
                            </form>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
