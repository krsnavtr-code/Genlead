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
                    <p class="mb-2">
                        <strong>Role:</strong>
                        <span class="role-display" data-employee-id="{{ $employee->id }}">
                            @php
                                $roles = [
                                    1 => 'SuperAdmin',
                                    2 => 'Agent',
                                    4 => 'HR',
                                    5 => 'Accountant',
                                    6 => 'Team Lead'
                                ];
                                $currentRole = $roles[$employee->emp_job_role] ?? 'Unknown Role';
                            @endphp
                            <span class="role-text">{{ $currentRole }}</span>
                            @if(session('emp_job_role') == 1) {{-- Only show edit for superadmin --}}
                                <button class="btn btn-xs btn-link p-0 ml-1 edit-role" 
                                        data-employee-id="{{ $employee->id }}"
                                        data-current-role="{{ $employee->emp_job_role }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <select class="form-control form-control-sm d-none role-select" 
                                        data-employee-id="{{ $employee->id }}">
                                    @foreach($roles as $id => $name)
                                        <option value="{{ $id }}" {{ $employee->emp_job_role == $id ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif
                        </span>
                    </p>

                    <div class="d-flex justify-content-between align-items-center">
                        <!-- Trigger Modal -->
                        <button class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#changePasswordModal{{ $employee->id }}">
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
                            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
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
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@push('scripts')
<script>
$(document).ready(function() {
    console.log('Role management script loaded');
    
    // Function to show toast notifications
    function showToast(type, message) {
        if (typeof Swal !== 'undefined') {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });
            
            Toast.fire({
                icon: type,
                title: message
            });
        } else {
            alert(type.toUpperCase() + ': ' + message);
        }
    }
    
    // Handle edit role button click
    $(document).on('click', '.edit-role', function(e) {
        e.preventDefault();
        console.log('Edit role button clicked');
        
        var $btn = $(this);
        var $container = $btn.closest('.role-display');
        
        // Hide text and button, show select
        $container.find('.role-text, .edit-role').addClass('d-none');
        $container.find('.role-select').removeClass('d-none').focus();
    });
    
    // Handle role change
    $(document).on('change', '.role-select', function() {
        var $select = $(this);
        var employeeId = $select.data('employee-id');
        var newRole = $select.val();
        var $container = $select.closest('.role-display');
        
        console.log('Role changed:', {employeeId, newRole});
        
        // Show loading state
        $select.prop('disabled', true);
        
        // Send AJAX request
        $.ajax({
            url: '{{ route("admin.update.employee.role") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                employee_id: employeeId,
                new_role: newRole
            },
            success: function(response) {
                console.log('Response:', response);
                if (response.success) {
                    // Update the displayed role
                    $container.find('.role-text').text(response.role_name);
                    showToast('success', response.message);
                } else {
                    showToast('error', response.message || 'Failed to update role');
                    // Revert the select to the previous value
                    $select.val($select.data('current-role'));
                }
            },
            error: function(xhr) {
                console.error('Error:', xhr);
                var errorMsg = xhr.responseJSON && xhr.responseJSON.message 
                    ? xhr.responseJSON.message 
                    : 'An error occurred while updating the role.';
                showToast('error', errorMsg);
                // Revert the select to the previous value
                $select.val($select.data('current-role'));
            },
            complete: function() {
                // Reset UI
                $select.prop('disabled', false).addClass('d-none');
                $container.find('.role-text, .edit-role').removeClass('d-none');
                // Update the current role in data attribute
                $select.data('current-role', $select.val());
            }
        });
    });
});
</script>
@endpush

@push('styles')
<style>
.role-display {
    display: inline-flex;
    align-items: center;
}
.role-select {
    max-width: 150px;
    display: inline-block !important;
}
</style>
@endpush

@endsection
