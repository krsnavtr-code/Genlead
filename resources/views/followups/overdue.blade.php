@extends('main')

@section('title', 'Overdue Follow-Ups')

@section('content')
<div class="content-wrapper">
    <!-- Header -->
    <div class="content-header sty-one">
        <h1>Overdue Follow-Ups</h1>
        <ol class="breadcrumb">
            <li><a href="">Leads</a></li>
        </ol>
    </div>

    <!-- Main Content -->
    <div class="content">
        <div class="card">
            <div class="card-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    These follow-ups are past their scheduled time and require immediate attention.
                </div>

                @forelse($followUps as $followUp)
                    @php
                        $isOverdue = \Carbon\Carbon::parse($followUp->follow_up_time)->diffInHours(now(), false) > 0;
                        $overdueHours = \Carbon\Carbon::parse($followUp->follow_up_time)->diffInHours(now());
                    @endphp
                    <div class="card mb-3 shadow-sm border-left-0 border-{{ $isOverdue ? 'danger' : 'warning' }} border-left-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between flex-wrap">
                                <div>
                                    <h5 class="mb-2">
                                        {{ $followUp->lead->first_name }} {{ $followUp->lead->last_name }}
                                        <span class="badge bg-{{ $isOverdue ? 'danger' : 'warning' }} ms-2">
                                            {{ $isOverdue ? 'Overdue by ' . $overdueHours . ' hours' : 'Due Soon' }}
                                        </span>
                                    </h5>
                                    <p class="mb-1"><strong>Scheduled:</strong> {{ \Carbon\Carbon::parse($followUp->follow_up_time)->format('d M Y, h:i A') }}</p>
                                    <p class="mb-1"><strong>Agent:</strong> {{ $followUp->agent->emp_name ?? 'N/A' }}</p>
                                    <p class="mb-2"><strong>Comments:</strong> {{ $followUp->comments }}</p>
                                </div>
                                <div class="d-flex align-items-start">
                                    <a href="{{ url('/i-admin/leads/view/'.$followUp->lead_id) }}" class="btn btn-primary btn-sm mt-2 me-2">
                                        View Lead
                                    </a>
                                    <a href="{{ url('/i-admin/leads/view/'.$followUp->lead_id) }}" class="btn btn-success btn-sm mt-2">
                                        <i class="fas fa-check-circle"></i> Mark as Contacted
                                    </a>
                                </div>
                            </div>
                            @if($isOverdue)
                                <div class="mt-2 text-danger">
                                    <i class="fas fa-exclamation-circle"></i> This follow-up is {{ $overdueHours }} hours overdue
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="alert alert-success text-center">
                        <i class="fas fa-check-circle fa-2x mb-3"></i>
                        <h4>All caught up!</h4>
                        <p class="mb-0">You don't have any overdue follow-ups at the moment.</p>
                    </div>
                @endforelse

                <!-- Back Button -->
                <a href="{{ url('/i-admin/show-leads') }}" class="btn btn-secondary mt-4">Back to Leads</a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto-refresh the page every 5 minutes to check for new overdue follow-ups
    setTimeout(function() {
        window.location.reload();
    }, 5 * 60 * 1000); // 5 minutes
</script>
@endpush
@endsection
