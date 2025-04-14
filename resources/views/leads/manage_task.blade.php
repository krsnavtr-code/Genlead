@extends('main')

@section('title', 'Manage Tasks')

@section('content')

<style>
      th,td {
        font-size: 16px;
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
        <h1>Manage Tasks</h1>
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
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <!-- Search and Filter Form -->
                    <form method="GET" action="{{ route('tasks.index') }}" class="d-flex">
                        <!-- Search Field -->
                        <input type="text" name="search" class="form-control" placeholder="Search Tasks" style="width: 200px; display: inline-block;" value="{{ request()->search }}">
                        <button type="submit" class="btn btn-secondary" style="margin-left: -30px;"><i class="fa fa-search"></i></button>
                    
                        <!-- Type Filter -->
                        <select name="task_type" class="form-control ml-3" style="width: 150px;" onchange="this.form.submit()">
                            <option value="all" {{ request()->task_type == 'all' ? 'selected' : '' }}>All Types</option>
                            <option value="follow_up" {{ request()->task_type == 'follow_up' ? 'selected' : '' }}>Follow-Up</option>
                            <option value="payment_failure" {{ request()->task_type == 'payment_failure' ? 'selected' : '' }}>Payment Failure</option>
                            <!-- Add more filters as needed -->
                        </select>

                        <!-- Status Filter -->
                        <select name="task_status" class="form-control ml-3" style="width: 150px;" onchange="this.form.submit()">
                            <option value="all" {{ request()->task_status == 'all' ? 'selected' : '' }}>All Status</option>
                            <option value="overdue" {{ request()->task_status == 'overdue' ? 'selected' : '' }}>Overdue</option>
                            <option value="pending" {{ request()->task_status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="completed" {{ request()->task_status == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>

                        <!-- Task Owner Filter -->
                        <input type="text" name="task_owner" class="form-control ml-3" placeholder="Task Owner" style="width: 150px;" value="{{ request()->task_owner }}">
                    </form>
                    
                    <div>
                        <a href="{{ route('tasks.create') }}" class="btn btn-primary">Create Task</a>
                    </div>
                </div>

                {{-- <!-- Date Filters -->
                <div class="d-flex justify-content-end align-items-center mb-3">
                    <div class="btn-group" role="group">
                        <a href="{{ route('tasks.index', ['due_date' => 'today']) }}" class="btn btn-outline-secondary {{ request()->due_date == 'today' ? 'active' : '' }}">Today</a>
                        <a href="{{ route('tasks.index', ['due_date' => 'this_week']) }}" class="btn btn-outline-secondary {{ request()->due_date == 'this_week' ? 'active' : '' }}">This Week</a>
                        <a href="{{ route('tasks.index', ['due_date' => 'this_month']) }}" class="btn btn-outline-secondary {{ request()->due_date == 'this_month' ? 'active' : '' }}">This Month</a>
                        <a href="{{ route('tasks.index', ['due_date' => 'overdue']) }}" class="btn btn-outline-secondary {{ request()->due_date == 'overdue' ? 'active' : '' }}">Overdue</a>
                    </div>
                </div> --}}

                
                 <!-- Date Filters -->
                 <div class="d-flex justify-content-end align-items-center mb-3">
                    <div class="btn-group" role="group">
                        <a href="{{ route('tasks.index', ['date_filter' => 'yesterday']) }}" class="btn btn-outline-secondary {{ request()->date_filter == 'yesterday' ? 'active' : '' }}">Yesterday</a>
                        <a href="{{ route('tasks.index', ['date_filter' => 'today']) }}" class="btn btn-outline-secondary {{ request()->date_filter == 'today' ? 'active' : '' }}">Today</a>
                        <a href="{{ route('tasks.index', ['date_filter' => 'tomorrow']) }}" class="btn btn-outline-secondary {{ request()->date_filter == 'tomorrow' ? 'active' : '' }}">Tomorrow</a>
                        <a href="{{ route('tasks.index', ['date_filter' => 'this_week']) }}" class="btn btn-outline-secondary {{ request()->date_filter == 'this_week' ? 'active' : '' }}">This Week</a>
                        <a href="{{ route('tasks.index', ['date_filter' => 'this_month']) }}" class="btn btn-outline-secondary {{ request()->date_filter == 'this_month' ? 'active' : '' }}">This Month</a>
                        <a href="{{ route('tasks.index', ['date_filter' => 'custom']) }}" class="btn btn-outline-secondary {{ request()->date_filter == 'custom' ? 'active' : '' }}">Custom</a>
                    </div>
                </div>

                <!-- Tasks Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Task ID</th>
                                <th>Subject</th>
                                <th>Task Type</th>
                                <th>Task Status</th>
                                <th>Schedule From</th>
                                <th>Schedule To</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tasks as $task)
                            <tr>
                                <td>{{ $task->id }}</td>
                                <td>{{ $task->subject }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $task->task_type)) }}</td>
                                <td>{{ ucfirst($task->task_status) }}</td>
                                <td>{{ $task->schedule_from }}</td>
                                <td>{{ $task->schedule_to }}</td>
                                <td>
                                    {{-- <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-warning btn-sm">Edit</a> --}}
                                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"  onclick="return confirm('Are you sure you want to delete this task?');">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- Pagination Links -->
                    {{ $tasks->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
