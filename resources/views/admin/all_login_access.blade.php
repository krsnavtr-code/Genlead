@extends('main')

@section('title', 'Employees Details')

@section('content')
<style>
    section.content {
        margin-top: -25px;
    }
</style>
<div class="container-fluid mt-4">
    <h3 class="mb-4">All Login Access</h3>

    @if (session('errors'))
    <div class="alert alert-danger">
        <ul>
            @foreach (session('errors')->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
   @endif

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
  
    <table class="table table-bordered table-responsive">
        <thead>
            <tr>
                 <th>S.No</th>
                <th>Employee Name</th>
                <th>Email</th>
                <th>Username</th>
                <th>Password</th>
                <th>Job Role</th>
                {{-- <th>Joining Date</th> --}}
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($employees as $index => $employee)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $employee->emp_name }}</td>
                    <td>{{ $employee->emp_email }}</td>
                    <td>{{ $employee->emp_username }}</td>
                    <td>{{ $employee->emp_password }}</td>
                    <td>
                           @if ($employee->emp_job_role == 1)
                           SuperAdmin
                          @elseif ($employee->emp_job_role == 2)
                           Agent
                          @elseif ($employee->emp_job_role == 4)
                           HR
                          @elseif ($employee->emp_job_role == 5)
                          Accountant
                          @else
                          Unknown Role
                        @endif
                  </td>
                  {{-- <td>{{ $employee->emp_join_date }}</td> --}}
                    <td>
                        <!-- Button to trigger password change modal -->
                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#changePasswordModal{{ $employee->id }}">
                            Change Password
                        </button>

                        <!-- Modal for changing password -->
                        <div class="modal fade" id="changePasswordModal{{ $employee->id }}" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">

                                    @if (session('error'))
                                    <div class="alert alert-danger">{{ session('error') }}</div>
                                    @endif
                                    <form action="{{url('/admin/change-employee-password')}}" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">Change Password:</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="employee_id" value="{{ $employee->id }}">             
                                            <div class="form-group">
                                                <label for="newPassword">New Password</label>
                                                <input type="text" class="form-control" name="new_password" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="confirmPassword">Confirm Password</label>
                                                <input type="password" class="form-control" name="new_password_confirmation" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
