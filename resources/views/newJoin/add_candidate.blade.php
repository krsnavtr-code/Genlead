@extends('main')

@section('title', 'Add New Candidate')

@section('content')
<div class="content-wrapper">
      <!-- Back Button -->
      <a href="/admin/hrms/manage-employees" class="btn btn-secondary">Back</a>

    <div class="content-header sty-one">
        <h1>Add New Candidate</h1>
    </div>

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
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>{{ session('success') }}</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="content">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('new-join.send-welcome-link') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="name">Candidate Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Candidate Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <div class="form-group">
                        <label for="phone">Candidate Phone</label>
                        <input type="tel" class="form-control" id="phone" name="phone" required>
                    </div>

                    <div class="form-group">
                        <label for="branch">Candidate Branch</label>
                        <input type="text" class="form-control" id="branch" name="branch" required>
                    </div>

                    <div class="form-group">
                        <label for="location">Candidate Location</label>
                        <input type="text" class="form-control" id="location" name="location" required>
                    </div>

                    <div class="form-group">
                        <label for="salary_discussed">Salary Discussed</label>
                        <select class="form-control" id="salary_discussed" name="salary_discussed" required onchange="toggleSalaryAmount()">
                            <option value="No">No</option>
                            <option value="Yes">Yes</option>
                        </select>
                    </div>

                    <div class="form-group" id="salary_amount_group" style="display: none;">
                        <label for="salary_amount">Package Amount</label>
                        <input type="number" class="form-control" id="salary_amount" name="salary_amount">
                    </div>

                    <div class="form-group">
                        <label for="resume">Resume</label>
                        <input type="file" class="form-control-file" id="resume" name="resume" required>
                    </div>

                    <div class="form-group">
                        <label for="interview_process">Interview Process</label><br>
                        <input type="radio" id="interview_online" name="interview_process" value="online" required>
                        <label for="interview_online">Online</label><br>
                        <input type="radio" id="interview_offline" name="interview_process" value="offline" required>
                        <label for="interview_offline">Offline</label>
                    </div>

                    <div class="form-group" style="width: 20%;">
                        <label for="interview_date_time">Interview Date & Time</label>
                        <input type="datetime-local" class="form-control" id="interview_date_time" name="interview_date_time" required min="{{ now()->format('Y-m-d\TH:i') }}"> <!-- Laravel-generated minimum date -->
                    </div>

                    <button type="submit" class="btn btn-primary">Send URL LINK</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function toggleSalaryAmount() {
    var salaryDiscussed = document.getElementById('salary_discussed').value;
    var salaryAmountGroup = document.getElementById('salary_amount_group');
    if (salaryDiscussed === 'Yes') {
        salaryAmountGroup.style.display = 'block';
    } else {
        salaryAmountGroup.style.display = 'none';
    }
}
</script>

@endsection
