<!DOCTYPE html>
<html>
<head>
    <title>JavaScript Test</title>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap 4 CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.6.0/css/bootstrap.min.css">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
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
</head>
<body>
    <div class="container mt-5">
        <h1>JavaScript Test Page</h1>
        
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title">Test Role Change</h5>
                
                <div class="role-display" data-employee-id="1">
                    <span class="role-text">Agent</span>
                    <button class="btn btn-xs btn-link p-0 ml-1 edit-role">
                        <i class="fas fa-edit">Edit Role</i>
                    </button>
                    <select class="form-control form-control-sm d-none role-select" data-employee-id="1">
                        <option value="1">SuperAdmin</option>
                        <option value="2" selected>Agent</option>
                        <option value="4">HR</option>
                        <option value="5">Accountant</option>
                        <option value="6">Team Leader</option>
                    </select>
                </div>
                
                <div class="mt-3">
                    <button id="test-toast" class="btn btn-primary">Test Toast</button>
                    <button id="test-ajax" class="btn btn-secondary">Test AJAX</button>
                </div>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title">Console</h5>
                <pre id="console" class="bg-light p-3" style="min-height: 100px; max-height: 300px; overflow-y: auto;"></pre>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    
    <script>
    // Simple console.log wrapper that also shows in the page
    function log() {
        const args = Array.from(arguments);
        console.log.apply(console, args);
        
        const message = args.map(arg => 
            typeof arg === 'object' ? JSON.stringify(arg, null, 2) : String(arg)
        ).join(' ');
        
        $('#console').append(message + '\n');
        $('#console').scrollTop($('#console')[0].scrollHeight);
    }
    
    $(document).ready(function() {
        log('Document ready');
        log('jQuery version:', $.fn.jquery);
        log('Bootstrap version:', $.fn.tooltip ? 'Loaded' : 'Not loaded');
        log('SweetAlert2 version:', typeof Swal !== 'undefined' ? 'Loaded' : 'Not loaded');
        
        // Test toast
        $('#test-toast').on('click', function() {
            Swal.fire({
                title: 'Test Toast',
                text: 'This is a test toast notification',
                icon: 'success',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
        });
        
        // Test AJAX
        $('#test-ajax').on('click', function() {
            log('Sending test AJAX request...');
            
            $.ajax({
                url: '{{ route("admin.update.employee.role") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    employee_id: 1,
                    new_role: 2
                },
                success: function(response) {
                    log('AJAX Success:', response);
                },
                error: function(xhr) {
                    log('AJAX Error:', xhr.responseJSON || xhr.statusText);
                }
            });
        });
        
        // Handle edit role button click
        $(document).on('click', '.edit-role', function(e) {
            e.preventDefault();
            log('Edit role button clicked');
            
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
            
            log('Role changed:', {employeeId, newRole});
            
            // Show loading state
            $select.prop('disabled', true);
            
            // In test page, just simulate success after a delay
            setTimeout(function() {
                $container.find('.role-text').text($select.find('option:selected').text());
                $select.prop('disabled', false).addClass('d-none');
                $container.find('.role-text, .edit-role').removeClass('d-none');
                
                Swal.fire({
                    title: 'Success',
                    text: 'Role updated successfully',
                    icon: 'success',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
            }, 1000);
        });
    });
    </script>
</body>
</html>
