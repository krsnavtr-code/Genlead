@extends('main')

@section('title', 'Agent Referral Leads Details')

@php
$emp_job_role = session('emp_job_role');

// Prepare data for charts
$chartDataArray = [
    'conversionLabels' => $chartData['labels'] ?? [],
    'conversionData' => $chartData['conversionRates'] ?? [],
    'convertedData' => $chartData['converted'] ?? [],
    'pendingData' => $chartData['pending'] ?? [],
    'rejectedData' => $chartData['rejected'] ?? []
];
@endphp

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Agent Referral Leads Details</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Agent Referral Leads</li>
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

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Agent Performance Metrics</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Agent Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Total Leads</th>
                                    <th>Converted</th>
                                    <th>Pending</th>
                                    <th>Rejected</th>
                                    <th>Conversion Rate</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($agents as $agent)
                                    <tr>
                                        <td>{{ $agent->emp_name }}</td>
                                        <td>{{ $agent->emp_email }}</td>
                                        <td>{{ $agent->emp_phone }}</td>
                                        <td class="text-center">{{ $agent->total_leads }}</td>
                                        <td class="text-center text-success">{{ $agent->converted }}</td>
                                        <td class="text-center text-warning">{{ $agent->pending }}</td>
                                        <td class="text-center text-danger">{{ $agent->rejected }}</td>
                                        <td class="text-center">
                                            <span class="badge {{ $agent->conversion_rate >= 50 ? 'bg-success' : ($agent->conversion_rate >= 25 ? 'bg-warning' : 'bg-danger') }}">
                                                {{ $agent->conversion_rate }}%
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.team.member.leads-details', $agent->id) }}" 
                                               class="btn btn-sm btn-info" 
                                               title="View Lead Details">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No agents found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @if($agents->count() > 0)
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Conversion Rate by Agent</h3>
                            </div>
                            <div class="card-body">
                                <canvas id="conversionChart" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Leads Distribution</h3>
                            </div>
                            <div class="card-body">
                                <canvas id="leadsDistributionChart" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
</div>
@endsection

@php
// Prepare chart data as JSON string
$chartDataJson = json_encode($chartDataArray, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
@endphp

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        try {
            // Parse JSON data
            var chartData = JSON.parse('{!! $chartDataJson !!}');
            var conversionLabels = Array.isArray(chartData.conversionLabels) ? chartData.conversionLabels : [];
            var conversionData = Array.isArray(chartData.conversionData) ? chartData.conversionData : [];
            var convertedData = Array.isArray(chartData.convertedData) ? chartData.convertedData : [];
            var pendingData = Array.isArray(chartData.pendingData) ? chartData.pendingData : [];
            var rejectedData = Array.isArray(chartData.rejectedData) ? chartData.rejectedData : [];
            
            // Initialize charts if data is available
            if (conversionLabels && conversionLabels.length > 0) {
                // Create conversion rate chart if element exists
                var conversionCtx = document.getElementById('conversionChart');
                if (conversionCtx) {
                    new Chart(conversionCtx.getContext('2d'), {
                        type: 'bar',
                        data: {
                            labels: conversionLabels,
                            datasets: [{
                                label: 'Conversion Rate (%)',
                                data: conversionData,
                                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    max: 100,
                                    title: {
                                        display: true,
                                        text: 'Conversion Rate (%)'
                                    }
                                }
                            },
                            plugins: {
                                title: {
                                    display: true,
                                    text: 'Conversion Rate by Agent'
                                }
                            }
                        }
                    });
                }


                // Create leads distribution chart if element exists
                var distributionCtx = document.getElementById('leadsDistributionChart');
                if (distributionCtx) {
                    var datasets = [
                        {
                            label: 'Converted',
                            data: convertedData,
                            backgroundColor: 'rgba(40, 167, 69, 0.7)',
                            borderColor: 'rgba(40, 167, 69, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Pending',
                            data: pendingData,
                            backgroundColor: 'rgba(255, 193, 7, 0.7)',
                            borderColor: 'rgba(255, 193, 7, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Rejected',
                            data: rejectedData,
                            backgroundColor: 'rgba(220, 53, 69, 0.7)',
                            borderColor: 'rgba(220, 53, 69, 1)',
                            borderWidth: 1
                        }
                    ];

                    new Chart(distributionCtx.getContext('2d'), {
                        type: 'bar',
                        data: {
                            labels: conversionLabels,
                            datasets: datasets
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                x: {
                                    stacked: true
                                },
                                y: {
                                    stacked: true,
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: 'Number of Leads'
                                    }
                                }
                            },
                            plugins: {
                                title: {
                                    display: true,
                                    text: 'Leads Distribution by Status'
                                },
                                tooltip: {
                                    mode: 'index',
                                    intersect: false
                                }
                            }
                        }
                    });
                }
            }
        } catch (error) {
            console.error('Error initializing charts:', error);
        }
    });
</script>
@endpush
