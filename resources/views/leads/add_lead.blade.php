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

    .form-group {
        margin-bottom: 20px;
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

    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
        padding: 8px 20px;
        font-size: 14px;
        font-weight: 500;
        border-radius: 4px;
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
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>{{ session('success') }}</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <!-- Content Header (Page header) -->
    <div class="content-header sty-one">
        <h1>Add Leads</h1>
        <ol class="breadcrumb">
            <li><a href="">Leads</a></li>
        </ol>
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ url('/admin/leads/add-lead') }}">
                    @csrf

                    <div class="form-container">
                        <!-- Left Column -->
                        <div class="form-column">
                            <fieldset class="form-group">
                                <label for="first_name">First Name<span class="text-danger">*</span></label>
                                <input class="form-control" id="first_name" name="first_name" type="text" required>
                            </fieldset>

                            <fieldset class="form-group">
                                <label for="title">Title<span class="text-danger">*</span></label>
                                <input class="form-control" id="title" name="title" type="text">
                            </fieldset>

                            <fieldset class="form-group">
                                <label for="phone">Phone<span class="text-danger">*</span></label>
                                <input class="form-control" id="phone" name="phone" type="text" required>
                            </fieldset>

                            <fieldset class="form-group">
                                <label for="lead_source">Lead Source</label>
                                @php
                                    $lead_source = array('Advertising','Social Media','Direct Call','Employee Referral','Web Research','Public Relations');
                                @endphp
                                <select class="form-control" id="source" name="lead_source">
                                    @foreach ($lead_source as $single)
                                    <option value="{{ $single }}">{{ $single }}</option>
                                    @endforeach
                                </select>
                            </fieldset>

                                <!-- New University Field -->
                                <fieldset class="form-group">
                                    <label for="university">University<span class="text-danger">*</span></label>
                                    <input class="form-control" id="university" name="university" type="text" required>
                                </fieldset>
                        </div>

                        <!-- Right Column -->
                        <div class="form-column">
                            <fieldset class="form-group">
                                <label for="last_name">Last Name<span class="text-danger">*</span></label>
                                <input class="form-control" id="last_name" name="last_name" type="text" required>
                            </fieldset>

                            <fieldset class="form-group">
                                <label for="company">Company<span class="text-danger">*</span></label>
                                <input class="form-control" id="company" name="company" type="text" required>
                            </fieldset>

                            <fieldset class="form-group">
                                <label for="email">Email</label>
                                <input class="form-control" id="email" name="email" type="email" required>
                            </fieldset>

                            <fieldset class="form-group">
                                <label for="lead_status">Lead Status</label>
                                <select class="form-control" id="status" name="lead_status">
                                    <option value="new">New</option>
                                    <option value="contacted">Contacted</option>
                                    <option value="contacted">Not Contacted</option>
                                    <option value="qualified">Qualified</option>
                                    <option value="qualified">Not Qualified</option>
                                    <option value="qualified">Contact in Future</option>
                                    <option value="lost">Lost</option>
                                    <option value="closed">Closed</option>
                                </select>
                            </fieldset>

                               <!-- New Courses Field -->
                               <fieldset class="form-group">
                                <label for="courses">Courses<span class="text-danger">*</span></label>
                                <input class="form-control" id="courses" name="courses" type="text" required>
                            </fieldset>

                        </div>
                    </div>

                    <!-- Address Information -->
                    <h4 class="text-black">Address Information:</h4>
                    <div class="form-container">
                        <div class="form-column">
                            <fieldset class="form-group">
                                <label for="street">Street</label>
                                <input class="form-control" id="street" name="street" type="text">
                            </fieldset>

                            <fieldset class="form-group">
                                <label for="state">State</label>
                                <input class="form-control" id="state" name="state" type="text">
                            </fieldset>

                            <fieldset class="form-group">
                                <label for="country">Country</label>
                                <input class="form-control" id="country" name="country" type="text">
                            </fieldset>
                        </div>

                        <div class="form-column">
                            <fieldset class="form-group">
                                <label for="city">City</label>
                                <input class="form-control" id="city" name="city" type="text">
                            </fieldset>

                            <fieldset class="form-group">
                                <label for="zip">Zip Code</label>
                                <input class="form-control" id="zip" name="zip_code" type="text">
                            </fieldset>    
                        </div>
                    </div>

                    <!-- Description Information -->
                    <h4 class="text-black">Description Information:</h4>
                    <fieldset class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="4"></textarea>
                    </fieldset>

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
    <!-- /.content -->
</div>

@endsection
