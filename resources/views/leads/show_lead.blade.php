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
</style>

<div class="content-wrapper" >
    <!-- Content Header (Page header) -->
    <div class="content-header sty-one">
        <h1>Manage Leads</h1>
        <ol class="breadcrumb">
            <li><a href="">Leads</a></li>
            {{-- <li class="fa fa-angle-right">Manage Leads</li> --}}
        </ol>
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="card">
            <div class="card-body">

                  <!-- Filters and Search -->
                  <div class="d-flex justify-content-between align-items-center mb-3">
                    <form method="GET" action="{{ route('leads.index') }}">
                        <!-- Search Leads -->
                        <input type="text" name="search" class="form-control" placeholder="Search Leads" style="width: 200px; display: inline-block;" value="{{ request()->search }}">
                        <button type="submit" class="btn btn-secondary" style="margin-left: -30px;"><i class="fa fa-search"></i></button>
                    
                       <!-- Lead Status Filter -->
                    <select name="lead_status" class="form-control" style="display: inline-block; width: 150px;" onchange="this.form.submit()">
                        <option value="All">Lead Status</option>
                        <option value="new" {{ request()->lead_status == 'new' ? 'selected' : '' }}>New</option>
                        <option value="contacted" {{ request()->lead_status == 'contacted' ? 'selected' : '' }}>Contacted</option>
                        <option value="qualified" {{ request()->lead_status == 'qualified' ? 'selected' : '' }}>Qualified</option>
                        <option value="lost" {{ request()->lead_status == 'lost' ? 'selected' : '' }}>Lost</option>
                        <option value="closed" {{ request()->lead_status == 'closed' ? 'selected' : '' }}>Closed</option>
                   </select>
                    
                        <!-- Lead Source Filter -->
                        <select name="lead_source" class="form-control" style="display: inline-block; width: 150px;" onchange="this.form.submit()">
                            <option value="All">Lead Source</option>
                            <option value="Website" {{ request()->lead_source == 'Website' ? 'selected' : '' }}>Website</option>
                            <option value="Referral" {{ request()->lead_source == 'Referral' ? 'selected' : '' }}>Referral</option>
                            <option value="Social Media" {{ request()->lead_source == 'Social Media' ? 'selected' : '' }}>Social Media</option>
                            <option value="Advertisement" {{ request()->lead_source == 'Advertisement' ? 'selected' : '' }}>Advertisement</option>
                            <option value="Other" {{ request()->lead_source == 'Other' ? 'selected' : '' }}>Other</option>
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
                    
                        <select onchange="location = this.value;">
                            <option selected disabled>More Actions</option>
                            <option value="{{ route('leads.export') }}">Export Leads</option>
                            <option value="{{ route('leads.importForm') }}">Import Leads</option>
                        </select>
                    </div>
                </div>
                <!-- Lead Table Information -->
                {{-- <h4 class="text-black">All Leads</h4> --}}
                <div class="table-responsive">
                    <table id="example2" class="table table-bordered table-hover" data-name="cool-table">
                        <thead>
                            <tr>
                                <th>Lead Name</th>
                                <th>Lead Score</th> 
                                {{-- <th>Lead Stage</th> --}}
                                <th>Company</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Lead Source</th>
                                <th>Status</th> 
                                {{-- <th>Modified On</th> --}}
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($leads as $lead)
                            <tr>
                                <td>
                                        {{-- <a href="{{ url('/admin/leads/view/'.$lead->id) }}">
                                            {{ $lead->first_name }} {{ $lead->last_name }}
                                        </a> --}}
                                        <a href="{{ url('/admin/leads/view/'.$lead->id) }}">
                                            <button class="btn {{ $lead->button_color }}" data-toggle="tooltip" title="{{ $lead->status_message }}">
                                                {{ $lead->first_name }} {{ $lead->last_name }}
                                            </button>
                                        </a>
                                </td>
                                <td>{{ $lead->lead_score }}</td>
                                <td>{{ $lead->company }}</td>
                                <td>{{ $lead->email }}</td>
                                <td>{{ $lead->phone }}</td>
                                <td>{{ $lead->lead_source }}</td>
                                <td>
                                    <!-- Status Button -->
                                    <button class="btn {{ $lead->button_color }}">
                                        {{ ucfirst($lead->lead_status) }}
                                    </button>
                                </td>
                                <td>
                                    {{-- <a href="{{ url('/leads/view/'.$lead->id) }}" class="btn btn-info btn-sm">View</a> --}}
                                    <a href="{{ url('/admin/leads/edit/'.$lead->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ url('/admin/leads/delete/'.$lead->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"  onclick="return confirm('Are you sure you want to delete this lead?');">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
</div>
@endsection <!-- End of content section -->
