@extends('main')

@section('title', 'Employees Details')

@section('content')

<div class="container-fluid mt-4">
    <h3 class="mb-4 text-center">All Login Access</h3>

    @if (session('errors'))
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach (session('errors')->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    <div class="row g-4">
        @foreach ($employees as $index => $employee)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card shadow-sm h-100 border-start border-4 border-primary">
                <div class="card-body">
                    <h5 class="card-title mb-2 text-primary fw-bold">{{ $employee->emp_name }}</h5>
                    <br>
                    <p class="mb-1"><strong>Email:</strong> {{ $employee->emp_email }}</p>
                    <p class="mb-1"><strong>Username:</strong> {{ $employee->emp_username }}</p>
                    <p class="mb-1"><strong>Password:</strong> {{ $employee->emp_password }}</p>
                    <p class="mb-2"><strong>Role:</strong>
                        @php
                            $roles = [
                                1 => 'SuperAdmin',
                                2 => 'Agent',
                                4 => 'HR',
                                5 => 'Accountant'
                            ];
                        @endphp
                        {{ $roles[$employee->emp_job_role] ?? 'Unknown Role' }}
                    </p>

                    <div class="d-flex justify-content-between align-items-center">
                        <!-- Trigger Modal -->
                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#changePasswordModal{{ $employee->id }}">
                            Change Password
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="changePasswordModal{{ $employee->id }}" tabindex="-1" aria-labelledby="changePasswordModalLabel{{ $employee->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ url('/admin/change-employee-password') }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="changePasswordModalLabel{{ $employee->id }}">Change Password</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                            <div class="mb-3">
                                <label for="newPassword" class="form-label">New Password</label>
                                <input type="text" name="new_password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="confirmPassword" class="form-label">Confirm Password</label>
                                <input type="password" name="new_password_confirmation" class="form-control" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
