@extends('main')

@section('title', 'TL Overdue Follow-ups')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">{{ $title }}</h3>

                    <div class="card-tools">
                        <form method="GET" action="{{ route('admin.team.overdue.followups') }}" class="form-inline">

                            {{-- Agent Filter --}}
                            <div class="input-group input-group-sm mr-2">
                                <select name="agent" class="form-control" onchange="this.form.submit()">
                                    <option value="">All Agents</option>
                                    @foreach($teamMembers as $member)
                                    <option value="{{ $member->id }}" {{ request('agent') == $member->id ? 'selected' : '' }}>
                                        {{ $member->emp_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Status Filter --}}
                            <div class="input-group input-group-sm mr-2">
                                <select name="status" class="form-control lead-form-select" onchange="this.form.submit()">
                                    <option value="">All Statuses</option>
                                    <option value="other" {{ request('status') === 'other' ? 'selected' : '' }}>
                                        Other (No Status)
                                    </option>
                                    @foreach(\App\Helpers\SiteHelper::getLeadStatus() as $type => $categories)
                                    <optgroup label="{{ $type }}">
                                        @foreach($categories as $category)
                                        @foreach($category['subcategories'] as $subcategory)
                                        <option value="{{ $subcategory['code'] }}" {{ request('status') == $subcategory['code'] ? 'selected' : '' }}>
                                            {{ $category['category'] }} - {{ $subcategory['name'] }}
                                        </option>
                                        @endforeach
                                        @endforeach
                                    </optgroup>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Clear Filters Button --}}
                            @if(request()->has('agent') || request()->has('status'))
                            <a href="{{ route('admin.team.overdue.followups') }}" class="btn btn-sm btn-secondary">
                                <i class="fas fa-times"></i> Clear Filters
                            </a>
                            @endif

                        </form>
                    </div>
                </div>


                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    @if($overdueFollowups->count() > 0)
                    <table class="table table-sm table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Lead</th>
                                <th>Agent</th>
                                <th>Follow-up Time</th>
                                <th>Overdue By</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($overdueFollowups as $index => $followup)
                            @php
                            $overdueHours = now()->diffInHours($followup->follow_up_time);
                            $overdueDays = now()->diffInDays($followup->follow_up_time);
                            $isSevere = $overdueDays > 1;
                            @endphp
                            <tr class="{{ $isSevere ? 'table-danger' : 'table-warning' }}">
                                <td>{{ $overdueFollowups->firstItem() + $index }}</td>
                                <td>
                                    @if($followup->lead)
                                    <a href="{{ url('i-admin/leads/view/' . $followup->lead_id) }}">
                                        {{ $followup->lead->first_name ?? '' }} {{ $followup->lead->last_name ?? '' }}
                                    </a>
                                    @else
                                    Lead #{{ $followup->lead_id }} (Not Found)
                                    @endif
                                </td>
                                <td>{{ $followup->agent ? $followup->agent->emp_name : 'N/A' }}</td>
                                <td>{{ $followup->follow_up_time->format('M d, Y h:i A') }}</td>
                                <td>
                                    @if($overdueHours < 0)
                                        @php
                                        $hoursRemaining=abs($overdueHours);
                                        $daysRemaining=floor($hoursRemaining / 24);
                                        $hoursRemaining=$hoursRemaining % 24;
                                        @endphp
                                        @if($daysRemaining> 0)
                                        In {{ $daysRemaining }} {{ Str::plural('day', $daysRemaining) }}
                                        @if($hoursRemaining > 0)
                                        and {{ $hoursRemaining }} {{ Str::plural('hour', $hoursRemaining) }}
                                        @endif
                                        @else
                                        In {{ $hoursRemaining }} {{ Str::plural('hour', $hoursRemaining) }}
                                        @endif
                                        @else
                                        @if($overdueDays > 0)
                                        {{ $overdueDays }} {{ Str::plural('day', $overdueDays) }} ago
                                        @else
                                        {{ $overdueHours }} {{ Str::plural('hour', $overdueHours) }} ago
                                        @endif
                                        @endif
                                </td>
                                <td>
                                    @if(!empty($followup->lead->status))
                                    <span class="status-badge status-badge-{{ $followup->lead->status_id }}">
                                        {{ ucfirst($followup->lead->status) }}
                                    </span>
                                    <p>{{ $followup->created_at->format('M d, Y h:i A') }}</p>
                                    @else
                                    <span class="badge badge-secondary">Not Talk</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#followupModal{{ $followup->lead_id }}">
                                        <i class="fas fa-comments"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <div class="alert alert-info m-3">
                        No overdue follow-ups found.
                    </div>
                    @endif
                </div>
                <!-- /.card-body -->
                <div class="card-footer clearfix">
                    {{ $overdueFollowups->withQueryString()->links() }}
                </div>
            </div>
            <!-- /.card -->
            @foreach($overdueFollowups as $followup)
            <!-- Modal for Lead {{ $followup->lead_id }} -->
            <div class="modal fade" id="followupModal{{ $followup->lead_id }}" tabindex="-1" role="dialog" aria-labelledby="followupModalLabel{{ $followup->lead_id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="followupModalLabel{{ $followup->lead_id }}">Follow-up History for {{ $followup->lead->first_name ?? 'Lead' }} {{ $followup->lead->last_name ?? '' }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            @if($followup->lead->followUps->count() > 0)
                                <div class="timeline">
                                    @foreach($followup->lead->followUps->sortBy('follow_up_time') as $followUp)
                                        <div class="time-label">
                                            <span class="bg-{{ $followUp->status->color ?? 'info' }}">
                                                {{ $followUp->follow_up_time->format('d M. Y') }}
                                            </span>
                                        </div>
                                        <div>
                                            <i class="fas fa-comments bg-blue"></i>
                                            <div class="timeline-item">
                                                <span class="time">
                                                    <i class="far fa-clock"></i> 
                                                    {{ $followUp->follow_up_time->format('h:i A') }}
                                                </span>
                                                <h3 class="timeline-header">
                                                    @if($followUp->createdBy)
                                                        {{ $followUp->createdBy->emp_name }}
                                                    @else
                                                        System
                                                    @endif
                                                    <small>updated the status to </small>
                                                    <span class="badge bg-{{ $followUp->status->color ?? 'secondary' }}">
                                                        {{ $followUp->status->name ?? 'N/A' }}
                                                    </span>
                                                </h3>
                                                @if($followUp->comments)
                                                    <div class="timeline-body">
                                                        {{ $followUp->comments }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                    <div>
                                        <i class="fas fa-clock bg-gray"></i>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    No follow-up history found for this lead.
                                </div>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Auto-refresh the page every 5 minutes to check for new overdue follow-ups
        setInterval(function() {
            window.location.reload();
        }, 5 * 60 * 1000);
    });
</script>
@endpush