@extends('main')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Team Management</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Team Management</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    {{ session('success') }}
                </div>
            @endif

            @if(isset($agentsWithTeam) && isset($agentsWithoutTeam))
                {{-- Admin View --}}
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Agents with Teams</h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Team Leader</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Total Leads</th>
                                    <th>Converted</th>
                                    <th>Pending</th>
                                    <th>Rejected</th>
                                    <th>Status</th>
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
                                        <td>{{ $member->converted_leads }}</td>
                                        <td>{{ $member->pending_leads }}</td>
                                        <td>{{ $member->rejected_leads }}</td>
                                        <td>
                                            <span class="badge badge-{{ $member->emp_status === 'active' ? 'success' : 'danger' }}">
                                                {{ ucfirst($member->emp_status) }}
                                            </span>
                                        </td>
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
                        <h3 class="card-title">Agents Without Team</h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Total Leads</th>
                                    <th>Converted</th>
                                    <th>Pending</th>
                                    <th>Rejected</th>
                                    <th>Status</th>
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
                                        <td>{{ $member->converted_leads }}</td>
                                        <td>{{ $member->pending_leads }}</td>
                                        <td>{{ $member->rejected_leads }}</td>
                                        <td>
                                            <span class="badge badge-{{ $member->emp_status === 'active' ? 'success' : 'danger' }}">
                                                {{ ucfirst($member->emp_status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.team.member.edit', $member->id) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <a href="{{ route('admin.team.member.followups', $member->id) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> View Followups
                                            </a>
                                            <a href="{{ route('admin.assign.agents.form', ['agent_id' => $member->id]) }}" class="btn btn-sm btn-success">
                                                <i class="fas fa-user-plus"></i> Assign Team
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
                    <div class="card-header">
                        <h3 class="card-title">My Team</h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.team.performance') }}" class="btn btn-info btn-sm">
                                <i class="fas fa-chart-line"></i> View Performance
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Total Leads</th>
                                    <th>Converted</th>
                                    <th>Pending</th>
                                    <th>Rejected</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($teamMembers as $member)
                                    <tr>
                                        <td>{{ $member->emp_name }}</td>
                                        <td>{{ $member->emp_email }}</td>
                                        <td>{{ $member->emp_phone }}</td>
                                        <td>{{ $member->total_leads }}</td>
                                        <td>{{ $member->converted_leads }}</td>
                                        <td>{{ $member->pending_leads }}</td>
                                        <td>{{ $member->rejected_leads }}</td>
                                        <td>
                                            <span class="badge badge-{{ $member->emp_status === 'active' ? 'success' : 'danger' }}">
                                                {{ ucfirst($member->emp_status) }}
                                            </span>
                                        </td>
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
