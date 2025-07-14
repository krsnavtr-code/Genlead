@extends('main')

@section('title', 'Candidate Interview Result')

@section('content')
<div class="content-wrapper">
    <div class="content-header sty-one text-center my-4">
        <h1 class="text-primary">Candidate Interview Result Page</h1>
    </div>

    <div class="content">
        <div class="card shadow-sm">
            <div class="card-body">
                <!-- <h4 class="text-center mb-4">Candidate Details</h4> -->

                <div class="table-responsive">
    <table class="table table-sm table-bordered align-middle">
        <thead class="table-info">
            <tr class="text-center">
                <th>Name</th>
                <th>Email</th>
                <th>Branch</th>
                <th>Loc</th>
                <th>Phone</th>
                <th>Salary</th>
                <th>Amount</th>
                <th>Result</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($new_employees as $candidate)
            <tr>
                <td class="fw-semibold text-info">{{ $candidate->name }}</td>
                <td style="font-size: 0.875rem;">
                    <span id="email-display-{{ $candidate->id }}">{{ $candidate->email }}</span>
                    <button class="btn btn-link btn-sm p-0 ms-1 edit-email-btn"
                        data-candidate-id="{{ $candidate->id }}"
                        data-email="{{ $candidate->email }}">
                        <i class="fas fa-edit text-primary"></i>
                    </button>
                </td>
                <td>{{ $candidate->branch }}</td>
                <td>{{ $candidate->location }}</td>
                <td>{{ $candidate->phone }}</td>
                <td>{{ $candidate->salary_discussed ? 'Yes' : 'No' }}</td>
                <td>₹{{ $candidate->salary_amount }}</td>
                <td class="text-center">
                    @if(!$candidate->interview_result)
                        <input type="checkbox" class="form-check-input interview-result-checkbox"
                            data-candidate-id="{{ $candidate->id }}"
                            id="interviewCheck{{ $candidate->id }}">
                    @else
                        <span class="badge bg-{{ $candidate->interview_result === 'Selected' ? 'success' : 'danger' }}">
                            {{ Str::limit($candidate->interview_result, 8) }}
                        </span>
                    @endif
                </td>
                <td class="text-center">
                    @if($candidate->interview_result === 'Selected')
                        <button class="btn btn-outline-primary btn-sm resend-email-btn" 
                            data-candidate-id="{{ $candidate->id }}">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                        <small class="text-muted d-block mt-1" style="font-size: 0.7rem;" id="resend-status-{{ $candidate->id }}"></small>
                    @else
                        <span class="text-muted" style="font-size: 0.75rem;">N/A</span>
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

<!-- Modal for Editing Email -->
<div id="editEmailModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Candidate Email</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editEmailForm">
                    <input type="hidden" id="edit-candidate-id">
                    <div class="mb-3">
                        <label for="edit-email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="edit-email" required>
                        <div class="invalid-feedback" id="email-error"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="save-email-btn">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Selecting Interview Result -->
