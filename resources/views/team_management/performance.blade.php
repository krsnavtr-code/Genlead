@extends('main')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Team Performance</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.team.management') }}">Team Management</a></li>
                        <li class="breadcrumb-item active">Team Performance</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Team Performance Metrics</h3>
                            <div class="card-tools">
                                <a href="{{ route('admin.team.management') }}" class="btn btn-default btn-sm">
                                    <i class="fas fa-arrow-left"></i> Back to Team
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Team Member</th>
                                            <th class="text-center">Total Leads</th>
                                            <th class="text-center">Converted Leads</th>
                                            <th class="text-center">Conversion Rate</th>
                                            <th class="text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($teamMembers as $member)
                                            @php
                                                $conversionRate = $member->total_leads > 0 
                                                    ? round(($member->converted_leads / $member->total_leads) * 100, 2)
                                                    : 0;
                                            @endphp
                                            <tr>
                                                <td>{{ $member->emp_name }}</td>
                                                <td class="text-center">{{ $member->total_leads }}</td>
                                                <td class="text-center">{{ $member->converted_leads }}</td>
                                                <td class="text-center">
                                                    <span class="badge {{ $conversionRate >= 50 ? 'badge-success' : ($conversionRate >= 25 ? 'badge-warning' : 'badge-danger') }}">
                                                        {{ $conversionRate }}%
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge badge-{{ $member->emp_status === 'active' ? 'success' : 'danger' }}">
                                                        {{ ucfirst($member->emp_status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">No team members found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-box bg-info">
                                        <span class="info-box-icon"><i class="fas fa-users"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Total Team Members</span>
                                            <span class="info-box-number">{{ count($teamMembers) }}</span>
                                        </div>
                                    </div>
                                </div>
                                @php
                                    $totalLeads = $teamMembers->sum('total_leads');
                                    $totalConverted = $teamMembers->sum('converted_leads');
                                    $teamConversionRate = $totalLeads > 0 ? round(($totalConverted / $totalLeads) * 100, 2) : 0;
                                @endphp
                                <div class="col-md-6">
                                    <div class="info-box bg-success">
                                        <span class="info-box-icon"><i class="fas fa-chart-pie"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Team Conversion Rate</span>
                                            <span class="info-box-number">{{ $teamConversionRate }}%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Include Chart.js for future chart implementation -->
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Future implementation for performance charts
    document.addEventListener('DOMContentLoaded', function() {
        // Chart initialization code can go here
    });
</script>
@endsection
@endsection
