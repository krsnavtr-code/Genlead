@extends('main')

@section('title', 'Team Management')

@php
$emp_job_role = session('emp_job_role');
@endphp

@push('styles')
<link href="{{ asset('css/team-management.css') }}" rel="stylesheet">
@endpush

@php
    $statuses = \App\Models\personal\LeadStatus::all();
@endphp

@push('scripts')
<script src="{{ asset('js/team-management.js') }}"></script>
<div id="status-data" 
     data-statuses='@json($statuses->map(function($status) {
         return [
             "id" => $status->id,
             "color" => $status->color,
             "name" => $status->name
         ];
     }))'>
</div>
@endpush

<style>
    .table-extra-sm td,
    .table-extra-sm th {
        padding: 0.25rem !important;
        font-size: 0.75rem !important;
        vertical-align: middle !important;
    }
    header::after {
    display: none;
    clear: both;
    content: "";
}
</style>


@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Team Leads Tracking</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Team Leads Tracking</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    
    <section class="content">
        <div class="container-fluid">
            <!-- Team Leads Section -->
            <div class="card mt-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <p class="m-0">All Leads of My Team Owns</p>
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
                <div class="card-body">
                    <!-- Search and Filter Form -->
                    <form action="{{ route('admin.team.management') }}" method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" name="search" class="form-control" placeholder="Search by name/phone..." value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <select name="agent" class="form-control">
                                        <option value="">Select Agent</option>
                                        @foreach($teamMembers as $member)
                                            <option value="{{ $member->id }}" {{ request('agent') == $member->id ? 'selected' : '' }}>{{ $member->emp_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="{{ route('admin.team.management') }}" class="btn btn-secondary">Reset</a>
                            </div>
                        </div>
                    </form>

                    <!-- Leads Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered rounded table-striped table-hover table-extra-sm">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Lead Name</th>
                                    <th>Agent</th>
                                    <th>Status</th>
                                    <th>Follow-ups</th>
                                    <th>Last Contact</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($teamLeads as $lead)
                                    <tr>
                                        <td>{{ $loop->iteration + ($teamLeads->currentPage() - 1) * $teamLeads->perPage() }}</td>
                                        <td>{{ $lead->first_name }} {{ $lead->last_name }}</td>
                                        <td>{{ $lead->agent->emp_name ?? 'N/A' }}</td>
                                        <td>
                                            @if(!empty($lead->status))
                                                <span class="status-badge status-badge-{{ $lead->status_id }}">
                                                    {{ ucfirst($lead->status) }}
                                                </span>
                                            @else
                                                <span class="badge badge-secondary">Not Talk</span>
                                            @endif
                                        </td>
                                        <td>{{ $lead->follow_ups_count ?? 0 }}</td>
                                        <td>
                                            @if($lead->followUps->isNotEmpty())
                                                {{ $lead->followUps->first()->follow_up_time->diffForHumans() }}
                                            @else
                                                No follow-ups
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.team.member.lead-details', $lead->id) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No leads found for your team.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($teamLeads->hasPages())
                        <div class="d-flex justify-content-center mt-3">
                            <nav aria-label="Page navigation">
                                <ul class="pagination flex-wrap">
                                    {{-- Previous Page Link --}}
                                    @if($teamLeads->onFirstPage())
                                        <li class="page-item disabled">
                                            <span class="page-link">&laquo;</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ route('admin.team.management', array_merge(request()->query(), ['page' => $teamLeads->currentPage() - 1])) }}" rel="prev">&laquo;</a>
                                        </li>
                                    @endif

                                    {{-- Pagination Elements --}}
                                    @foreach($teamLeads->getUrlRange(1, $teamLeads->lastPage()) as $page => $url)
                                        @if($page == $teamLeads->currentPage())
                                            <li class="page-item active">
                                                <span class="page-link">{{ $page }}</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ route('admin.team.management', array_merge(request()->query(), ['page' => $page])) }}">{{ $page }}</a>
                                            </li>
                                        @endif
                                    @endforeach

                                    {{-- Next Page Link --}}
                                    @if($teamLeads->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" href="{{ route('admin.team.management', array_merge(request()->query(), ['page' => $teamLeads->currentPage() + 1])) }}" rel="next">&raquo;</a>
                                        </li>
                                    @else
                                        <li class="page-item disabled">
                                            <span class="page-link">&raquo;</span>
                                        </li>
                                    @endif
                                </ul>
                            </nav>
                        </div>
                    @endif
                </div>
            </div>
            <!-- End Team Leads Section -->
            <!-- Assign Agents to Team Leader for Admin (1) -->
            @if(in_array($emp_job_role, [1]))
            <div class="card">
                <div class="card-header">
                    <a href="{{ url('/admin/assign-agents-to-team-leader') }}" class="nav-link w-100 {{ request()->is('admin/assign-agents-to-team-leader') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-plus" style="color: #0062CC;"></i>
                        <span>Assign Agents to Team Leader</span>
                    </a>
                </div>
            </div>
            @endif
            @if(session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    {{ session('success') }}
                </div>
            @endif

            @if(isset($agentsWithTeam) && isset($agentsWithoutTeam))
                {{-- Admin View --}}
                <div class="card">
                    <div class="card-header bg-success">
                        <h5 class="card-title">Agents with Teams</h5>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped table-extra-sm">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Team Leader</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Total Leads</th>
                                    <!-- <th>Converted</th> -->
                                    <!-- <th>Pending</th> -->
                                    <!-- <th>Rejected</th> -->
                                    <!-- <th>Status</th> -->
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($agentsWithTeam as $member)
                                    <tr>
                                        <td>{{ $member->emp_name }}</td>
                                        <td>{{ $member->reportsTo->emp_name ?? 'N/A' }}</td>
                                        <td>{{ $member->emp_email }}</td>
                                        <td>{{ $member->emp_phone }}</td>
                                        <td>{{ $member->total_leads }}</td>
                                        <!-- <td>{{ $member->converted_leads }}</td> -->
                                        <!-- <td>{{ $member->pending_leads }}</td> -->
                                        <!-- <td>{{ $member->rejected_leads }}</td> -->
                                        <!-- <td>
                                            <span class="badge badge-{{ $member->emp_status === 'active' ? 'success' : 'danger' }}">
                                                {{ ucfirst($member->emp_status) }}
                                            </span>
                                        </td> -->
                                        <td>
                                            <a href="{{ route('admin.team.member.edit', $member->id) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <a href="{{ route('admin.team.member.followups', $member->id) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> View Followups
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">No agents with teams found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header bg-warning">
                        <h5 class="card-title">Agents Without Team</h5>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped table-extra-sm">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Total Leads</th>
                                    <!-- <th>Converted</th> -->
                                    <!-- <th>Pending</th> -->
                                    <!-- <th>Rejected</th> -->
                                    <!-- <th>Status</th> -->
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($agentsWithoutTeam as $member)
                                    <tr>
                                        <td>{{ $member->emp_name }}</td>
                                        <td>{{ $member->emp_email }}</td>
                                        <td>{{ $member->emp_phone }}</td>
                                        <td>{{ $member->total_leads }}</td>
                                        <!-- <td>{{ $member->converted_leads }}</td> -->
                                        <!-- <td>{{ $member->pending_leads }}</td> -->
                                        <!-- <td>{{ $member->rejected_leads }}</td> -->
                                        <!-- <td>
                                            <span class="badge badge-{{ $member->emp_status === 'active' ? 'success' : 'danger' }}">
                                                {{ ucfirst($member->emp_status) }}
                                            </span>
                                        </td> -->
                                        <td>
                                            <a href="{{ route('admin.team.member.edit', $member->id) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <a href="{{ route('admin.team.member.followups', $member->id) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> View Followups
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">All agents have been assigned to teams.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                {{-- Team Leader View --}}
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title">All My Team Members</h5>
                        <div class="card-tools">
                            <a href="{{ route('admin.team.performance') }}" class="btn btn-info btn-sm">
                                <i class="fas fa-chart-line"></i> View Performance
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped table-extra-sm">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Total Leads</th>
                                    <!-- <th>Converted</th> -->
                                    <!-- <th>Pending</th> -->
                                    <!-- <th>Rejected</th> -->
                                    <!-- <th>Status</th> -->
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($teamMembers as $member)
                                    <tr>
                                        <td>{{ $member->emp_name }}</td>
                                        <td>{{ $member->emp_email}}</td>
                                        <td>{{ $member->emp_phone }}</td>
                                        <td>{{ $member->total_leads }}</td>
                                        <!-- <td>{{ $member->converted_leads }}</td> -->
                                        <!-- <td>{{ $member->pending_leads }}</td> -->
                                        <!-- <td>{{ $member->rejected_leads }}</td> -->
                                        <!-- <td>
                                            <span class="badge badge-{{ $member->emp_status === 'active' ? 'success' : 'danger' }}">
                                                {{ ucfirst($member->emp_status) }}
                                            </span>
                                        </td> -->
                                        <td>
                                            <!-- <a href="{{ route('admin.team.member.edit', $member->id) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i> Edit
                                            </a> -->
                                            <a href="{{ route('admin.team.member.followups', $member->id) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> View Followups
                                            </a>
                                            <a href="{{ route('admin.team.member.leads-details', $member->id) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> View Leads Details
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No team members found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </section>
</div>
@endsection
