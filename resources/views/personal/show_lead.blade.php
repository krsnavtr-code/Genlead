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
    /* Horizontal Navbar Styles */
    .horizontal-navbar {
        display: flex;
        justify-content: space-around;
        background-color: #f8f9fa;
        padding: 10px 0;
        border-bottom: 1px solid #ddd;
    }

    .horizontal-navbar a {
        color: #007bff;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        padding: 8px 16px;
        border-radius: 4px;
    }

    .horizontal-navbar a:hover {
        background-color: #007bff;
        color: white;
    }

</style>

<div class="content-wrapper" >

    <div class="horizontal-navbar">
        <a href="{{ url('/i-admin/show-leads') }}">Manage Leads</a>
        <a href="{{ url('/admin/activities/create') }}">Add Activities</a>
        <a href="{{ url('/admin/activities') }}">Manage Activities</a>
        <a href="{{ url('/admin/tasks/create') }}">Create/Add Tasks</a>
        <a href="{{ url('/admin/tasks') }}">Manage Tasks</a>
        <a href="{{ url('/i-admin/pending') }}">Pending Payment</a>
    </div>

    <!-- Content Header (Page header) -->
    <div class="content-header sty-one d-flex justify-content-between align-items-center">
        <div>
            <h1>Manage Leads</h1>
        </div>
        <div>
            <a href="{{ url('/i-admin/leads/add-lead') }}" 
            class="btn btn-danger btn-sm" 
            style=" color: white; padding: 5px 20px; font-size: 16px; font-weight: bold; border-radius: 4px; text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">
         
            <!-- + Mark -->
            <span style="font-size: 20px; font-weight: bold; margin-right: 8px;">&#43;</span>
         
            <!-- Button Text -->
            Add Lead
         </a>
        </div>
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="card">
            <div class="card-body">

                  <!-- Filters and Search -->
                
                    <form method="GET" action="{{ route('leads.index') }}" class="d-flex align-items-center mb-3" style="gap: 30px;">
                        <!-- Search Leads -->
                        <input type="text" name="search" class="form-control" placeholder="Search Leads" style="width: 200px; display: inline-block;" value="{{ request()->search }}">
                        <button type="submit" class="btn btn-secondary" style="margin-left: -30px;"><i class="fa fa-search"></i></button>
                    
                        <!-- Lead Source Filter -->
                        <select name="lead_source" class="form-control" style="display: inline-block; width: 150px;" onchange="this.form.submit()">
                            <option value="All">Lead Source</option>
                            <option value="Web Research" {{ request()->lead_source == 'Web Research' ? 'selected' : '' }}>Web Research</option>
                            <option value="Employee Referral" {{ request()->lead_source == 'Employee Referral' ? 'selected' : '' }}>Employee Referral</option>
                            <option value="Social Media" {{ request()->lead_source == 'Social Media' ? 'selected' : '' }}>Social Media</option>
                            <option value="Advertising" {{ request()->lead_source == 'Advertising' ? 'selected' : '' }}>Advertising</option>
                            <option value="Direct Call" {{ request()->lead_source == 'Direct Call' ? 'selected' : '' }}>Direct Call</option>
                            <option value="Public Relations" {{ request()->lead_source == 'Public Relations' ? 'selected' : '' }}>Public Relations</option>
                            {{-- <option value="Other" {{ request()->lead_source == 'Other' ? 'selected' : '' }}>Other</option> --}}
                        </select>
                    
                        <!-- Date Range Filter -->
                        <select name="date_range" class="form-control" style="display: inline-block; width: 150px;" onchange="this.form.submit()">
                            <option value="All">Date Range</option>
                            <option value="Last Activity" {{ request()->date_range == 'Last Activity' ? 'selected' : '' }}>Last Activity</option>
                            <option value="Created On" {{ request()->date_range == 'Created On' ? 'selected' : '' }}>Created On</option>
                            <option value="Modified On" {{ request()->date_range == 'Modified On' ? 'selected' : '' }}>Modified On</option>
                        </select>
                    
                        <!-- Time Frame Filter -->
                        <select name="time_frame" class="form-control" style="display: inline-block; width: 150px;" onchange="this.form.submit()">
                            <option value="All Time">All Time</option>
                            <option value="Custom" {{ request()->time_frame == 'Custom' ? 'selected' : '' }}>Custom</option>
                            <option value="Yesterday" {{ request()->time_frame == 'Yesterday' ? 'selected' : '' }}>Yesterday</option>
                            <option value="Today" {{ request()->time_frame == 'Today' ? 'selected' : '' }}>Today</option>
                            <option value="Last Week" {{ request()->time_frame == 'Last Week' ? 'selected' : '' }}>Last Week</option>
                            <option value="This Week" {{ request()->time_frame == 'This Week' ? 'selected' : '' }}>This Week</option>
                            <option value="Last Month" {{ request()->time_frame == 'Last Month' ? 'selected' : '' }}>Last Month</option>
                            <option value="This Month" {{ request()->time_frame == 'This Month' ? 'selected' : '' }}>This Month</option>
                            <option value="Last Year" {{ request()->time_frame == 'Last Year' ? 'selected' : '' }}>Last Year</option>
                        </select>
                    </form>
                
                <div class="d-flex align-items-center mb-3" style="gap: 30px;">

                    @if(session()->get('emp_job_role') == 1)
                        <!-- <select onchange="location = this.value;">
                            <option selected disabled>More Actions</option>
                            <option value="{{ route('leads.export') }}">Export All Leads</option>
                        </select> -->
                        <button class="btn btn-primary ml-2" onclick="location.href='{{ route('leads.transfer.view') }}'">Transfer & Share Leads</button>
                        <button class="btn btn-primary ml-2" data-toggle="modal" data-target="#importModal">Import Leads</button>
                    @endif

                        <!-- Import Leads Button -->
                        <button class="btn btn-info ml-2" onclick="location.href='{{ route('followups.today') }}'">Today Follow-up</button>
                </div>

                 <!-- Validation Messages -->
                 @if(session('error'))
                 <div class="alert alert-danger">
                     {{ session('error') }}
                 </div>
                 @endif
                      @if(session('success'))
                    <div class="alert alert-success">
                      {{ session('success') }}
                   </div>
                @endif

                <!-- Lead Table Information -->
                {{-- <h4 class="text-black">All Leads</h4> --}}
                <div class="table-responsive">
                    <table id="example2" class="table table-bordered table-hover" data-name="cool-table">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Lead Name</th>
                                {{-- <th>Lead Score</th>  --}}
                                {{-- <th>Lead Stage</th> --}}
                                {{-- <th>Company</th> --}}
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Lead Source</th>
                                <th>Status</th> 
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($leads as $lead)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                        <a href="{{ url('/i-admin/leads/view/'.$lead->id) }}">
                                            {{ $lead->first_name }} {{ $lead->last_name }}
                                        </a>
                                        @if($lead->is_fresh)
                                        <span style="color: green; font-size: small; font-weight: bold;">Fresh</span>
                                        @endif
                                </td>
                                <td>{{ $lead->email }}</td>
                                <td>{{ $lead->phone }}</td>
                                <td>{{ $lead->lead_source }}</td>
                                         <!-- Add Lead Status Button -->
                                <td>
                                   @php
                                  $lastFollowUp = $lead->followUps->sortByDesc('created_at')->first();
                                        $daysSinceFollowUp = $lastFollowUp ? \Carbon\Carbon::parse($lastFollowUp->created_at)->diffInDays(now()) : null;
                                        $statusText = 'Contacted'; // Default status
                                        $buttonClass = 'btn-secondary'; // Default color

                                        if ($daysSinceFollowUp !== null) {
                                            if ($daysSinceFollowUp <= 1) {
                                                $statusText = 'Active';
                                                $buttonClass = 'btn-warning'; // Green for Active
                                            } elseif ($daysSinceFollowUp <= 7) {
                                                $statusText = 'Engaged';
                                                $buttonClass = 'btn-success'; // Yellow for Engaged
                                            } else {
                                                $statusText = 'Disengaged';
                                                $buttonClass = 'btn-danger'; // Red for Disengaged
                                            }
                                        }
                         @endphp
                         <button class="btn {{ $buttonClass }}">{{ $statusText }}</button>
                     </td>
                                {{-- <td>{{ $lead->lead_score }}</td> --}}
                                {{-- <td>{{ $lead->company }}</td> --}}
                                {{-- <td>
                                    <!-- Status Button -->
                                    <button class="btn {{ $lead->button_color }}">
                                        {{ ucfirst($lead->lead_status) }}
                                    </button>
                                </td> --}}
                                <td>
                                    {{-- <a href="{{ url('/leads/view/'.$lead->id) }}" class="btn btn-info btn-sm">View</a> --}}
                                    <button class="btn btn-info btn-sm update-status-btn"  data-lead-id="{{ $lead->id }}"data-current-status="{{ $lead->status }}">Update status</button>

                                    <a href="{{ url('/i-admin/leads/edit/'.$lead->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    {{-- <form action="{{ url('/i-admin/leads/delete/'.$lead->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"  onclick="return confirm('Are you sure you want to delete this lead?');">Delete</button>
                                    </form> --}}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
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
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
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
