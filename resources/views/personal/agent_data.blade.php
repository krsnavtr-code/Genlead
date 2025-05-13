@extends('main')

@section('title', 'Agent Leads')

@section('content')

@php
    $emp_job_role = session()->get('emp_job_role');
@endphp

<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-body">
            <h1 class="h4 mb-4">Select Agent</h1>

            @if($agents->count() > 0)
                <form action="{{ route('admin.agent.data') }}" method="GET" class="mb-4">
                    @csrf
                    <div class="form-group">
                        <label for="agent_id">Select Agent:</label>
                        <select name="agent_id" id="agent_id" class="form-control" required>
                            <option value="" disabled {{ !request()->has('agent_id') ? 'selected' : '' }}>Select an agent</option>
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id }}" {{ request('agent_id') == $agent->id ? 'selected' : '' }}>
                                    {{ $agent->emp_name }} ({{ $agent->emp_job_role ?? 'No Role' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">View Leads</button>
                </form>
            @else
                <div class="alert alert-warning">
                    No agents found in the system. Please add agents first.
                </div>
            @endif

            @if(isset($agentData))
                <div class="agent-info mb-4">
                    <h2 class="h5 mb-3">Leads for {{ $agentData->emp_name }}</h2>
                    <p class="text-muted">
                        <strong>Email:</strong> {{ $agentData->emp_email }} | 
                        <strong>Phone:</strong> {{ $agentData->emp_phone }}
                    </p>
                </div>

                @if(count($agentLeads) > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($agentLeads as $index => $lead)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $lead->first_name }} {{ $lead->last_name }}</td>
                                        <td>{{ $lead->email }}</td>
                                        <td>{{ $lead->phone }}</td>
                                        <td>
                                            @php
                                                $statusClass = [
                                                    'new' => 'badge bg-primary',
                                                    'contacted' => 'badge bg-info',
                                                    'qualified' => 'badge bg-warning',
                                                    'lost' => 'badge bg-danger',
                                                    'closed' => 'badge bg-secondary'
                                                ][$lead->status] ?? 'badge bg-secondary';
                                                $statusLabel = ucfirst($lead->status);
                                            @endphp
                                            <span class="{{ $statusClass }}">{{ $statusLabel }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info">
                        No leads found for this agent.
                    </div>
                @endif
            @elseif(request()->has('agent_id'))
                <div class="alert alert-warning">
                    No agent found with the selected ID.
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    .table th {
        white-space: nowrap;
    }
    .badge {
        font-size: 0.8rem;
        padding: 0.35em 0.65em;
    }
</style>
@endpush

@endsection
