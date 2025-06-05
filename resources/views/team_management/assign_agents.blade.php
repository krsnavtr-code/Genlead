@extends('main')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $title ?? 'Assign Agents to Team Leader' }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Assign Agents</li>
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
                    <h3 class="card-title">Assign Agents to Team Leader</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.assign.agents') }}" method="POST">
                        @csrf
                        
                        <div class="form-group">
                            <label for="team_leader_id">Select Team Leader</label>
                            <select name="team_leader_id" id="team_leader_id" class="form-control select2" required>
                                <option value="">-- Select Team Leader --</option>
                                @foreach($teamLeaders as $leader)
                                    <option value="{{ $leader->id }}">
                                        {{ $leader->emp_name }} ({{ $leader->emp_email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Select Agents</label>
                            <select name="agent_ids[]" id="agent_ids" style="height: 200px; width: 100%; overflow-y: auto; overflow-x: hidden;" class="form-control select2" multiple="multiple" required>
                                @foreach($agents as $agent)
                                    <option value="{{ $agent->id }}">
                                        {{ $loop->iteration }} - {{ $agent->emp_name }} ({{ $agent->emp_email }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">
                                Hold down the Ctrl (Windows) or Command (Mac) button to select multiple agents.
                            </small>
                        </div>


                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Assign Agents
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">Current Team Assignments</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Team Leader</th>
                                <th>Assigned Agents</th>
                                <th>Agents Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($teamLeaders as $leader)
                                @php
                                    $assignedAgents = $agents->where('reports_to', $leader->id);
                                @endphp
                                <tr>
                                    <td>
                                        <strong>{{ $leader->emp_name }}</strong><br>
                                        <small class="text-muted">{{ $leader->emp_email }}</small>
                                    </td>
                                    <td>
                                        @if($assignedAgents->count() > 0)
                                            <ul class="list-unstyled mb-0">
                                                @foreach($assignedAgents as $agent)
                                                    <li>{{ $loop->iteration }} - {{ $agent->emp_name }} ({{ $agent->emp_email }})</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <span class="text-muted">No agents assigned</span>
                                        @endif
                                    </td>
                                    <td>{{ $assignedAgents->count() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

@push('styles')
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script>
    $(function () {
        // Initialize Select2 Elements
        $('.select2').select2({
            theme: 'bootstrap4',
            placeholder: 'Select an option',
            allowClear: true
        });
    });
</script>
@endpush

@endsection
