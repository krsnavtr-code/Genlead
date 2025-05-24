@extends('main')

@section('title', 'Upcoming Follow-Ups')

@section('content')
<div class="content-wrapper">
    <!-- Header -->
    <div class="content-header sty-one">
        <h1>Upcoming Follow-Ups</h1>
        <ol class="breadcrumb">
            <li><a href="">Leads</a></li>
        </ol>
    </div>

    <!-- Main Content -->
    <div class="content">
        <div class="card">
            <div class="card-body">
                <h4 class="text-black mb-4">All Upcoming Follow-Ups</h4>
                <p class="text-muted mb-4">Showing all follow-ups scheduled after tomorrow ({{ \Carbon\Carbon::tomorrow()->addDay()->format('d M Y') }} onwards)</p>

                @php
                    $tomorrow = \Carbon\Carbon::tomorrow()->addDay();
                @endphp
                
                @forelse($followUps as $followUp)
                    @if($followUp->follow_up_time >= $tomorrow)
                    <div class="card mb-3 shadow-sm border rounded p-3">
                        <div class="d-flex justify-content-between flex-wrap">
                            <div>
                                <h5 class="mb-2">{{ $followUp->lead->first_name }} {{ $followUp->lead->last_name }}</h5>
                                <p class="mb-1"><strong>Agent:</strong> {{ $followUp->agent->emp_name ?? 'N/A' }}</p>
                                <p class="mb-1"><strong>Follow-Up Time:</strong> {{ \Carbon\Carbon::parse($followUp->follow_up_time)->format('d M Y, h:i A') }}</p>
                                <p class="mb-2"><strong>Comments:</strong> {{ $followUp->comments }}</p>
                            </div>
                            <div class="d-flex align-items-start justify-content-end">
                                <a href="{{ url('/i-admin/leads/view/'.$followUp->lead_id) }}" class="btn btn-primary btn-sm mt-2">View Lead</a>
                            </div>
                        </div>
                    </div>
                    @endif
                @empty
                    <div class="alert alert-info text-center">
                        No follow-ups scheduled for upcoming days.
                    </div>
                @endforelse

                <!-- Back Button -->
                <div class="mt-4">
                    <a href="{{ url('/i-admin/show-leads') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Back to Leads
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
</style>
@endpush

@push('scripts')
<script>
    // Auto-refresh the page every 15 minutes to check for new follow-ups
    setTimeout(function() {
        window.location.reload();
    }, 15 * 60 * 1000); // 15 minutes
</script>
@endpush
@endsection
