@extends('main') <!-- Extend the main layout -->

@section('title', 'Manage Leads') <!-- Set the title for the page -->

@section('content') <!-- Content section -->

@php
    $emp_job_role = session()->get('emp_job_role');
@endphp

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

    .pagination {
        flex-wrap: wrap !important;
        justify-content: center !important;
        gap: 5px;
    }

    @media (max-width: 576px) {
        .lead-card {
            padding: 12px;
        }

        .lead-meta {
            font-size: 0.85rem;
        }
    }
    @media (max-width: 768px) {
        .display_none {
            display: none;
        }
    }
</style>

<div class="content-wrapper" >

    @include('navbar')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Manage Leads</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Manage Leads</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Header (Page header) -->
    <!-- <div class="container-fluid mb-2">
        <div class="d-flex flex-md-row align-items-center justify-content-between gap-3">
            <a href="{{ url('/i-admin/leads/add-lead') }}" 
               class="btn btn-danger d-inline-flex align-items-center px-2  fs-6 fw-bold rounded-2 shadow-sm">
              <span class="fs-5">&#43;</span> Add Lead
            </a>
        </div>
    </div> -->


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
                    @if(session()->get('emp_job_role') == 1)
                    <div class="col-md-3 col-12">
                        <select name="lead_source" class="form-select lead-form-select" onchange="this.form.submit()">
                            <option value="All">Lead Source</option>
                            @foreach(['Web Research', 'Employee Referral', 'Social Media', 'Advertising', 'Direct Call', 'Public Relations'] as $source)
                                <option value="{{ $source }}" {{ request()->lead_source == $source ? 'selected' : '' }}>{{ $source }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <!-- Date Range -->
                    @if(session()->get('emp_job_role') == 1)
                    <div class="col-md-3 col-12">
                        <select name="date_range" class="form-select lead-form-select" onchange="this.form.submit()">
                            <option value="All">Date Range</option>
                            @foreach(['Last Activity', 'Created On', 'Modified On'] as $range)
                                <option value="{{ $range }}" {{ request()->date_range == $range ? 'selected' : '' }}>{{ $range }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

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
                    <div class="dropdown">
                        <button class="btn btn-info dropdown-toggle px-3" type="button"
                            id="leadDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Leads
                        </button>
                        <div class="dropdown-menu px-2" aria-labelledby="leadDropdown">
                            <a class="dropdown-item text-white bg-success mb-1" href="{{ route('leads.transfer.view') }}" style="border-radius: 5px;">Transfer & Share Leads</a>
                            <button class="dropdown-item text-white bg-info mb-1" data-toggle="modal" data-target="#importModal">Import Leads</button>
                            <a class="dropdown-item text-white bg-warning mb-1" href="{{ route('admin.agent.data') }}" style="border-radius: 5px;">Agent Data</a>
                        </div>
                    </div>
                    @endif

                    
                    <div class="dropdown">
                        <button class="btn btn-info dropdown-toggle px-3" type="button"
                            id="followupDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Follow-ups
                        </button>
                        <div class="dropdown-menu px-2" aria-labelledby="followupDropdown">
                            <a class="dropdown-item text-white bg-success mb-1" href="{{ route('followups.today') }}" style="border-radius: 5px;">Today's Follow-ups</a>
                            <a class="dropdown-item text-white bg-info mb-1" href="{{ route('followups.tomorrow') }}" style="border-radius: 5px;">Tomorrow's Follow-ups</a>
                            <a class="dropdown-item text-white bg-warning mb-1" href="{{ route('followups.upcoming') }}" style="border-radius: 5px;">Upcoming Follow-ups</a>
                            <a class="dropdown-item text-white bg-danger" href="{{ route('followups.overdue') }}" style="border-radius: 5px;">Overdue Follow-ups</a>
                        </div>
                    </div>

                    <div class="lead_add">
                        <a href="{{ url('/i-admin/leads/add-lead') }}" class="btn btn-danger rounded-2 ">
                            <span class="fs-5">&#43;</span> Add Lead
                        </a>
                    </div>
                </div>

                <!-- Flash Messages -->
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <!-- Lead Cards -->
                <!-- <div class="row g-3">
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
                            <h5 class="mb-2 d-flex justify-content-between">
                                <a href="{{ url('/i-admin/leads/view/'.$lead->id) }}">
                                    {{ $lead->first_name }} {{ $lead->last_name }}
                                </a>
                                <span class="bg-dark text-white rounded px-2 py-1 fw-semibold">{{ $loop->iteration }}</span>
                            </h5>
                            @if($lead->is_fresh)
                                <span class="badge bg-success ms-1">Fresh</span>
                            @endif
                            <div class="lead-meta display_none"><strong>Email:</strong> {{ $lead->email }}</div>
                            <div class="lead-meta display_none">
                                <strong>Phone:</strong> 
                                <span>{{ $lead->phone }}</span>
                            </div>
                            <div class="lead-meta display_none"><strong>Source:</strong> {{ $lead->lead_source }}</div>
                            <div class="mt-2 w-100">
                                <button class="btn btn-sm {{ $buttonClass }}">{{ $statusText }}</button>
                                <button class="btn btn-info btn-sm update-status-btn" data-lead-id="{{ $lead->id }}" data-current-status="{{ $lead->status }}">
                                    Update Status
                                </button>
                                <a href="tel:{{ $lead->phone }}" class="btn btn-primary btn-sm" style="float: right;"><i class="fa fa-phone"></i></a>
                                <a href="{{ url('/i-admin/leads/edit/'.$lead->id) }}" class="btn btn-warning btn-sm display_none">Edit</a>
                            </div>
                        </div> 
                    </div>
                    @endforeach
                </div>  -->

                <table style="display: inline-table;" class="table table-bordered table-striped table-hover w-100">
                    <thead class="table-info w-100">
                        <tr class="w-100">
                            <th>#</th>
                            <th>Name & Status</th>
                            <th class="display_none">Email</th>
                            <th class="display_none">Phone</th>
                            <!-- <th class="display_none">Source</th> -->
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="w-100">
                        @foreach($leads as $lead)
                        {{-- @php
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
                        @endphp --}}
                        <tr class="w-100">
                            <td style="padding: 5px; text-align: center; font-size: 12px;">{{ $loop->iteration }}</td>
                            <td class="d-flex flex-column align-items-start" style="padding: 5px; font-size: 14px;">
                                <a href="{{ url('/i-admin/leads/view/'.$lead->id) }}">
                                    {{ $lead->first_name }} {{ $lead->last_name }}
                                </a>
                                @php
                                    $statusColor = [
                                        'new' => 'bg-secondary',
                                        'contacted' => 'bg-warning',
                                        'not_connected' => 'bg-info',
                                        'qualified' => 'bg-success',
                                        'not_qualified' => 'bg-danger',
                                        'future' => 'bg-primary',
                                        'lost' => 'bg-dark',
                                        'closed' => 'bg-light text-dark',
                                        'registration_done' => 'bg-success',
                                        'admission_done' => 'bg-success',
                                        'interested' => 'bg-primary',
                                        'not_interested' => 'bg-danger',
                                        'wrong_number' => 'bg-dark',
                                        'follow_up_callback' => 'bg-warning',
                                        'follow_up_ringing' => 'bg-warning',
                                        'follow_up_hang_up' => 'bg-info',
                                        'follow_up_rpnc' => 'bg-info',
                                    ];

                                    $statusKey = strtolower(str_replace(' ', '_', $lead->status)); // sanitize
                                    $badgeClass = $statusColor[$statusKey] ?? 'bg-secondary';
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ $lead->status }}</span>
                                @if($lead->is_fresh)
                                    <span class="badge bg-success ms-1" style="font-size: 12px;">Fresh</span>
                                @endif
                            </td>
                            <td class="display_none" style="padding: 5px;">{{ $lead->email }}</td>
                            <td class="display_none" style="padding: 5px;">{{ $lead->phone }}</td>
                            <!-- <td class="display_none" style="padding: 5px;">{{ $lead->lead_source }}</td> -->
                            <!-- <button class="btn btn-sm lead-status-btn {{ $buttonClass }}">{{ $statusText }}</button> -->

                            <td style="padding: 5px;">
                                <a href="tel:{{ $lead->phone }}" class="btn btn-primary btn-sm" title="Call">
                                    <i class="fa fa-phone" style="font-size: 12px;"></i>
                                </a>
                                <button style="font-size: 12px;" class="btn btn-info btn-sm update-status-btn" data-lead-id="{{ $lead->id }}" data-current-status="{{ $lead->status }}">
                                    Update
                                </button>
                                <!-- <a href="{{ url('/i-admin/leads/edit/'.$lead->id) }}" class="btn btn-warning btn-sm display_none">Edit</a> -->
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>


                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4 flex-wrap">
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
                    <small class="form-text text-muted">Required columns: 'first_name', 'email', 'phone', 'courses' with valid values.</small>
                    <!-- <small class="form-text text-muted">Approximate time to upload 2MB file is 30 seconds.</small> -->
                    <!-- <small class="form-text text-muted">Click <a href="{{ url('/i-admin/leads/download-sample-csv') }}">here</a> to download sample csv</small> -->
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
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="updateStatusModalLabel">Update Lead Status</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="updateStatusForm" action="{{ url('/i-admin/leads/update-status') }}" method="POST">
        @csrf
        <input type="hidden" name="lead_id" id="modal_lead_id">
        <div class="modal-body">
          <div class="form-group">
            <label for="new_status">New Status</label>
            <select name="new_status" id="new_status" class="form-control" required>
              @foreach(App\Helpers\SiteHelper::getLeadStatus() as $leadType => $categories)
                <optgroup label="{{ $leadType }}">
                  @foreach($categories as $category)
                    <optgroup label="&nbsp;&nbsp;→ {{ $category['category'] }}">
                      @foreach($category['subcategories'] as $subcategory)
                        <option value="{{ $subcategory['code'] }}">
                          &nbsp;&nbsp;&nbsp;&nbsp;{{ $subcategory['name'] }}
                        </option>
                      @endforeach
                    </optgroup>
                  @endforeach
                </optgroup>
              @endforeach
            </select>
          </div>

          <div class="form-group" id="follow_up_container">
            <label for="next_follow_up">Next Follow-up Date & Time</label>
            <input type="datetime-local" name="next_follow_up" id="next_follow_up" class="form-control">
          </div>

          <div class="form-group">
            <label for="comments">Comments</label>
            <textarea name="comments" id="comments" class="form-control" placeholder="Add status update comments" required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Update Status</button>
        </div>
      </form>
    </div>
  </div>
