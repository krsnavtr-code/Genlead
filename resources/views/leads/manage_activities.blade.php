@extends('main')

@section('title', 'Manage Activities')

@section('content')

<style>
      th,td {
        font-size: 16px;
        color: #495057;
        font-family: sans-serif;
        font-weight: 100 !important;
        
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
        <h1>Manage Activities</h1>
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
                    <form method="GET" action="{{ route('activities.index') }}" class="d-flex">
                        <!-- Search Field -->
                        <input type="text" name="search" class="form-control" placeholder="Search Activities" style="width: 200px; display: inline-block;" value="{{ request()->search }}">
                        <button type="submit" class="btn btn-secondary" style="margin-left: -30px;"><i class="fa fa-search"></i></button>
                    
                        <!-- Type Filter -->
                        <select name="type" class="form-control ml-3" style="width: 150px;" onchange="this.form.submit()">
                            <option value="all" {{ request()->type == 'all' ? 'selected' : '' }}>All</option>
                            <option value="call" {{ request()->type == 'call' ? 'selected' : '' }}>Call</option>
                            <option value="meeting" {{ request()->type == 'meeting' ? 'selected' : '' }}>Meeting</option>
                            <option value="lunch" {{ request()->type == 'lunch' ? 'selected' : '' }}>Lunch</option>
                        </select>
                    </form>

                    <!-- Pagination and Filters -->
                    <div class="d-flex">
                        <select class="form-control mr-2" style="width: 70px;">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        {{-- <button class="btn btn-secondary">Filter</button>
                        <button class="btn btn-secondary ml-2">+</button> --}}
                    </div>
                </div>

                 <!-- Date Filters -->
                 <div class="d-flex justify-content-end align-items-center mb-3">
                    <div class="btn-group" role="group">
                        <a href="{{ route('activities.index', ['date_filter' => 'yesterday']) }}" class="btn btn-outline-secondary {{ request()->date_filter == 'yesterday' ? 'active' : '' }}">Yesterday</a>
                        <a href="{{ route('activities.index', ['date_filter' => 'today']) }}" class="btn btn-outline-secondary {{ request()->date_filter == 'today' ? 'active' : '' }}">Today</a>
                        <a href="{{ route('activities.index', ['date_filter' => 'tomorrow']) }}" class="btn btn-outline-secondary {{ request()->date_filter == 'tomorrow' ? 'active' : '' }}">Tomorrow</a>
                        <a href="{{ route('activities.index', ['date_filter' => 'this_week']) }}" class="btn btn-outline-secondary {{ request()->date_filter == 'this_week' ? 'active' : '' }}">This Week</a>
                        <a href="{{ route('activities.index', ['date_filter' => 'this_month']) }}" class="btn btn-outline-secondary {{ request()->date_filter == 'this_month' ? 'active' : '' }}">This Month</a>
                        <a href="{{ route('activities.index', ['date_filter' => 'custom']) }}" class="btn btn-outline-secondary {{ request()->date_filter == 'custom' ? 'active' : '' }}">Custom</a>
                    </div>
                </div>


                <!-- Activities Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Type</th>
                                {{-- <th>Lead</th> --}}
                                <th>Is Done</th>
                                {{-- <th>Created By</th> --}}
                                <th>Schedule From</th>
                                <th>Schedule To</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($activities as $activity)
                            <tr>
                                <td>{{ $activity->title }}</td>
                                <td>{{ $activity->type }}</td>
                                {{-- <td>{{ $activity->lead ? $activity->lead->first_name . ' ' . $activity->lead->last_name : '-' }}</td> --}}
                                <td>{{ $activity->is_done ? 'Yes' : 'No' }}</td>
                                {{-- <td>{{ $activity->created_by }}</td> --}}
                                <td>{{ $activity->schedule_from }}</td>
                                <td>{{ $activity->schedule_to }}</td>
                                <td>
                                    <a href="{{ route('activities.edit', $activity->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('activities.destroy', $activity->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this activity?');">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- <!-- Pagination and Items per Page -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        Showing {{ $activities->firstItem() }} to {{ $activities->lastItem() }} of {{ $activities->total() }} entries
                    </div>
                    <div>
                        {{ $activities->links() }}
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
</div>
@endsection
