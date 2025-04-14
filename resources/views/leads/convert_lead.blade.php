@extends('main') <!-- Extend the main layout -->

@section('title', 'Convert Lead') <!-- Set the page title -->

@section('content')

<style>
    /* General Styling */
    body {
        font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        color: #333;
    }

    label {
        font-size: 16px;
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

    /* Layout for Two-column alignment */
    .form-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
    }

    .form-group {
        flex-basis: 48%;
        margin-bottom: 20px;
    }

    .form-group-full {
        flex-basis: 100%;
    }

    /* Button Styling */
    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        padding: 10px 20px;
        font-size: 14px;
        font-weight: 500;
        border-radius: 4px;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #0056b3;
    }

    /* Responsive Layout */
    @media (max-width: 768px) {
        .form-group {
            flex-basis: 100%;
        }
    }
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header sty-one">
        <h1>Convert Lead</h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/leads') }}">Leads</a></li>
        </ol>
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="card">
            <div class="card-body">
                <form action="{{ url('/admin/leads/convert/'.$lead->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Two-column form layout -->
                    <div class="form-container">

                        <!-- Deal Name -->
                        <div class="form-group">
                            <label for="deal_name">Deal Name</label>
                            <input type="text" class="form-control" id="deal_name" name="deal_name" placeholder="Enter Deal Name" required>
                        </div>

                        <!-- Amount -->
                        <div class="form-group">
                            <label for="amount">Amount</label>
                            <input type="number" class="form-control" id="amount" name="amount" placeholder="Enter Amount">
                        </div>

                        <!-- Stage -->
                        <div class="form-group">
                            <label for="stage">Stage</label>
                            <select class="form-control" id="stage" name="stage" required>
                                <option value="Prospecting">Prospecting</option>
                                <option value="Qualification">Qualification</option>
                                <option value="Proposal">Proposal</option>
                                <option value="Negotiation">Negotiation</option>
                                <option value="Closed Won">Closed Won</option>
                                <option value="Closed Lost">Closed Lost</option>
                            </select>
                        </div>

                        <!-- Contact Phone -->
                        <div class="form-group">
                            <label for="contact_phone">Contact Phone</label>
                            <input type="text" class="form-control" id="contact_phone" name="contact_phone" placeholder="Enter Contact Phone" required>
                        </div>

                        <!-- Contact Email -->
                        <div class="form-group">
                            <label for="contact_email">Contact Email</label>
                            <input type="email" class="form-control" id="contact_email" name="contact_email" placeholder="Enter Contact Email" required>
                        </div>

                        <!-- Lead Source -->
                        <div class="form-group">
                            <label for="lead_source">Lead Source</label>
                            <select class="form-control" id="lead_source" name="lead_source" required>
                                <option value="Website">Website</option>
                                <option value="Referral">Referral</option>
                                <option value="Social Media">Social Media</option>
                                <option value="Advertisement">Advertisement</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <!-- Referred By -->
                        <div class="form-group">
                            <label for="referral_name">Referred By</label>
                            <input type="text" class="form-control" id="referral_name" name="referral_name" placeholder="Enter Name of Referrer">
                        </div>

                        <!-- Priority Level -->
                        <div class="form-group">
                            <label for="priority">Priority Level</label>
                            <select class="form-control" id="priority" name="priority">
                                <option value="High">High</option>
                                <option value="Medium">Medium</option>
                                <option value="Low">Low</option>
                            </select>
                        </div>

                        <!-- Estimated Close Date -->
                        <div class="form-group">
                            <label for="estimated_close_date">Estimated Close Date</label>
                            <input type="date" class="form-control" id="estimated_close_date" name="estimated_close_date">
                        </div>

                        <!-- Lead Status -->
                        <div class="form-group">
                            <label for="lead_status">Lead Status</label>
                            <select class="form-control" id="lead_status" name="lead_status" required>
                                <option value="New">New</option>
                                <option value="Contacted">Contacted</option>
                                <option value="Qualified">Qualified</option>
                                <option value="Proposal Sent">Proposal Sent</option>
                                <option value="Negotiation">Negotiation</option>
                                <option value="Closed Won">Closed Won</option>
                                <option value="Closed Lost">Closed Lost</option>
                            </select>
                        </div>

                        <!-- Notes/Comments -->
                        <div class="form-group-full">
                            <label for="notes">Notes/Comments</label>
                            <textarea class="form-control" id="notes" name="notes" rows="4" placeholder="Enter any notes or comments"></textarea><br>
                        </div>

                        <!-- Upload Attachments -->
                        <div class="form-group-full">
                            <label for="attachments">Upload Attachments</label>
                            <input type="file" class="form-control" id="attachments" name="attachments[]" multiple><br>
                        </div>

                        <!-- Follow-up Reminder -->
                        <div class="form-group-full">
                            <label for="follow_up">Follow-up Reminder</label>
                            <input type="datetime-local" class="form-control" id="follow_up" name="follow_up">
                        </div>

                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary mt-3">Convert Lead</button>
                </form>
            </div>
        </div>
    </div>
    <!-- /.content -->
</div>

@endsection
