@extends('main') <!-- Extend the main layout -->

@section('title', 'Manage Leads') <!-- Set the title for the page -->

@section('content') <!-- Content section -->

<style>
    /* Styling for table headers */
    th {
        font-size: 16px;
        color: #495057;
        font-family: sans-serif;
        font-weight: 100 !important;
    }
    td{
        font-size: 16px;
        color: #495057;
        font-family: sans-serif;
        font-weight: 100 !important;
    }
    .lead-card {
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        padding: 16px;
        display: flex;
        flex-direction: column;
        height: 100%;
        transition: transform 0.2s ease;
    }

    /* .lead-card:hover {
        transform: translateY(-5px);
    } */

    .lead-card h5 a {
        text-decoration: none;
        color: #212529;
        font-weight: 600;
    }

    .lead-card h5 a:hover {
        color: #0d6efd;
    }

    .lead-meta {
        font-size: 0.9rem;
        color: #555;
        margin-bottom: 6px;
    }

    .lead-actions {
        margin-top: auto;
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .lead-form-select {
        width: 100%;
        max-width: 500px;
        display: inline-block;
        padding: 8px;
        border-radius: 5px;
        border: 1px solid #ccc;
        font-size: 14px;
        font-weight: 500;
        color: #495057;
        text-transform: uppercase;
    }

    @media (max-width: 576px) {
        .lead-card {
            padding: 12px;
        }

        .lead-meta {
            font-size: 0.85rem;
        }
    }
</style>

<div class="content-wrapper" >

   <!-- Horizontal Navbar -->
    <div class="horizontal-navbar d-flex flex-wrap justify-content-around py-2 border-bottom mb-3">
        <a href="{{ url('/i-admin/show-leads') }}" class="btn m-1">Manage Leads</a>
        <a href="{{ url('/admin/activities/create') }}" class="btn m-1">Add Activities</a>
        <a href="{{ url('/admin/activities') }}" class="btn m-1">Manage Activities</a>
        <a href="{{ url('/admin/tasks/create') }}" class="btn m-1">Create/Add Tasks</a>
        <a href="{{ url('/admin/tasks') }}" class="btn m-1">Manage Tasks</a>
        <a href="{{ url('/i-admin/pending') }}" class="btn m-1">Pending Payment</a>
    </div>

    <!-- Content Header (Page header) -->
    <div class="container-fluid mb-2">
        <div class="d-flex flex-md-row align-items-center justify-content-between gap-3">
            <p class="h5 m-0 fw-semibold text-dark">Manage Leads</p>
            <a href="{{ url('/i-admin/leads/add-lead') }}" 
               class="btn btn-danger d-inline-flex align-items-center px-2  fs-6 fw-bold rounded-2 shadow-sm">
              <span class="fs-5">&#43;</span> Add Lead
            </a>
        </div>
    </div>


    <!-- Main content -->
    <div class="container-fluid" style="padding: 0px;">
        <div class="card shadow-sm mt-3">
            <div class="card-body" style="padding: 8px;">
                <!-- Filters and Search -->
                <form method="GET" action="{{ route('leads.index') }}" class="row g-3 mb-4" style="gap: 8px;">
                    <!-- Search -->
                    <div class="col-md-3 col-12 d-flex">
                        <input type="text" name="search" class="form-control me-2" placeholder="Search Leads" value="{{ request()->search }}">
                        <button type="submit" class="btn btn-outline-secondary"><i class="fa fa-search"></i></button>
                    </div>

                    <!-- Lead Source -->
                    <div class="col-md-3 col-12">
                        <select name="lead_source" class="form-select lead-form-select" onchange="this.form.submit()">
                            <option value="All">Lead Source</option>
                            @foreach(['Web Research', 'Employee Referral', 'Social Media', 'Advertising', 'Direct Call', 'Public Relations'] as $source)
                                <option value="{{ $source }}" {{ request()->lead_source == $source ? 'selected' : '' }}>{{ $source }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date Range -->
                    <div class="col-md-3 col-12">
                        <select name="date_range" class="form-select lead-form-select" onchange="this.form.submit()">
                            <option value="All">Date Range</option>
                            @foreach(['Last Activity', 'Created On', 'Modified On'] as $range)
                                <option value="{{ $range }}" {{ request()->date_range == $range ? 'selected' : '' }}>{{ $range }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Time Frame -->
                    <div class="col-md-3 col-12">
                        <select name="time_frame" class="form-select lead-form-select" onchange="this.form.submit()">
                            @foreach(['All Time', 'Custom', 'Yesterday', 'Today', 'Last Week', 'This Week', 'Last Month', 'This Month', 'Last Year'] as $frame)
                                <option value="{{ $frame }}" {{ request()->time_frame == $frame ? 'selected' : '' }}>{{ $frame }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>

                <!-- Action Buttons -->
                <div class="d-flex flex-wrap mb-4" style="gap: 8px;">
                    @if(session()->get('emp_job_role') == 1)
                        <button class="btn btn-primary" onclick="location.href='{{ route('leads.transfer.view') }}'">Transfer & Share Leads</button>
                        <button class="btn btn-primary" data-toggle="modal" data-target="#importModal">Import Leads</button>
                    @endif
                    <a href="{{ route('followups.today') }}" class="btn btn-info">Today Follow-up</a>
                </div>

                <!-- Flash Messages -->
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <!-- Lead Cards -->
                <div class="row g-3">
                    @foreach($leads as $lead)
                    @php
                        $lastFollowUp = $lead->followUps->sortByDesc('created_at')->first();
                        $daysSinceFollowUp = $lastFollowUp ? \Carbon\Carbon::parse($lastFollowUp->created_at)->diffInDays(now()) : null;
                        $statusText = 'Contacted';
                        $buttonClass = 'btn-secondary';

                        if ($daysSinceFollowUp !== null) {
                            if ($daysSinceFollowUp <= 1) {
                                $statusText = 'Active';
                                $buttonClass = 'btn-warning';
                            } elseif ($daysSinceFollowUp <= 7) {
                                $statusText = 'Engaged';
                                $buttonClass = 'btn-success';
                            } else {
                                $statusText = 'Disengaged';
                                $buttonClass = 'btn-danger';
                            }
                        }
                    @endphp

                    <div class="col-12 col-sm-6 col-md-4 col-lg-3" style="margin-bottom: 20px;">
                        <div class="lead-card" style="background-color: antiquewhite;">
                            <h5 class="mb-2">
                                <a href="{{ url('/i-admin/leads/view/'.$lead->id) }}">
                                    {{ $lead->first_name }} {{ $lead->last_name }}
                                </a>
                            </h5>
                            @if($lead->is_fresh)
                                <span class="badge bg-success ms-1">Fresh</span>
                            @endif
                            </h5>
                            <div class="lead-meta"><strong>Email:</strong> {{ $lead->email }}</div>
                            <div class="lead-meta"><strong>Phone:</strong> {{ $lead->phone }}</div>
                            <div class="lead-meta"><strong>Source:</strong> {{ $lead->lead_source }}</div>
                            <div class="mt-2">
                                <button class="btn btn-sm {{ $buttonClass }}">{{ $statusText }}</button>
                            </div>
                            <div class="lead-actions mt-3">
                                <button class="btn btn-info btn-sm w-100 update-status-btn" data-lead-id="{{ $lead->id }}" data-current-status="{{ $lead->status }}">
                                    Update Status
                                </button>
                                <a href="{{ url('/i-admin/leads/edit/'.$lead->id) }}" class="btn btn-warning btn-sm w-100">Edit</a>
                            </div>
                        </div> 
                    </div>
                    @endforeach
                </div> 

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $leads->links() }}
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
</div>

<!-- Modal for Import Leads -->
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('leads.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import Leads</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label for="leadsFile">Select CSV File:</label>
                    <input type="file" name="leads_file" id="leadsFile" class="form-control" accept=".csv" required>
                    <small class="form-text text-muted">Please upload a valid CSV file.</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Update Status Modal -->
<div class="modal fade" id="updateStatusModal" tabindex="-1" role="dialog" aria-labelledby="updateStatusModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="updateStatusForm" method="POST" action="{{ url('/i-admin/leads/update-status') }}">
        @csrf
        <input type="hidden" name="lead_id" id="modal_lead_id">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="updateStatusModalLabel">Update Lead Status & Reminder</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">

            <div class="form-group">
                <label for="new_status">New Status</label>
                <select name="new_status" id="new_status" class="form-control" required>
                    @foreach(App\Helpers\SiteHelper::getLeadStatus() as $status)
                                        <option value="{{ $status['code'] }}">{{ $status['name'] }}</option>
                                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="next_follow_up">Next Follow-up Date & Time</label>
                <input type="datetime-local" name="next_follow_up" id="next_follow_up" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="comments">Comments</label>
                <input type="text" name="comments" id="comments" class="form-control" required>
            </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Update Status</button>
          </div>
        </div>
    </form>
  </div>
</div>


<script>
$(document).ready(function() {
    $('.update-status-btn').click(function() {
        var leadId = $(this).data('lead-id');
        var currentStatus = $(this).data('current-status');

        $('#modal_lead_id').val(leadId);
        $('#new_status').val(currentStatus);

        $('#updateStatusModal').modal('show');
    });
});
</script>

@endsection <!-- End of content section -->