@extends('main')

@section('title', 'Manage Employees')

@section('content')
<div class="container-fluid">
    <div class="content-header sty-one text-center my-4">
        <h1>HRMS - Welcome Page</h1>
    </div>

    <div class="content">
        <div class="card shadow-sm">
            <div class="card-body">

                <!-- Welcome Message -->
                <div class="mb-4 text-center">
                    <h4>Welcome to the HRMS Dashboard</h4>
                    <p>Manage employee documents, add new candidates, and track interview results.</p>
                </div>

                <!-- Buttons -->
                <div class="d-flex flex-wrap mb-4 justify-content-center">
                    <a href="{{ url('/admin/new-join/add')}}" class="btn btn-primary m-2">Add New Candidate</a>
                    <a href="{{ url('/admin/hrms/candidate-interview-result')}}" class="btn btn-primary m-2">Candidate Interview Result</a>
                </div>

                <!-- Success Alert -->
                @if(session('success'))
                    <div class="alert alert-success text-center">{{ session('success') }}</div>
                @endif

                <h4 class="mb-4 text-center">New-Joinee Uploaded Documents</h4>

                <!-- Cards for Employees -->
                <div class="row g-4">
                    @foreach($employees as $employee)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 border-start border-4 border-primary shadow-sm bg-light">
                            <div class="card-body">
                                <h5 class="card-title text-primary fw-bold">{{ $employee->name }}</h5>
                                <br>
                                <p class="mb-1"><strong>Employee ID:</strong> {{ $employee->id }}</p>
                                <p class="mb-1"><strong>Email:</strong> {{ $employee->email }}</p>
                                <p class="mb-1"><strong>Phone:</strong> {{ $employee->phone }}</p>
                                <p class="mb-1"><strong>Location:</strong> {{ $employee->address }}</p>
                                <p class="mb-2"><strong>Salary Discussed:</strong> {{ $employee->salary_discussed == 1 ? 'Yes' : 'No' }}</p>

                                <h6 class="mt-3">Documents:</h6>
                                <ul class="ps-3 mb-3">
                                    <li>
                                        @if($employee->company_pan_file)
                                            <a href="{{ asset($employee->company_pan_file) }}" target="_blank">Marksheet</a>
                                        @else
                                            Marksheet: Not Uploaded
                                        @endif
                                    </li>
                                    <li>
                                        @if($employee->personal_aadhar_file)
                                            <a href="{{ asset($employee->personal_aadhar_file) }}" target="_blank">Personal Aadhar</a>
                                        @else
                                            Personal Aadhar: Not Uploaded
                                        @endif
                                    </li>
                                    <li>
                                        @if($employee->personal_pan_file)
                                            <a href="{{ asset($employee->personal_pan_file) }}" target="_blank">Personal PAN</a>
                                        @else
                                            Personal PAN: Not Uploaded
                                        @endif
                                    </li>
                                </ul>

                                <div class="d-flex justify-content-between align-items-center">
                                    @if(!$employee->is_verified)
                                        <a href="{{ url('/admin/hrms/verify-document/' . $employee->id) }}" class="btn btn-sm btn-outline-warning">
                                            Pending Documents Verify
                                        </a>
                                    @else
                                        <span class="badge bg-success">Verified</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
