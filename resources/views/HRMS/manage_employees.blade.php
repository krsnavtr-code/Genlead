@extends('main')

@section('title', 'Manage Employees')

@section('content')
<div class="content-wrapper">
    <div class="content-header sty-one">
        <h1>HRMS - Welcome Page</h1> 
    </div>

    <div class="content">
        <div class="card">
            <div class="card-body">

                 <!-- Welcome Message -->
                 <div class="mb-4">
                    <h4>Welcome to the HRMS Dashboard</h4>
                    <p>Manage employee documents, add new candidates, and track interview results.</p>
                </div>

                   <!-- Buttons for Adding Candidate and Interview Result -->
                   <div class="mb-3">
                    <a href="{{ url('/admin/new-join/add')}}" class="btn btn-primary">Add New Candidate</a>
                    <a href="{{ url('/admin/hrms/candidate-interview-result')}}" class="btn btn-primary">Candidate Interview Result</a>
                </div>


                <!-- Success popup -->
                @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif

                <h4>New-Joinee Uploaded Documents:</h4>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Employee ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Location</th>
                                <th>Salary Discussed</th>
                                <th>View Documents</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employees as $employee)
                            <tr>
                                <td>{{ $employee->id }}</td>
                                <td>{{ $employee->name }}</td>
                                <td>{{ $employee->email }}</td>
                                <td>{{ $employee->phone }}</td>
                                <td>{{ $employee->address }}</td>
                                <td>{{ $employee->salary_discussed == 1 ? 'Yes' : 'No' }}</td>
                                <td>
                                    <!-- View Documents -->
                                    <ul>
                                        @if($employee->company_pan_file)
                                            <li><a href="{{ asset($employee->company_pan_file) }}" target="_blank"> Marksheet</a></li>
                                        @else
                                            <li> Marksheet: Not Uploaded</li>
                                        @endif

                                        @if($employee->personal_aadhar_file)
                                            <li><a href="{{ asset($employee->personal_aadhar_file) }}" target="_blank">Personal Aadhar</a></li>
                                        @else
                                            <li>Personal Aadhar: Not Uploaded</li>
                                        @endif

                                        @if($employee->personal_pan_file)
                                            <li><a href="{{ asset($employee->personal_pan_file) }}" target="_blank">Personal PAN</a></li>
                                        @else
                                            <li>Personal PAN: Not Uploaded</li>
                                        @endif
                                    </ul>
                                </td>
                                <td>
                                    @if(!$employee->is_verified)
                                        <button type="submit"><a href="{{ url('/admin/hrms/verify-document/'. $employee->id) }}" > Pending Documents Verify </a></button>
                                      
                                    @else
                                        <span class="badge badge-success">Verified</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
