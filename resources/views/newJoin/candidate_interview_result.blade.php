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
                <h4 class="text-center mb-4">Candidate Details</h4>

                <div class="row g-4">
                    @foreach($new_employees as $candidate)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 border-start border-4 border-info shadow-sm" style="background-color: #f8f9fa;">
                            <div class="card-body">
                                <h5 class="card-title fw-bold text-info">{{ $candidate->name }}</h5>
                                <br>
                                <p class="mb-1"><strong>Email:</strong> {{ $candidate->email }}</p>
                                <p class="mb-1"><strong>Branch:</strong> {{ $candidate->branch }}</p>
                                <p class="mb-1"><strong>Location:</strong> {{ $candidate->location }}</p>
                                <p class="mb-1"><strong>Phone:</strong> {{ $candidate->phone }}</p>
                                <p class="mb-1"><strong>Salary Discussed:</strong> {{ $candidate->salary_discussed ? 'Yes' : 'No' }}</p>
                                <p class="mb-2"><strong>Salary Amount:</strong> ₹{{ $candidate->salary_amount }}</p>

                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    @if(!$candidate->interview_result)
                                        <div>
                                            <input type="checkbox" class="form-check-input interview-result-checkbox"
                                                data-candidate-id="{{ $candidate->id }}" id="interviewCheck{{ $candidate->id }}">
                                            <label for="interviewCheck{{ $candidate->id }}">Mark Result</label>
                                        </div>
                                    @else
                                        <span class="badge bg-{{ $candidate->interview_result === 'Selected' ? 'success' : 'danger' }}">
                                            {{ $candidate->interview_result }}
                                        </span>
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
