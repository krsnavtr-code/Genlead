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

    <!-- Main content -->
    <div class="container-fluid" style="padding: 0px;">
        <div class="card shadow-sm mt-3">
            <div class="card-body" style="padding: 8px;">
                <!-- Filters and Search -->
                <form method="GET" action="{{ url('/i-admin/show-leads') }}" class="row g-3 mb-4" style="gap: 8px;">
                    <!-- Search -->
                    <div class="col-md-3 col-12 d-flex">
                        <input type="text" name="search" class="form-control me-2" placeholder="Search Leads" value="{{ request()->search }}">
                        <button type="submit" class="btn btn-outline-secondary"><i class="fa fa-search"></i></button>
                    </div>

                    <!-- Search by Status -->
                    <div class="col-md-3 col-12">
                        <select name="status" class="form-select lead-form-select" onchange="this.form.submit()">
                            <option value="">All Statuses</option>
                            <option value="other" {{ request()->status === 'other' ? 'selected' : '' }}>Other (No Status)</option>
                            @foreach(\App\Helpers\SiteHelper::getLeadStatus() as $type => $categories)
                                <optgroup label="{{ $type }}">
                                    @foreach($categories as $category)
                                        @foreach($category['subcategories'] as $subcategory)
                                            <option value="{{ $subcategory['code'] }}" {{ request()->status == $subcategory['code'] ? 'selected' : '' }}>
                                                {{ $category['category'] }} - {{ $subcategory['name'] }}
                                            </option>
                                        @endforeach
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                    

                    <!-- Lead Source -->
                    @if(in_array(session()->get('emp_job_role'), [1, 8]))
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
                    @if(in_array(session()->get('emp_job_role'), [1, 8]))
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
                            @foreach(['All Time', 'Yesterday', 'Today', 'Last Week', 'This Week', 'Last Month', 'This Month', 'Last Year'] as $frame)
                                <option value="{{ $frame }}" {{ request()->time_frame == $frame ? 'selected' : '' }}>{{ $frame }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>

                <!-- Action Buttons -->
                <div class="d-flex flex-wrap mb-4" style="gap: 8px;">
                    @if(in_array(session()->get('emp_job_role'), [1, 8]))
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

                    
                    <div class="dropdown" style="display: inline-block;">
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

                <form id="leadsForm" method="POST" action="{{ route('leads.bulk.action') }}">
                    @csrf
                    <input type="hidden" name="select_all" id="selectAllPages" value="0">
                    <input type="hidden" name="current_filters" id="currentFilters" value="{{ json_encode(request()->all()) }}">
                    @if(in_array($emp_job_role, [1, 8]))
                    <div class="mb-3 d-flex align-items-center" style="gap: 10px;">
                        <button type="button" class="btn btn-primary btn-sm" id="selectAllBtn">Select All</button>
                        <button type="button" class="btn btn-secondary btn-sm" id="deselectAllBtn">Deselect All</button>
                        <button type="submit" class="btn btn-success btn-sm" name="action" value="export" id="exportBtn" disabled>
                            Export Selected (<span id="selectedCount">0</span>)
                        </button>
                        <button type="submit" class="btn btn-warning btn-sm" name="action" value="transfer" id="transferBtn" disabled>
                            <i class="fas fa-exchange-alt"></i> Transfer to Agent 76
                        </button>
                        <span class="text-muted ms-2" id="selectionInfo">No leads selected</span>
                    </div>
                    @endif
                    <table style="display: inline-table;" class="table table-bordered table-striped table-hover table-sm w-100">
                        <thead class="table-info w-100">
                            <tr class="w-100">
                                <th><input type="checkbox" id="selectAll"></th>
                                <th>#</th>
                                <th>Name & Status</th>
                                <th class="display_none">Email</th>
                                <th class="display_none">Phone</th>
                                <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="w-100">
                        @foreach($leads as $lead)
                        <tr class="w-100">
                            <td style="padding: 5px; text-align: center;">
                                <input type="checkbox" name="selected_leads[]" value="{{ $lead->id }}" class="lead-checkbox">
                            </td>
                            <td style="padding: 5px; text-align: center; font-size: 12px;">{{ ($leads->currentPage() - 1) * $leads->perPage() + $loop->iteration }}</td>
                            <td class="d-flex flex-column align-items-start" style="padding: 5px; font-size: 14px;">
                                <a href="{{ url('/i-admin/leads/view/'.$lead->id) }}" class="d-block mb-1 text-primary">
                                    {{ $lead->first_name }} {{ $lead->last_name }}
                                </a>
                                <div class="">
                                    @php
                                        $currentEmployeeName = auth()->user()->emp_name ?? session('emp_name');
                                        $userRole = auth()->user()->emp_job_role ?? session('emp_job_role');
                                        $isAdmin = $userRole == 1; // Check if user is admin (role 1)
                                    @endphp
                                    @foreach($lead->followUps->sortByDesc('created_at')->take(1) as $followUp)
                                        @if($isAdmin || ($followUp->agent && $followUp->agent->emp_name === $currentEmployeeName))
                                           <span class="text-dark">{{ \Illuminate\Support\Str::after($followUp->comments, 'to ') }}</span>
                                        @endif
                                    @endforeach
                                </div>
                            </td>
                            <td class="display_none" style="padding: 5px;">{{ $lead->email }}</td>
                            <td class="display_none" style="padding: 5px;">{{ $lead->phone }}</td>
                            <td style="padding: 5px;">
                                <a href="tel:{{ $lead->phone }}" class="btn btn-primary btn-sm" title="Call">
                                    <i class="fa fa-phone" style="font-size: 12px;"></i>
                                </a>
                                <button type="button" style="font-size: 12px;" class="btn btn-info btn-sm update-status-btn" data-lead-id="{{ $lead->id }}" data-current-status="{{ $lead->status }}">
                                    Update
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    </table>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted">
                            Showing {{ $leads->firstItem() ?? 0 }} to {{ $leads->lastItem() ?? 0 }} of {{ $leads->total() }} entries
                        </div>
                        <div>
                            {{ $leads->links() }}
                        </div>
                    </div>
                </form>

                <style>
                    .pagination {
                        margin: 0;
                        flex-wrap: wrap;
                    }
                    .page-link {
                        padding: 0.25rem 0.5rem;
                        font-size: 0.875rem;
                    }
                    .page-item.active .page-link {
                        background-color: #0d6efd;
                        border-color: #0d6efd;
                    }
                </style>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const selectAllCheckbox = document.getElementById('selectAll');
                        const leadCheckboxes = document.querySelectorAll('.lead-checkbox');
                        const selectAllBtn = document.getElementById('selectAllBtn');
                        const deselectAllBtn = document.getElementById('deselectAllBtn');
                        const exportBtn = document.getElementById('exportBtn');
                        const transferBtn = document.getElementById('transferBtn');
                        const selectedCountSpan = document.getElementById('selectedCount');
                        const selectionInfo = document.getElementById('selectionInfo');
                        
                        // Function to update the selection count and UI
                        function updateSelectionCount() {
                            const selectedCount = document.querySelectorAll('.lead-checkbox:checked').length;
                            selectedCountSpan.textContent = selectedCount;
                            
                            // Update the buttons state
                            const anySelected = selectedCount > 0 || document.getElementById('selectAllPages').value === '1';
                            exportBtn.disabled = !anySelected;
                            transferBtn.disabled = !anySelected;
                            
                            // Update the selection info text
                            if (selectedCount === 0) {
                                selectionInfo.textContent = 'No leads selected';
                                selectionInfo.className = 'text-muted ms-2';
                            } else {
                                selectionInfo.textContent = `${selectedCount} lead${selectedCount !== 1 ? 's' : ''} selected`;
                                selectionInfo.className = 'text-primary fw-bold ms-2';
                            }
                        }

                        // Toggle all checkboxes when select all checkbox is clicked
                        selectAllCheckbox.addEventListener('change', function() {
                            const selectAllPages = document.getElementById('selectAllPages');
                            
                            if (selectAllCheckbox.checked) {
                                // Show confirmation for selecting all across pages
                                if (confirm('Do you want to select all leads that match the current filters? This will select leads on all pages, not just this page.')) {
                                    selectAllPages.value = '1';
                                    // Uncheck all individual checkboxes as they're not needed
                                    leadCheckboxes.forEach(checkbox => {
                                        checkbox.checked = false;
                                    });
                                } else {
                                    // If user cancels, just select current page
                                    selectAllPages.value = '0';
                                    leadCheckboxes.forEach(checkbox => {
                                        checkbox.checked = true;
                                    });
                                }
                            } else {
                                // Deselect all
                                selectAllPages.value = '0';
                                leadCheckboxes.forEach(checkbox => {
                                    checkbox.checked = false;
                                });
                            }
                            updateSelectionCount();
                        });

                        // Select all button
                        selectAllBtn.addEventListener('click', function() {
                            leadCheckboxes.forEach(checkbox => {
                                checkbox.checked = true;
                            });
                            selectAllCheckbox.checked = true;
                            updateSelectionCount();
                        });

                        // Deselect all button
                        deselectAllBtn.addEventListener('click', function() {
                            leadCheckboxes.forEach(checkbox => {
                                checkbox.checked = false;
                            });
                            selectAllCheckbox.checked = false;
                            updateSelectionCount();
                        });

                        // Update select all checkbox and selection count when individual checkboxes are clicked
                        leadCheckboxes.forEach(checkbox => {
                            checkbox.addEventListener('change', function() {
                                // If any checkbox is manually checked, turn off select all pages
                                if (this.checked) {
                                    document.getElementById('selectAllPages').value = '0';
                                }
                                const allChecked = Array.from(leadCheckboxes).every(cb => cb.checked);
                                selectAllCheckbox.checked = allChecked;
                                updateSelectionCount();
                            });
                        });
                        
                        // Initialize the selection count
                        updateSelectionCount();
                    });
                </script>


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