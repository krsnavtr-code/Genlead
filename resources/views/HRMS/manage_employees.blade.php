@extends('main')

@section('title', 'Manage Employees')

@section('content')
<div class="container-fluid">
    <div class="content-header sty-one text-center my-2">
    <h4 class="mb-2 text-center">New-Joinee Uploaded Documents</h4>
    </div>
    <div class="d-flex flex-wrap mb-0 justify-content-end">
    <a href="{{ url('/admin/new-join/add')}}" class="btn btn-primary m-2">Add New</a>
    <a href="{{ url('/admin/hrms/candidate-interview-result')}}" class="btn btn-primary m-2">Interview Result</a>
</div>
    <div class="content">
        <div class="card shadow-sm">
            <div class="card-body">
                <!-- Success Alert -->
                @if(session('success'))
                    <div class="alert alert-success text-center">{{ session('success') }}</div>
                @endif
                <div class="table-responsive">
    <table class="table table-sm table-bordered align-middle table-striped mb-0" style="font-size: 13px;">
        <thead class="table-primary text-center">
            <tr style="white-space: nowrap;">
                <th>Name</th>
                <th>Emp ID</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Location</th>
                <th>Salary</th>
                <th>Docs</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($employees as $employee)
                <tr>
                    <td class="text-primary fw-semibold">{{ $employee->name }}</td>
                    <td>{{ $employee->id }}</td>
                    <td>{{ $employee->email }}</td>
                    <td>{{ $employee->phone }}</td>
                    <td>{{ $employee->address }}</td>
                    <td>{{ $employee->salary_discussed ? 'Yes' : 'No' }}</td>
                    <td>
                        <ul class="list-unstyled mb-0 small">
                            <li>
                                @if($employee->company_pan_file)
                                    <a href="{{ asset($employee->company_pan_file) }}" target="_blank">Marksheet</a>
                                @else
                                    <small class="text-muted">Marksheet: N/A</small>
                                @endif
                            </li>
                            <li>
                                @if($employee->personal_aadhar_file)
                                    <a href="{{ asset($employee->personal_aadhar_file) }}" target="_blank">Aadhar</a>
                                @else
                                    <small class="text-muted">Aadhar: N/A</small>
                                @endif
                            </li>
                            <li>
                                @if($employee->personal_pan_file)
                                    <a href="{{ asset($employee->personal_pan_file) }}" target="_blank">PAN</a>
                                @else
                                    <small class="text-muted">PAN: N/A</small>
                                @endif
                            </li>
                        </ul>
                    </td>
                    <td class="text-center">
                        @if(!$employee->is_verified)
                            <a href="{{ url('/admin/hrms/verify-document/' . $employee->id) }}"
                               class="btn btn-sm btn-outline-warning px-2 py-1">
                                Verify
                            </a>
                        @else
                            <span class="badge bg-success">✔</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

    </div>
</div>
@endsection
