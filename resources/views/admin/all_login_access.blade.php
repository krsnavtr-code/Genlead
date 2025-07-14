@extends('main')

@section('title', 'Employees Details')

@section('content')

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">All Login Access</h3>
        <div class="d-flex">
            <form action="{{ url()->current() }}" method="GET" class="d-flex">
                <select name="role" class="form-select p-2 me-2" style="border-radius: 10px; border-color: blue;" onchange="this.form.submit()">
                    <option value="">All Roles</option>
                    <option value="1" {{ request('role') == '1' ? 'selected' : '' }}>SuperAdmin</option>
                    <option value="2" {{ request('role') == '2' ? 'selected' : '' }}>Agent</option>
                    <option value="4" {{ request('role') == '4' ? 'selected' : '' }}>HR</option>
                    <option value="5" {{ request('role') == '5' ? 'selected' : '' }}>Accountant</option>
                    <option value="6" {{ request('role') == '6' ? 'selected' : '' }}>Team Leader</option>
                    <option value="7" {{ request('role') == '7' ? 'selected' : '' }}>Chain Team Agent</option>
                    <option value="8" {{ request('role') == '8' ? 'selected' : '' }}>ChildAdmin</option>
                </select>
                
                <select name="status" class="form-select p-2 me-2" style="border-radius: 10px; border-color: blue; margin:0px 10px ;" onchange="this.form.submit()">
                    <option value="">All Status</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                </select>
                
                @if(request()->has('role') || request()->has('status'))
                    <a style="border-radius: 10px; border-color: blue;" href="{{ url()->current() }}" class="btn btn-outline-secondary">Clear All</a>
                @endif
            </form>
        </div>
    </div>

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

 <div class="table-responsive">
    <table class="table table-bordered table-sm text-sm align-middle" style="font-size: 0.85rem;">
        <thead class="table-primary">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Username</th>
                <th>Password</th>
                <th>Role</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($employees as $employee)
            <tr>
                <td>{{ $employee->id }}</td>
                <td>{{ $employee->emp_name }}</td>
                <td>{{ $employee->emp_email }}</td>
                <td>
                    <span class="username-display" data-employee-id="{{ $employee->id }}">
                        <span class="username-text">{{ $employee->emp_username }}</span>
                        @if(in_array(session('emp_job_role'), [1, 8]))
                        <button class="btn btn-xs btn-link p-0 ml-1 edit-username"
                            data-employee-id="{{ $employee->id }}"
                            data-current-username="{{ $employee->emp_username }}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <div class="input-group input-group-sm d-none username-edit mt-1" style="max-width: 180px;" data-employee-id="{{ $employee->id }}">
                            <input type="text" class="form-control form-control-sm username-input"
                                value="{{ $employee->emp_username }}">
                            <div class="input-group-append">
                                <button class="btn btn-success btn-sm save-username" type="button">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button class="btn btn-danger btn-sm cancel-username" type="button">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        @endif
                    </span>
                </td>
                <td>
                    @if ($employee->emp_job_role == 1)
                        Hidden
                    @else
                        {{ $employee->emp_password }}
                    @endif
                </td>
                <td>
                    @php
                        $roles = [
                            1 => 'SuperAdmin',
                            2 => 'Agent',
                            4 => 'HR',
                            5 => 'Accountant',
                            6 => 'Team Leader',
                            7 => 'Chain Team Agent',
                            8 => 'ChildAdmin',
                        ];
                        $currentRole = $roles[$employee->emp_job_role] ?? 'Unknown Role';
                    @endphp
                    <span class="role-display" data-employee-id="{{ $employee->id }}">
                        <span class="role-text">{{ $currentRole }}</span>
                        @if(in_array(session('emp_job_role'), [1, 8]))
                        <button class="btn btn-xs btn-link p-0 ml-1 edit-role"
                            data-employee-id="{{ $employee->id }}"
                            data-current-role="{{ $employee->emp_job_role }}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <select class="form-control form-control-sm d-none role-select mt-1"
                            data-employee-id="{{ $employee->id }}">
                            @foreach($roles as $id => $name)
                            <option value="{{ $id }}" {{ $employee->emp_job_role == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                            @endforeach
                        </select>
                        @endif
                    </span>
                </td>
                <td>
                    <div class="form-check form-switch d-inline-block">
                        <input type="checkbox" class="form-check-input toggle-login-access"
                            data-employee-id="{{ $employee->id }}"
                            id="toggle-{{ $employee->id }}"
                            {{ $employee->is_active ? 'checked' : '' }}>
                        <label class="form-check-label" for="toggle-{{ $employee->id }}">
                            {{ $employee->is_active ? 'Active' : 'Inactive' }}
                        </label>
                    </div>
                    <div class="spinner-border spinner-border-sm text-primary d-none" id="spinner-{{ $employee->id }}" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </td>
                <td>
                    <button class="btn btn-outline-primary btn-sm py-0 px-2" data-toggle="modal" data-target="#changePasswordModal{{ $employee->id }}">
                        Change Password
                    </button>
                </td>
            </tr>

            <!-- Modal -->
            <div class="modal fade" id="changePasswordModal{{ $employee->id }}" tabindex="-1" aria-labelledby="changePasswordModalLabel{{ $employee->id }}" aria-hidden="true">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <form action="{{ url('/admin/change-employee-password') }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title" id="changePasswordModalLabel{{ $employee->id }}">Change Password</h5>
                                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                                <div class="mb-2">
                                    <label class="form-label">New Password</label>
                                    <input type="text" name="new_password" class="form-control form-control-sm" required>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">Confirm Password</label>
                                    <input type="password" name="new_password_confirmation" class="form-control form-control-sm" required>
                                </div>
                            </div>
                            <div class="modal-footer py-2">
                                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary btn-sm">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </tbody>
    </table>
</div>


</div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    console.log('Role management script loaded');
    
    // Toggle login access
    $('.toggle-login-access').on('change', function() {
        const employeeId = $(this).data('employee-id');
        const isActive = $(this).is(':checked');
        const toggleSwitch = $(this);
        const label = toggleSwitch.siblings('label');
        const spinner = $(`#spinner-${employeeId}`);
        
        // Show loading state
        toggleSwitch.prop('disabled', true);
        spinner.removeClass('d-none');
        
        // Get CSRF token from meta tag
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Build the URL directly to avoid Blade syntax issues
        const baseUrl = '/admin/employees';
        const url = `${baseUrl}/${employeeId}/toggle-login-access`;
        
        console.log('Sending request to:', url);
        console.log('CSRF Token:', token);
        
        // Send AJAX request to toggle login access
        $.ajax({
            url: url,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            data: {
                _token: token
            },
            xhrFields: {
                withCredentials: true
            },
            success: function(response) {
                console.log('Success response:', response);
                if (response.success) {
                    // Update UI
                    label.text(response.is_active ? 'Active' : 'Inactive');
                    
                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end'
                    });
                } else {
                    // Revert the toggle if there was an error
                    toggleSwitch.prop('checked', !isActive);
                    
                    // Log the error
                    console.error('Error updating login access:', response);
                    
                    // Show error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Failed to update login access. Check console for details.',
                        timer: 3000,
                        showConfirmButton: true
                    });
                }
            },
            error: function(xhr, status, error) {
                // Revert the toggle on error
                toggleSwitch.prop('checked', !isActive);
                
                // Log the error
                console.error('AJAX Error:', {
                    status: xhr.status,
                    statusText: xhr.statusText,
                    responseText: xhr.responseText,
                    error: error
                });
                
                // Show error message
                let errorMessage = 'An error occurred while updating login access. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.status === 403) {
                    errorMessage = 'You do not have permission to perform this action.';
                } else if (xhr.status === 404) {
                    errorMessage = 'Employee not found.';
                } else if (xhr.status >= 500) {
                    errorMessage = 'A server error occurred. Please try again later.';
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMessage,
                    timer: 4000,
                    showConfirmButton: true
                });
            },
            complete: function() {
                // Re-enable the toggle switch and hide spinner
                toggleSwitch.prop('disabled', false);
                spinner.addClass('d-none');
            }
        });
    });
    
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
        } else {
            alert(type.toUpperCase() + ': ' + message);
        }
    }
    
    // Username editing functionality
    $(document).on('click', '.edit-username', function() {
        const employeeId = $(this).data('employee-id');
        const $display = $(`.username-display[data-employee-id="${employeeId}"]`);
        const $edit = $(`.username-edit[data-employee-id="${employeeId}"]`);
        
        $display.find('.username-text, .edit-username').addClass('d-none');
        $edit.removeClass('d-none').find('input').focus();
    });
    
    $(document).on('click', '.cancel-username', function() {
        const $edit = $(this).closest('.username-edit');
        const employeeId = $edit.data('employee-id');
        const originalUsername = $(`.edit-username[data-employee-id="${employeeId}"]`).data('current-username');
        
        $edit.addClass('d-none');
        $(`.username-display[data-employee-id="${employeeId}"] .username-text, 
           .username-display[data-employee-id="${employeeId}"] .edit-username`)
           .removeClass('d-none');
        $edit.find('input').val(originalUsername);
    });
    
    $(document).on('click', '.save-username', function() {
        const $edit = $(this).closest('.username-edit');
        const employeeId = $edit.data('employee-id');
        const newUsername = $edit.find('input').val().trim();
        const $display = $(`.username-display[data-employee-id="${employeeId}"]`);
        
        if (!newUsername) {
            showToast('error', 'Username cannot be empty');
            return;
        }
        
        // Show loading state
        const $saveBtn = $(this);
        const originalText = $saveBtn.html();
        $saveBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
        
        // Send AJAX request to update username
        $.ajax({
            url: `/admin/employees/${employeeId}/update-username`,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                username: newUsername
            },
            success: function(response) {
                if (response.success) {
                    // Update UI
                    $display.find('.username-text').text(newUsername);
                    $display.find('.edit-username').data('current-username', newUsername);
                    
                    // Hide edit form
                    $edit.addClass('d-none');
                    $display.find('.username-text, .edit-username').removeClass('d-none');
                    
                    showToast('success', 'Username updated successfully');
                } else {
                    showToast('error', response.message || 'Failed to update username');
                }
            },
            error: function(xhr) {
                const errorMessage = xhr.responseJSON?.message || 'An error occurred while updating username';
                showToast('error', errorMessage);
            },
            complete: function() {
                $saveBtn.prop('disabled', false).html(originalText);
            }
        });
    });
    
    // Allow pressing Enter to save
    $(document).on('keypress', '.username-input', function(e) {
        if (e.which === 13) { // Enter key
            $(this).closest('.username-edit').find('.save-username').click();
        }
    });
    
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
