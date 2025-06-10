@extends('main')

@section('title', 'My Earnings')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">My Earnings</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">My Earnings</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <!-- Info boxes -->
            <div class="row">
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="info-box">
                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-wallet"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Earnings</span>
                            <span class="info-box-number">
                                ₹{{ number_format($totalEarnings, 2) }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="info-box">
                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-check-circle"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Paid</span>
                            <span class="info-box-number">₹{{ number_format($totalPaid, 2) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="info-box">
                        <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-clock"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Pending Payout</span>
                            <span class="info-box-number">₹{{ number_format($totalPending, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Earnings History</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    @if(auth()->user()->isAdmin())
                                    <th>Agent</th>
                                    @endif
                                    <th>Type</th>
                                    <th>Description</th>
                                    <th class="text-right">Amount</th>
                                    <th>Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($earnings as $earning)
                                <tr>
                                    <td>{{ $earning->earned_date->format('M d, Y') }}</td>
                                    @if(auth()->user()->isAdmin())
                                    <td>{{ $earning->agent->emp_name ?? 'N/A' }}</td>
                                    @endif
                                    <td>
                                        <span class="badge bg-{{ $earning->type === 'commission' ? 'primary' : 'info' }}">
                                            {{ ucfirst($earning->type) }}
                                        </span>
                                    </td>
                                    <td>{{ $earning->description }}</td>
                                    <td class="text-right">₹{{ number_format($earning->amount, 2) }}</td>
                                    <td>
                                        @if($earning->is_paid)
                                            <span class="badge bg-success">Paid on {{ $earning->paid_date->format('M d, Y') }}</span>
                                        @else
                                            <span class="badge bg-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.referr-agent-earning.show', $earning->id) }}" class="btn btn-sm btn-info" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(!$earning->is_paid && (auth()->user()->isAdmin() || auth()->id() === $earning->agent_id))
                                        <form action="{{ route('admin.referr-agent-earning.payout', $earning->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to mark this as paid?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-success" title="Mark as Paid">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="{{ auth()->user()->isAdmin() ? 7 : 6 }}" class="text-center">No earnings found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($earnings->hasPages())
                <div class="card-footer clearfix">
                    {{ $earnings->links() }}
                </div>
                @endif
            </div>
            
            @if(auth()->user()->isAdmin() && $totalPending > 0)
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Payout Tools</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.referr-agent-earning.payout-all') }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to mark all pending earnings as paid?');">
                        @csrf
                        <div class="form-group">
                            <label>Select Agent</label>
                            <select name="agent_id" class="form-control select2" required>
                                @foreach(\App\Models\Employee::where('emp_job_role', 7)->get() as $agent)
                                    <option value="{{ $agent->id }}">
                                        {{ $agent->emp_name }} (ID: {{ $agent->id }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check-circle mr-1"></i> Mark All as Paid
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </section>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script>
    $(function () {
        $('.select2').select2({
            theme: 'bootstrap4',
            placeholder: 'Select an agent'
        });
    });
</script>
@endpush
