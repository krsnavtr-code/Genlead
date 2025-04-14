@extends('main')

@section('title', 'Create Task')

@section('content')

<style>
      label {
        font-size: 17px;
        color: #495057;
        font-family: sans-serif;
        font-weight: 100 !important;
        
    }
      /* Horizontal Navbar Styles */
      .horizontal-navbar {
        display: flex;
        justify-content: space-around;
        background-color: #f8f9fa;
        padding: 17px 0;
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
</style>
<div class="content-wrapper">
    
    <div class="horizontal-navbar">
        <a href="{{ url('/i-admin/show-leads') }}">Manage Leads</a>
        <a href="{{ url('/admin/activities/create') }}">Add Activities</a>
        <a href="{{ url('/admin/activities') }}">Manage Activities</a>
        <a href="{{ url('/admin/tasks/create') }}">Create/Add Tasks</a>
        <a href="{{ url('/admin/tasks') }}">Manage Tasks</a>
        <a href="{{ url('/i-admin/pending') }}">Pending Payment</a>
    </div>
    <!-- Content Header (Page header) -->
    <div class="content-header sty-one d-flex justify-content-between align-items-center">
        <div>
            <h1>Create task</h1>
        </div>
        <div>
            <a href="{{ url('/i-admin/leads/add-lead') }}" 
            class="btn btn-danger btn-sm" 
            style=" color: white; padding: 5px 20px; font-size: 16px; font-weight: bold; border-radius: 4px; text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">
         
            <!-- + Mark -->
            <span style="font-size: 20px; font-weight: bold; margin-right: 8px;">&#43;</span>
         
            <!-- Button Text -->
            Add Lead
         </a>
        </div>
    </div>

    <div class="content">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('tasks.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="subject">Subject</label>
                        <input type="text" class="form-control" id="subject" name="subject" required>
                    </div>

                    <div class="form-group">
                        <label for="task_type">Task Type</label>
                        <select class="form-control" id="task_type" name="task_type" required>
                            <option value="follow_up">Follow-Up</option>
                            <option value="payment_failure">Payment Failure</option>
                            <option value="follow_up">Document Pending</option>
                            <option value="payment_failure">Video Meeting</option>
                            <option value="follow_up">Inbound missed call</option>
                            <option value="payment_failure">Website Visit</option>
                            <!-- Add more task types as needed -->
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="task_status">Task Status</label>
                        <select class="form-control" id="task_status" name="task_status" required>
                            <option value="open">All Status</option>
                            <option value="overdue">Overdue</option>
                            <option value="overdue">Pending</option>
                            <option value="overdue">Overdue and Pending</option>
                            <option value="overdue">Completed</option>
                            <option value="overdue">Cancelled</option>
                            <!-- Add more statuses as needed -->
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="schedule_from">Schedule From</label>
                        <input type="datetime-local" class="form-control" id="schedule_from" name="schedule_from" required>
                    </div>

                    <div class="form-group">
                        <label for="schedule_to">Schedule To</label>
                        <input type="datetime-local" class="form-control" id="schedule_to" name="schedule_to" required>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="4" placeholder="Enter Description"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Create Task</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