</div>


<script>
$(document).ready(function() {
    // Handle update status button click
    $('.update-status-btn').click(function() {
        var leadId = $(this).data('lead-id');
        var currentStatus = $(this).data('current-status');

        // Set form values
        $('#modal_lead_id').val(leadId);
        $('#new_status').val(currentStatus);
        
        // Reset form and show modal
        $('#updateStatusForm')[0].reset();
        $('#updateStatusModal').modal('show');
        
        // Trigger change to update UI based on initial status
        $('#new_status').trigger('change');
    });

    // Show/Hide Next Follow-up calendar Field based on status
    $('#new_status').change(function() {
        const selectedValue = $(this).val();
        const followUpContainer = $('#follow_up_container');
        const hiddenStatuses = [
            'C_not_interested',
            'H_admission_done'
        ];
        
        if (hiddenStatuses.includes(selectedValue)) {
            followUpContainer.hide();
            $('#next_follow_up').removeAttr('required');
        } else {
            followUpContainer.show();
            $('#next_follow_up').attr('required', 'required');
        }
    });

    // Handle form submission
    $('#updateStatusForm').on('submit', function(e) {
        e.preventDefault();
        
        var form = $(this);
        var formData = form.serialize();
        var url = form.attr('action');
        
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    // Show success message
                    alert('Status updated successfully!');
                    // Reload the page to see the changes
                    location.reload();
                } else {
                    // Show error message
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr) {
                // Show error message
                var errorMessage = 'An error occurred while updating the status.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                alert('Error: ' + errorMessage);
            }
        });
    });
});
</script>

@endsection <!-- End of content section -->