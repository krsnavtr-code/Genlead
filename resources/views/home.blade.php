@extends('main')

@section('title', 'Genlead - Home')

@section('content')

@php
    $emp_job_role = session()->get('emp_job_role');
@endphp

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Genlead Dashboard</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
     <!-- home for admin and agent -->
    @if ($emp_job_role == 1 || $emp_job_role == 2)
    <section class="content">
        <div class="container-fluid">
            <!-- Welcome Banner -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card bg-gradient-primary">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <h2 class="text-white">Welcome to Genlead</h2>
                                    <p class="text-white">Your comprehensive lead management system designed to streamline your sales process and boost conversions.</p>
                                    <a href="{{ route('how-to-use') }}" class="btn btn-light">Learn How to Use</a>
                                </div>
                                <div class="col-md-4 text-center d-none d-md-block">
                                    <i class="fas fa-chart-line fa-5x text-white-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Overview -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <!-- Total Leads -->
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $totalLeads ?? 0 }}</h3>
                            <p>Total Leads</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <a href="/i-admin/show-leads" class="small-box-footer">View All Leads <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <!-- Converted Leads -->
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $convertedLeads ?? 0 }}</h3>
                            <p>Converted Leads</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <a href="#" class="small-box-footer">More Details <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <!-- Today's Follow-ups -->
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $todayFollowups ?? 0 }}</h3>
                            <p>Today's Follow-ups</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <a href="/i-admin/followups/today" class="small-box-footer">View Follow-ups <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-3 col-6">
                    <!-- Tomorrow's Follow-ups -->
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $tomorrowFollowups ?? 0 }}</h3>
                            <p>Tomorrow's Follow-ups</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                        <a href="/i-admin/followups/tomorrow" class="small-box-footer">View Follow-ups <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-3 col-6">
                    <!-- Upcoming Follow-ups -->
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $upcomingFollowups ?? 0 }}</h3>
                            <p>Upcoming Follow-ups</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <a href="/i-admin/followups/upcoming" class="small-box-footer">View Follow-ups <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-3 col-6">
                    <!-- Overdue Follow-ups -->
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ $overdueFollowups ?? 0 }}</h3>
                            <p>Overdue Follow-ups</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <a href="/i-admin/followups/overdue" class="small-box-footer">View Follow-ups <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <!-- Pending Payments -->
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ $pendingPayments ?? 0 }}</h3>
                            <p>Pending Payments</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <a href="/i-admin/pending" class="small-box-footer">View Payments <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Recent Leads -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-user-plus mr-1"></i>
                                Recent Leads
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table m-0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Phone</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(isset($recentLeads) && count($recentLeads) > 0)
                                            @foreach($recentLeads as $lead)
                                            <tr>
                                                <td><a href="/i-admin/leads/view/{{ $lead->id ?? 0 }}">#{{ $lead->id ?? 0 }}</a></td>
                                                <td>{{ $lead->name ?? 'N/A' }}</td>
                                                <td>{{ $lead->phone ?? 'N/A' }}</td>
                                                <td>
                                                    @if(isset($lead->status))
                                                        @if($lead->status == 1)
                                                            <span class="badge badge-primary">New</span>
                                                        @elseif($lead->status == 2)
                                                            <span class="badge badge-warning">In Progress</span>
                                                        @elseif($lead->status == 3)
                                                            <span class="badge badge-success">Converted</span>
                                                        @elseif($lead->status == 4)
                                                            <span class="badge badge-danger">Lost</span>
                                                        @else
                                                            <span class="badge badge-secondary">Unknown</span>
                                                        @endif
                                                    @else
                                                        <span class="badge badge-secondary">N/A</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="/i-admin/leads/view/{{ $lead->id ?? 0 }}" class="btn btn-xs btn-info">
                                                        <i class="fas fa-eye"></i> View
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="5" class="text-center">No recent leads found</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <a href="/i-admin/show-leads" class="text-primary">View All Leads</a>
                        </div>
                    </div>
                </div>

                <!-- Today's Follow-ups -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-calendar-day mr-1"></i>
                                Today's Follow-ups
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <ul class="todo-list" data-widget="todo-list">
                                @if(isset($todayFollowupsList) && count($todayFollowupsList) > 0)
                                    @foreach($todayFollowupsList as $followup)
                                    <li>
                                        <span class="handle">
                                            <i class="fas fa-ellipsis-v"></i>
                                            <i class="fas fa-ellipsis-v"></i>
                                        </span>
                                        <div class="icheck-primary d-inline ml-2">
                                            <input type="checkbox" value="" name="todo1" id="todoCheck{{ $followup->id ?? 0 }}">
                                            <label for="todoCheck{{ $followup->id ?? 0 }}"></label>
                                        </div>
                                        <span class="text">{{ $followup->lead->name ?? 'Unknown Lead' }}</span>
                                        <small class="badge badge-info"><i class="far fa-clock"></i> {{ isset($followup->follow_up_time) ? \Carbon\Carbon::parse($followup->follow_up_time)->format('h:i A') : 'N/A' }}</small>
                                        <div class="tools">
                                            <a href="/i-admin/leads/view/{{ $followup->lead_id ?? 0 }}" class="text-primary"><i class="fas fa-eye"></i></a>
                                        </div>
                                    </li>
                                    @endforeach
                                @else
                                    <li class="text-center py-3">
                                        <span class="text">No follow-ups scheduled for today</span>
                                    </li>
                                @endif
                            </ul>
                        </div>
                        <div class="card-footer text-center">
                            <a href="/i-admin/followups/today" class="text-primary">View All Follow-ups</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Quick Actions</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 col-sm-6 col-12">
                                    <a href="/i-admin/leads/add-lead" class="text-dark">
                                        <div class="info-box">
                                            <span class="info-box-icon bg-primary"><i class="fas fa-user-plus"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Add New Lead</span>
                                                <span class="info-box-number">Create</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-3 col-sm-6 col-12">
                                    <a href="/i-admin/show-leads" class="text-dark">
                                        <div class="info-box">
                                            <span class="info-box-icon bg-success"><i class="fas fa-search"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Find Leads</span>
                                                <span class="info-box-number">Search</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-3 col-sm-6 col-12">
                                    <a href="/i-admin/followups/today" class="text-dark">
                                        <div class="info-box">
                                            <span class="info-box-icon bg-warning"><i class="fas fa-tasks"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Today's Tasks</span>
                                                <span class="info-box-number">Manage</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <!-- <div class="col-md-3 col-sm-6 col-12">
                                    <a href="/i-admin/leads/import" class="text-dark">
                                        <div class="info-box">
                                            <span class="info-box-icon bg-danger"><i class="fas fa-file-export"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Import Data</span>
                                                <span class="info-box-number">Upload</span>
                                            </div>
                                        </div>
                                    </a>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize any dashboard widgets or charts here
        
        // Example: Auto-refresh dashboard data every 5 minutes
        // setInterval(function() {
        //     location.reload();
        // }, 300000);
    });
</script>
@endsection
