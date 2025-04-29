@extends('main')

@section('title', 'Add Lead')

@section('content')

<style>
    /* General Styling */
    body {
        font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        color: #333;
    }

    h1, h4 {
        font-weight: 600;
        color: #333;
    }

    label {
        font-size: 14px;
        color: #495057;
        font-family: sans-serif;
        font-weight: 100 !important;
    }

    .form-control {
        font-size: 14px;
        padding: 8px 12px;
        border: 1px solid #ced4da;
        border-radius: 4px;
    }

    .form-control:focus {
        border-color: #80bdff;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.25);
    }

    .form-group {
        margin-bottom: 20px;
    }

    /* Page Layout */
    .content-header h1 {
        font-size: 24px;
        margin-bottom: 20px;
    }

    .breadcrumb {
        padding: 0;
        margin-bottom: 15px;
        background-color: transparent;
        font-size: 14px;
    }

    /* Two-column layout */
    .form-container {
        display: flex;
        flex-wrap: wrap;
    }

    .form-container .form-column {
        flex: 0 0 48%;
        margin-right: 2%;
    }

    .form-container .form-column:nth-child(2n) {
        margin-right: 0;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        padding: 8px 20px;
        font-size: 14px;
        font-weight: 500;
        border-radius: 4px;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #0056b3;
    }

    /* Horizontal Navbar Styles */
    .horizontal-navbar {
        display: flex;
        justify-content: space-around;
        background-color: #f8f9fa;
        padding: 10px 0;
        border-bottom: 1px solid #ddd;
    }

    .horizontal-navbar a {
        color: #007bff;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        padding: 8px 16px;
        border-radius: 4px;
    }

    .horizontal-navbar a:hover {
        background-color: #007bff;
        color: white;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .form-container .form-column {
            flex: 0 0 100%;
            margin-right: 0;
        }
    }
</style>

<div class="content-wrapper">
    <!-- Add Lead Options in Horizontal Navbar -->
    <div class="horizontal-navbar">
        <a href="{{ url('/i-admin/show-leads') }}">Manage Leads</a>
        <a href="{{ url('/admin/activities/create') }}">Add Activities</a>
        <a href="{{ url('/admin/activities') }}">Manage Activities</a>
        <a href="{{ url('/admin/tasks/create') }}">Create/Add Tasks</a>
        <a href="{{ url('/admin/tasks') }}">Manage Tasks</a>
        <a href="{{ url('/i-admin/pending') }}">Pending Payment</a>
    </div>

    <!-- Content Header (Page header) -->
    <div class="content-header sty-one">
        <h1>Add Leads</h1>
        <ol class="breadcrumb">
            <li><a href="">Leads</a></li>
        </ol>
    </div>

     <!-- Display Success Message -->
            @if (session('success'))
          <div class="alert alert-success">
                {{ session('success') }}
         </div>
          @endif

          @if (session('errors'))
          <div class="alert alert-danger">
              <ul>
                  @foreach (session('errors')->all() as $error)
                      <li>{{ $error }}</li>
                  @endforeach
              </ul>
          </div>
         @endif

    <!-- Main content -->
    <div class="content">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('store-lead') }}">
                    @csrf

                    <div class="form-container">
                        <!-- Left Column -->
                        <div class="form-column">
                            <fieldset class="form-group">
                                <label for="first_name">First Name<span class="text-danger">*</span></label>
                                <input class="form-control" id="first_name" name="first_name" type="text" required>
                            </fieldset>

                            <fieldset class="form-group">
                                <label for="phone">Phone<span class="text-danger">*</span></label>
                                <input class="form-control" id="phone" name="phone" type="text" required>
                            </fieldset>

                            <fieldset class="form-group">
                                <label for="secondary_phone">Secondary Phone</label>
                                <input class="form-control" id="secondary_phone" name="secondary_phone" type="text">
                            </fieldset>

                            <fieldset class="form-group">
                                <label for="status">Status<span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-control" required>
                                    @foreach(App\Helpers\SiteHelper::getLeadStatus() as $status)
                                        <option value="{{ $status['code'] }}">{{ $status['name'] }}</option>
                                    @endforeach
                                </select>
                            </fieldset>

                            <fieldset class="form-group">
                                <label for="lead_source">
                                    Lead Source <span class="text-danger">*</span>
                                </label>
                                @php
                                    $lead_source = array('Advertising', 'Social Media', 'Direct Call', 'Employee Referral', 'Web Research', 'Public Relations');
                                @endphp
                                <select class="form-control" id="source" name="lead_source" required>
                                    @foreach ($lead_source as $single)
                                    <option value="{{ $single }}">{{ $single }}</option>
                                    @endforeach
                                </select>
                            </fieldset>

                            <fieldset class="form-group">
                                <label for="college">College<span class="text-danger"></span></label>
                                <input class="form-control" id="college" name="college" type="text">
                            </fieldset>

                            <fieldset class="form-group">
                                <label for="branch">Branch<span class="text-danger"></span></label>
                                <input class="form-control" id="branch" name="branch" type="text">
                            </fieldset>
                        </div>

                        <!-- Right Column -->
                        <div class="form-column">
                            <fieldset class="form-group">
                                <label for="last_name">Last Name<span class="text-danger">*</span></label>
                                <input class="form-control" id="last_name" name="last_name" type="text" required>
                            </fieldset>

                            <fieldset class="form-group">
                                <label for="email">Email<span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input class="form-control" id="email" name="email" type="email" required>
                                    <select class="form-control" name="email_domain" required>
                                        <option value="gmail.com">Gmail</option>
                                        <option value="yahoo.com">Yahoo</option>
                                    </select>
                                </div>
                            </fieldset>

                            <fieldset class="form-group">
                                <label for="secondary_email">Secondary Email</label>
                                <div class="input-group">
                                    <input class="form-control" id="secondary_email" name="secondary_email" type="email">
                                    <select class="form-control" name="secondary_email_domain">
                                        <option value="gmail.com">Gmail</option>
                                        <option value="yahoo.com">Yahoo</option>
                                    </select>
                                </div>
                            </fieldset>

                            <fieldset class="form-group">
                                <label for="university">University<span class="text-danger">*</span></label>
                                <input class="form-control" id="university" name="university" type="text" required>
                            </fieldset>

                            <fieldset class="form-group">
                                <label for="courses">Courses<span class="text-danger">*</span></label>
                                <input class="form-control" id="courses" name="courses" type="text" required>
                            </fieldset>
                            
                            <div class="form-group">
                                <label for="session_duration">Session Duration<span class ="text-danger">*</span></label>
                                <select name="session_duration" id="session_duration" class="form-control" required>
                                    @for ($year = 2016; $year <= 2027; $year++)
                                        <option value="{{ $year }}-{{ $year + 1 }}">{{ $year }}-{{ $year + 1 }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
    <!-- /.content -->
</div>

<script>
    document.querySelector('form').addEventListener('submit', function(event) {
        const email = document.querySelector('#email').value;
        const emailDomain = document.querySelector('select[name="email_domain"]').value;
        if (!email.endsWith(emailDomain)) {
            alert('Please use only Gmail or Yahoo for your email.');
            event.preventDefault();
        }

        const secondaryEmail = document.querySelector('#secondary_email').value;
        const secondaryEmailDomain = document.querySelector('select[name="secondary_email_domain"]').value;
        if (secondaryEmail && !secondaryEmail.endsWith(secondaryEmailDomain)) {
            alert('Please use only Gmail or Yahoo for your secondary email.');
            event.preventDefault();
        }
    });
</script>

@endsection
