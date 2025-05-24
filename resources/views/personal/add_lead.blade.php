@extends('main')

@section('title', 'Add Lead')

@section('content')

@php
    $emp_job_role = session()->get('emp_job_role');
@endphp

<style>
    .lead-form-select{
        width: 100%;
        max-width: 500px;
        display: inline-block;
        padding: 8px;
        border-radius: 5px;
        border: 1px solid #ccc;
        font-size: 14px;
        font-weight: 500;
        color: #495057;
        text-transform: uppercase;
    }
</style>

<div class="content-wrapper">
    @include('navbar')

    <div class="container">
        <div class="text-center mb-4">
            <h1 class="fw-bold">Add Leads</h1>
        </div>

        <!-- Flash Messages -->
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('errors'))
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach (session('errors')->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Lead Form -->
        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" action="{{ route('store-lead') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="first_name" class="form-label">First Name<span class="text-danger">*</span></label>
                            <input type="text" id="first_name" name="first_name" class="form-control" required>

                            <label for="last_name" class="form-label mt-3">Last Name<span class="text-danger">*</span></label>
                            <input type="text" id="last_name" name="last_name" class="form-control" required>

                            <label for="phone" class="form-label mt-3">Phone<span class="text-danger">*</span></label>
                            <input type="text" id="phone" name="phone" class="form-control" required>

                            <label for="secondary_phone" class="form-label mt-3">Secondary Phone</label>
                            <input type="text" id="secondary_phone" name="secondary_phone" class="form-control">

                            <label for="email" class="form-label mt-3">Email<span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="email" id="email" name="email" class="form-control" required>
                                <select class="form-select" style="border-radius: 5px; border: 1px solid #ccc;" name="email_domain" required>
                                    <option value="gmail.com">Gmail</option>
                                    <option value="yahoo.com">Yahoo</option>
                                </select>
                            </div>

                            <label for="secondary_email" class="form-label mt-3">Secondary Email</label>
                            <div class="input-group">
                                <input type="email" id="secondary_email" name="secondary_email" class="form-control">
                                <select class="form-select" style="border-radius: 5px; border: 1px solid #ccc;" name="secondary_email_domain">
                                    <option value="gmail.com">Gmail</option>
                                    <option value="yahoo.com">Yahoo</option>
                                </select>
                            </div>

                            <label for="status" class="form-label mt-3">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select lead-form-select" required>
                                @foreach(App\Helpers\SiteHelper::getLeadStatus() as $leadType => $categories)
                                    <optgroup label="{{ $leadType }}">
                                        @foreach($categories as $category)
                                            <optgroup label="&nbsp;&nbsp;→ {{ $category['category'] }}">
                                                @foreach($category['subcategories'] as $subcategory)
                                                    <option value="{{ $subcategory['code'] }}">
                                                        &nbsp;&nbsp;&nbsp;&nbsp;{{ $subcategory['name'] }}
                                                    </option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>

                            <label for="lead_source" class="form-label mt-3">Lead Source<span class="text-danger">*</span></label>
                            @php
                                $lead_source = ['Advertising', 'Social Media', 'Direct Call', 'Employee Referral', 'Web Research', 'Public Relations'];
                            @endphp
                            <select class="form-select lead-form-select" id="source" name="lead_source" required>
                                @foreach ($lead_source as $single)
                                    <option value="{{ $single }}">{{ $single }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="university" class="form-label">University<span class="text-danger">*</span></label>
                            <input type="text" id="university" name="university" class="form-control" required>

                            <label for="college" class="form-label mt-3">College</label>
                            <input type="text" id="college" name="college" class="form-control">

                            <label for="courses" class="form-label mt-3">Courses<span class="text-danger">*</span></label>
                            <select name="courses" id="courses" class="form-select lead-form-select" required>
                                <option value="">Select Course</option>
                                <option value="MBA">MBA</option>
                                <option value="MCA">MCA</option>
                                <option value="M.Com">M.Com</option>
                                <option value="M.Sc">M.Sc</option>
                                <option value="BBA">BBA</option>
                                <option value="BCA">BCA</option>
                                <option value="B.Sc">B.Sc</option>
                                <option value="B.Com">B.Com</option>
                                <option value="B.Tech">Software</option>
                                <option value="M.Tech">Other</option>
                            </select>

                            <label for="branch" class="form-label mt-3">Branch</label>
                            <input type="text" id="branch" name="branch" class="form-control">

                            <label for="session_duration" class="form-label mt-3">Session Duration<span class="text-danger">*</span></label>
                            <select name="session_duration" id="session_duration" class="form-select lead-form-select" required>
                                @for ($year = 2016; $year <= 2027; $year++)
                                    <option value="{{ $year }}-{{ $year + 1 }}">{{ $year }}-{{ $year + 1 }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <div class="mt-4 text-center">
                        <button type="submit" class="btn btn-success px-4">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Email Domain Validation -->
<script>
    document.querySelector('form').addEventListener('submit', function(event) {
        const email = document.querySelector('#email').value;
        const emailDomain = document.querySelector('select[name="email_domain"]').value;
        if (!email.endsWith(emailDomain)) {
            alert('Primary email must match the selected domain.');
            event.preventDefault();
        }

        const secondaryEmail = document.querySelector('#secondary_email').value;
        const secondaryEmailDomain = document.querySelector('select[name="secondary_email_domain"]').value;
        if (secondaryEmail && !secondaryEmail.endsWith(secondaryEmailDomain)) {
            alert('Secondary email must match the selected domain.');
            event.preventDefault();
        }
    });
</script>

@endsection
