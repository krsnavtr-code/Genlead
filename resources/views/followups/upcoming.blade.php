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

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="bg-light">
                            <tr>
                                <th>Lead Name</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Agent</th>
                                <th>Comments</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($followUps as $followUp)
                                <tr>
                                    <td>
                                        {{ $followUp->lead->first_name }} {{ $followUp->lead->last_name }}
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($followUp->follow_up_time)->format('d M Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($followUp->follow_up_time)->format('h:i A') }}</td>
                                    <td>{{ $followUp->agent->emp_name ?? 'N/A' }}</td>
                                    <td>{{ Str::limit($followUp->comments, 50) }}</td>
                                    <td>
                                        <a href="{{ url('/i-admin/leads/view/'.$followUp->lead_id) }}" class="btn btn-sm btn-primary" title="View Lead">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="fas fa-calendar-check fa-3x mb-3"></i>
                                        <p class="mb-0">No upcoming follow-ups found.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

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
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }
    .table td {
        vertical-align: middle;
    }
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
