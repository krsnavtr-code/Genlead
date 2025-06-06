@extends('main')

@push('styles')
<style>
    .badge {
        font-size: 85%;
        padding: 0.35em 0.65em;
    }
    .table th {
        white-space: nowrap;
    }
    .nav-tabs .nav-link {
        font-weight: 500;
    }
    .view-notes {
        cursor: pointer;
    }
</style>
@endpush

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $title ?? 'Team Member Followups' }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.team.management') }}">Team Management</a></li>
                        <li class="breadcrumb-item active">Followups - {{ $teamMember->emp_name }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $teamMember->emp_name }} Followups details</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.team.management') }}" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Team
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" id="followupTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="today-tab" data-toggle="tab" href="#today" role="tab" aria-controls="today" aria-selected="true">
                                Today Follow-ups <span class="badge badge-primary">{{ $todayFollowups->count() }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="upcoming-tab" data-toggle="tab" href="#upcoming" role="tab" aria-controls="upcoming" aria-selected="true">
                                Upcoming Follow-ups <span class="badge badge-primary">{{ $upcomingFollowups->count() }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="past-tab" data-toggle="tab" href="#past" role="tab" aria-controls="past" aria-selected="false">
                                Past Follow-ups <span class="badge badge-secondary">{{ $pastFollowups->total() }}</span>
                            </a>
                        </li>
                    </ul>
                    
                    <div class="tab-content mt-3" id="followupTabsContent">
                        <!-- Today Follow-ups Tab -->
                        <div class="tab-pane fade show active" id="today" role="tabpanel" aria-labelledby="today-tab">
                            @if($todayFollowups->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Lead</th>
                                                <th>Follow-up Time</th>
                                                <th>Status</th>
                                                <th>Notes</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($todayFollowups as $index => $followup)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>
                                                        @if($followup->lead)
                                                            <a href="{{ route('leads.view', $followup->lead_id) }}">
                                                                {{ $followup->lead->first_name ?? '' }} {{ $followup->lead->last_name ?? '' }}
                                                            </a>
                                                            @if($followup->lead->email)
                                                                <br><small class="text-muted">{{ $followup->lead->email }}</small>
                                                            @endif
                                                        @else
                                                            <span class="text-muted">Lead not found</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ $followup->followup_time ? \Carbon\Carbon::parse($followup->followup_time)->format('M d, Y h:i A') : 'Not set' }}
                                                    </td>
                                                    <td>
                                                        <span class="badge {{ $followup->status === 'completed' ? 'badge-success' : 'badge-warning' }}">
                                                            {{ ucfirst($followup->status) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @if($followup->notes)
                                                            <button type="button" class="btn btn-sm btn-outline-primary view-notes" data-notes="{{ $followup->notes }}">
                                                                View Notes
                                                            </button>
                                                        @else
                                                            <span class="text-muted">No notes</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    No follow-ups scheduled for today.
                                </div>
                            @endif
                        </div>

                        <!-- Upcoming Follow-ups Tab -->
                        <div class="tab-pane fade show active" id="upcoming" role="tabpanel" aria-labelledby="upcoming-tab">
                            @if($upcomingFollowups->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Lead</th>
                                                <th>Follow-up Time</th>
                                                <th>Status</th>
                                                <th>Notes</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($upcomingFollowups as $index => $followup)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>
                                                        @if($followup->lead)
                                                            <a href="{{ route('leads.view', $followup->lead_id) }}">
                                                                {{ $followup->lead->first_name ?? '' }} {{ $followup->lead->last_name ?? '' }}
                                                            </a>
                                                            @if($followup->lead->email)
                                                                <br><small class="text-muted">{{ $followup->lead->email }}</small>
                                                            @endif
                                                        @else
                                                            <span class="text-muted">Lead not found</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ $followup->follow_up_time->format('M d, Y h:i A') }}
                                                        <br>
                                                        <small class="text-muted">
                                                            {{ $followup->follow_up_time->diffForHumans() }}
                                                        </small>
                                                    </td>
                                                    <td>
                                                        @php
                                                            $statusClass = [
                                                                'pending' => 'warning',
                                                                'completed' => 'success',
                                                                'cancelled' => 'danger',
                                                                'rescheduled' => 'info'
                                                            ][$followup->status] ?? 'secondary';
                                                        @endphp
                                                        <span class="badge badge-{{ $statusClass }}">
                                                            {{ ucfirst($followup->status ?? 'pending') }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @if($followup->comments)
                                                            <button class="btn btn-sm btn-info view-notes" data-notes="{{ $followup->comments }}">
                                                                <i class="fas fa-eye"></i> View Notes
                                                            </button>
                                                        @else
                                                            <span class="text-muted">No notes</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info mt-3">
                                    No upcoming follow-ups found.
                                </div>
                            @endif
                        </div>

                        <!-- Past Follow-ups Tab -->
                        <div class="tab-pane fade" id="past" role="tabpanel" aria-labelledby="past-tab">
                            @if($pastFollowups->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Lead</th>
                                                <th>Follow-up Time</th>
                                                <th>Status</th>
                                                <th>Notes</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($pastFollowups as $followup)
                                                <tr>
                                                    <td>{{ $loop->iteration + (($pastFollowups->currentPage() - 1) * $pastFollowups->perPage()) }}</td>
                                                    <td>
                                                        @if($followup->lead)
                                                            <a href="{{ route('leads.view', $followup->lead_id) }}">
                                                                {{ $followup->lead->first_name ?? '' }} {{ $followup->lead->last_name ?? '' }}
                                                            </a>
                                                        @else
                                                            N/A
                                                        @endif
                                                    </td>
                                                    <td>{{ $followup->follow_up_time->format('M d, Y h:i A') }}</td>
                                                    <td>
                                                        <span class="badge badge-{{ $followup->status === 'completed' ? 'success' : 'secondary' }}">
                                                            {{ ucfirst($followup->status ?? 'pending') }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @if($followup->comments)
                                                            <button class="btn btn-sm btn-info view-notes" data-notes="{{ $followup->comments }}">
                                                                <i class="fas fa-eye"></i> View Notes
                                                            </button>
                                                        @else
                                                            <span class="text-muted">No notes</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-3">
                                    {{ $pastFollowups->links() }}
                                </div>
                            @else
                                <div class="alert alert-info">
                                    No past follow-ups found.
                                </div>
                            @endif
                        </div>
                        <!-- today follow tab -->
                        <!-- <div class="tab-pane fade" id="today" role="tabpanel" aria-labelledby="today-tab">

                            
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Notes Modal -->
<div class="modal fade" id="notesModal" tabindex="-1" role="dialog" aria-labelledby="notesModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="notesModalLabel">Follow-up Notes</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="notesContent">
                <!-- Notes will be inserted here by JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize tooltips
        $('[data-toggle="tooltip"]').tooltip();

        // View notes in modal
        $('.view-notes').on('click', function() {
            const notes = $(this).data('notes');
            $('#notesContent').text(notes || 'No notes available.');
            $('#notesModal').modal('show');
        });

        // Handle tab switching
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            localStorage.setItem('lastFollowupTab', $(e.target).attr('id'));
        });

        // Restore last active tab
        const lastTab = localStorage.getItem('lastFollowupTab');
        if (lastTab) {
            $(`#${lastTab}`).tab('show');
        }
    });
</script>
@endpush

@endsection
