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

    .task-card {
        transition: transform 0.2s ease-in-out, box-shadow 0.3s ease;
        border-left: 4px solid #0d6efd;
    }

    .task-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.1);
    }

    .task-card .card-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #0d6efd;
    }

    .task-card .info-label {
        font-weight: 500;
        color: #6c757d;
    }

    .task-card .info-value {
        font-weight: 600;
        color: #212529;
    }

    .task-card .card-body {
        padding: 1.2rem 1rem;
    }


    @media (max-width: 768px) {
        .btn.w-100.w-md-auto {
            margin-top: 10px;
        }
    }

</style>
<div class="content-wrapper">
<!-- Horizontal Navbar -->
    <div class="horizontal-navbar d-flex flex-wrap justify-content-around py-2 border-bottom mb-3">
        <a href="{{ url('/i-admin/show-leads') }}" class="btn m-1">Manage Leads</a>
        <a href="{{ url('/admin/activities/create') }}" class="btn m-1">Add Activities</a>
        <a href="{{ url('/admin/activities') }}" class="btn m-1">Manage Activities</a>
        <a href="{{ url('/admin/tasks/create') }}" class="btn m-1">Create/Add Tasks</a>
        <a href="{{ url('/admin/tasks') }}" class="btn m-1">Manage Tasks</a>
        <a href="{{ url('/i-admin/pending') }}" class="btn m-1">Pending Payment</a>
    </div>

   <!-- Content Header (Page header) -->
   <div class="content-header sty-one d-flex justify-content-between align-items-center">
    <div>
        <h1>Manage Tasks</h1>
    </div>
    <!-- <div>
        <a href="{{ url('/i-admin/leads/add-lead') }}" 
        class="btn btn-danger btn-sm" 
        style=" color: white; padding: 5px 20px; font-size: 16px; font-weight: bold; border-radius: 4px; text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">
     
        <span style="font-size: 20px; font-weight: bold; margin-right: 8px;">&#43;</span>
     
       
        Add Lead
     </a>
    </div> -->
</div>

    <div class="content">
        <div class="card">
            <div class="card-body">
                <!-- Task Filter/Search UI -->
                <div class="mb-4">
                    <div class="row g-3 align-items-center">
                        <!-- Form: Search + Filters -->
                        <div class="col-lg-10 col-md-9 col-sm-12">
                            <form method="GET" action="{{ route('tasks.index') }}" class="row g-2">
                                <!-- Search -->
                                <div class="col-md-4 mb-2">
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control" placeholder="Search Tasks" value="{{ request()->search }}">
                                        <button class="btn btn-outline-secondary" type="submit">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                
                                <!-- Type Filter -->
                                <div class="col-md-3 mb-2">
                                    <select name="task_type" class="form-control" onchange="this.form.submit()">
                                        <option value="all" {{ request()->task_type == 'all' ? 'selected' : '' }}>All Types</option>
                                        <option value="follow_up" {{ request()->task_type == 'follow_up' ? 'selected' : '' }}>Follow-Up</option>
                                        <option value="payment_failure" {{ request()->task_type == 'payment_failure' ? 'selected' : '' }}>Payment Failure</option>
                                    </select>
                                </div>
                
                                <!-- Status Filter -->
                                <div class="col-md-3 mb-2">
                                    <select name="task_status" class="form-control" onchange="this.form.submit()">
                                        <option value="all" {{ request()->task_status == 'all' ? 'selected' : '' }}>All Status</option>
                                        <option value="overdue" {{ request()->task_status == 'overdue' ? 'selected' : '' }}>Overdue</option>
                                        <option value="pending" {{ request()->task_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="completed" {{ request()->task_status == 'completed' ? 'selected' : '' }}>Completed</option>
                                    </select>
                                </div>

                                <!-- Owner Filter -->
                                <div class="col-md-2 mb-2">
                                    <input type="text" name="task_owner" class="form-control" placeholder="Task Owner" value="{{ request()->task_owner }}">
                                </div>
                            </form>
                        </div>

                        <!-- Create Button -->
                        <div class="col-lg-2 col-md-3 col-sm-12 text-md-end text-start">
                            <a href="{{ route('tasks.create') }}" class="btn btn-primary w-100 w-md-auto">Create Task</a>
                        </div>
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
                <div class="mb-3">
                    <div class="d-flex flex-column flex-md-row justify-content-md-end align-items-start gap-2">
                        <div class="btn-group flex-wrap" role="group">
                            <a href="{{ route('tasks.index', ['date_filter' => 'yesterday']) }}" class="btn btn-outline-primary rounded-pill px-4 {{ request()->date_filter == 'yesterday' ? 'active' : '' }}">Yesterday</a>
                            <a href="{{ route('tasks.index', ['date_filter' => 'today']) }}" class="btn btn-outline-primary rounded-pill px-4 {{ request()->date_filter == 'today' ? 'active' : '' }}">Today</a>
                            <a href="{{ route('tasks.index', ['date_filter' => 'tomorrow']) }}" class="btn btn-outline-primary rounded-pill px-4 {{ request()->date_filter == 'tomorrow' ? 'active' : '' }}">Tomorrow</a>
                            <a href="{{ route('tasks.index', ['date_filter' => 'this_week']) }}" class="btn btn-outline-primary rounded-pill px-4 {{ request()->date_filter == 'this_week' ? 'active' : '' }}">This Week</a>
                            <a href="{{ route('tasks.index', ['date_filter' => 'this_month']) }}" class="btn btn-outline-primary rounded-pill px-4 {{ request()->date_filter == 'this_month' ? 'active' : '' }}">This Month</a>
                            <a href="{{ route('tasks.index', ['date_filter' => 'custom']) }}" class="btn btn-outline-primary rounded-pill px-4 {{ request()->date_filter == 'custom' ? 'active' : '' }}">Custom</a>
                        </div>
                    </div>
                </div>


                <!-- Tasks Table -->
                <div class="row g-4">
                    @forelse($tasks as $task)
                    <div class="col-md-6 col-lg-4">
                        <div class="card shadow-sm h-100 task-card bg-white rounded-3">
                            <div class="card-body">
                                <h5 class="card-title">#{{ $task->id }} - {{ $task->subject }}</h5>
                                <br>
                                <div class="mb-2">
                                    <span class="info-label">Type:</span>
                                    <span class="info-value">{{ ucfirst(str_replace('_', ' ', $task->task_type)) }}</span>
                                </div>
                                <div class="mb-2">
                                    <span class="info-label">Status:</span>
                                    <span class="info-value">{{ ucfirst($task->task_status) }}</span>
                                </div>
                                <div class="mb-2">
                                    <span class="info-label">From:</span>
                                    <span class="info-value">{{ $task->schedule_from }}</span>
                                </div>
                                <div class="mb-3">
                                    <span class="info-label">To:</span>
                                    <span class="info-value">{{ $task->schedule_to }}</span>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this task?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                    {{-- <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-sm btn-outline-warning ms-2">Edit</a> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center">No tasks found.</div>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $tasks->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