<div id="resultModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Interview Result</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModal()"></button>
            </div>
            <div class="modal-body text-center">
                <p>Please select the result for the candidate:</p>
                <button class="btn btn-success mx-2" onclick="submitResult('Selected')">Selected</button>
                <button class="btn btn-danger mx-2" onclick="submitResult('Rejected')">Rejected</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Add CSRF token for AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Handle resend email button click
    $(document).on('click', '.resend-email-btn', function() {
        const candidateId = $(this).data('candidate-id');
        const $btn = $(this);
        const $status = $('#resend-status-' + candidateId);
        
        // Disable button and show loading state
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Sending...');
        $status.removeClass('text-danger').addClass('text-muted').text('Sending email...');
        
        // Send AJAX request
        $.ajax({
            url: '{{ route("new_joinee.resend_document_email", "") }}/' + candidateId,
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $status.removeClass('text-danger').addClass('text-success')
                        .text('Email sent successfully!');
                } else {
                    $status.removeClass('text-success').addClass('text-danger')
                        .text('Failed to send email.');
                }
            },
            error: function(xhr) {
                const errorMsg = xhr.responseJSON && xhr.responseJSON.message 
                    ? xhr.responseJSON.message 
                    : 'An error occurred while sending the email.';
                $status.removeClass('text-success').addClass('text-danger').text(errorMsg);
            },
            complete: function() {
                // Re-enable button after 3 seconds
                setTimeout(function() {
                    $btn.prop('disabled', false).html(
                        '<i class="fas fa-paper-plane me-1"></i> Resend Document Upload Email'
                    );
                    // Clear status message after 5 seconds
                    setTimeout(function() {
                        $status.text('');
                    }, 5000);
                }, 3000);
            }
        });
    });

    // Handle edit email button clicks
    $(document).on('click', '.edit-email-btn', function() {
        const candidateId = $(this).data('candidate-id');
        const currentEmail = $(this).data('email');
        
        // Show the modal with the current email
        $('#edit-candidate-id').val(candidateId);
        $('#edit-email').val(currentEmail);
        $('#edit-email').removeClass('is-invalid');
        $('#email-error').text('');
        new bootstrap.Modal(document.getElementById('editEmailModal')).show();
    });

    // Handle save email button click
    $('#save-email-btn').click(function() {
        const candidateId = $('#edit-candidate-id').val();
        const newEmail = $('#edit-email').val().trim();
        
        // Basic email validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(newEmail)) {
            $('#edit-email').addClass('is-invalid');
            $('#email-error').text('Please enter a valid email address');
            return;
        }

        // Disable button and show loading state
        const $btn = $(this);
        const originalText = $btn.html();
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');

        // Send AJAX request to update email
        $.ajax({
            url: '{{ route("hrms.update_candidate_email") }}',
            type: 'POST',
            data: {
                candidate_id: candidateId,
                email: newEmail,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                console.log('Response:', response);
                if (response && response.success) {
                    // Update the displayed email
                    $(`#email-display-${candidateId}`).text(newEmail);
                    
                    // Simple and reliable way to close the modal
                    $('#editEmailModal').modal('hide');
                    
                    // Show success message immediately
                    alert('Email updated successfully!');
                } else {
                    const errorMsg = (response && response.message) || 'Failed to update email';
                    console.error('Error in response:', errorMsg, response);
                    showError(errorMsg);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
                console.error('Response Text:', xhr.responseText);
                
                let errorMsg = 'An error occurred while updating the email.';
                
                try {
                    const response = xhr.responseJSON;
                    if (response) {
                        if (response.errors) {
                            // Handle validation errors
                            errorMsg = Object.values(response.errors).flat().join(' ');
                        } else if (response.message) {
                            errorMsg = response.message;
                        }
                    } else if (xhr.responseText) {
                        // Try to parse the response text as JSON
                        const textResponse = JSON.parse(xhr.responseText);
                        errorMsg = textResponse.message || errorMsg;
                    }
                } catch (e) {
                    console.error('Error parsing error response:', e);
                    errorMsg = xhr.responseText || errorMsg;
                }
                
                showError(errorMsg);
            },
            complete: function() {
                $btn.prop('disabled', false).html(originalText);
            }
        });
    });

    function showError(message) {
        $('#edit-email').addClass('is-invalid');
        $('#email-error').text(message);
        // Show a more visible error message
        alert('Error: ' + message);
    }
    
    function showSuccess(message) {
        // You can replace this with a toast notification or other UI element
        alert(message);
    }

    // JS to handle modal and AJAX
    document.querySelectorAll('.interview-result-checkbox').forEach(checkbox => {
        checkbox.addEventListener('click', function () {
            if (this.checked) {
                window.selectedCandidateId = this.getAttribute('data-candidate-id');
                new bootstrap.Modal(document.getElementById('resultModal')).show();
            }
        });
    });

    function closeModal() {
        const modalEl = document.getElementById('resultModal');
        const modal = bootstrap.Modal.getInstance(modalEl);
        modal.hide();
    }

    function submitResult(result) {
        const candidateId = window.selectedCandidateId;

        fetch("{{ route('hrms.submit_interview_result') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ candidate_id: candidateId, result: result })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.success);
                location.reload();
            } else {
                alert(data.error || "Something went wrong!");
            }
        })
        .catch(error => {
            console.error("Error:", error);
        });

        closeModal();
    }
</script>
@endsection
