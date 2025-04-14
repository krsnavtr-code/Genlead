@extends('main')

@section('title', 'Candidate Interview Result')

@section('content')
<div class="content-wrapper">
    <div class="content-header sty-one">
        <h1 style="color:blue;text-align:center;">Candidate Interview Result Page</h1>
    </div>

    <div class="content">
        <div class="card">
            <div class="card-body">
                <h4>Candidate Details:</h4>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th> Name</th>
                                <th> Email</th>
                                <th> Branch</th>
                                <th>Location</th>
                                <th>Phone</th>
                                <th>Salary Discussed</th>
                                <th>Salary Amount</th>
                                <th>Interview Result</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($new_employees as $candidate)
                            <tr>
                                <td>{{ $candidate->name }}</td>
                                <td>{{ $candidate->email }}</td>
                                <td>{{ $candidate->branch }}</td>
                                <td>{{ $candidate->location }}</td>
                                <td>{{ $candidate->phone }}</td>
                                <td>{{ $candidate->salary_discussed == 1 ? 'Yes' : 'No'  }}</td>
                                <td>{{ $candidate->salary_amount }}</td>
                                <td>
                                    @if(!$candidate->interview_result)
                                    <input type="checkbox" class="interview-result-checkbox" data-candidate-id="{{ $candidate->id }}">
                                    @else
                                    <span class="badge badge-{{ $candidate->interview_result === 'Selected' ? 'success' : 'danger' }}">
                                        {{ $candidate->interview_result }}
                                    </span>
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

<!-- Modal for Selected/Rejected -->
<div id="resultModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Interview Result</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Please select the result for the candidate:</p>
                <button class="btn btn-success" onclick="submitResult('Selected')">Selected</button>
                <button class="btn btn-danger" onclick="submitResult('Rejected')">Rejected</button>
            </div>
        </div>
    </div>
</div>

<script>
    // JavaScript to handle the modal and AJAX request
    document.querySelectorAll('.interview-result-checkbox').forEach(checkbox => {
        checkbox.addEventListener('click', function() {
            if (this.checked) {
                // Store the candidate ID for further use
                window.selectedCandidateId = this.getAttribute('data-candidate-id');
                openModal();
            }
        });
    });

    function openModal() {
        document.getElementById('resultModal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('resultModal').style.display = 'none';
    }

    function submitResult(result) {
        const candidateId = window.selectedCandidateId;

        // Make an AJAX POST request to update the interview result
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
                location.reload(); // Reload the page to see the updated status
            } else {
                alert(data.error);
            }
        })
        .catch(error => {
            console.error("Error:", error);
        });

        closeModal();
    }
</script>
@endsection
