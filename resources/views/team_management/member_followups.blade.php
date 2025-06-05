@extends('main')

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
                    <h3 class="card-title">Followups for {{ $teamMember->emp_name }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.team.management') }}" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Team
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($followups->count() > 0)
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
                                    @foreach($followups as $followup)
                                        <tr>
                                            <td>{{ $loop->iteration + (($followups->currentPage() - 1) * $followups->perPage()) }}</td>
                                            <td>
                                                @if($followup->lead)
                                                    {{ $followup->lead->first_name }} {{ $followup->lead->last_name }}
                                                    <br><small class="text-muted">{{ $followup->lead->email }}</small>
                                                @else
                                                    <span class="text-muted">Lead not found</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $followup->follow_up_time ? \Carbon\Carbon::parse($followup->follow_up_time)->format('M d, Y h:i A') : 'N/A' }}
                                                <br>
                                                <small class="text-muted">
                                                    {{ $followup->created_at->diffForHumans() }}
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
                                                    {{ ucfirst($followup->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($followup->notes)
                                                    {{ Str::limit($followup->notes, 50) }}
                                                    @if(strlen($followup->notes) > 50)
                                                        <a href="#" class="view-notes" data-notes="{{ $followup->notes }}">
                                                            View More
                                                        </a>
                                                    @endif
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
                            {{ $followups->links() }}
                        </div>
                    @else
                        <div class="alert alert-info">
                            No followups found for this team member.
                        </div>
                    @endif
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
        // Handle view notes click
        $(document).on('click', '.view-notes', function(e) {
            e.preventDefault();
            var notes = $(this).data('notes');
            $('#notesContent').text(notes);
            $('#notesModal').modal('show');
        });
    });
</script>
@endpush

@endsection
