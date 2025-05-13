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
    @include('navbar')
    
    
   <!-- Content Header (Page header) -->
   <div class="content-header sty-one d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center justify-content-between">
        <h1>Manage Activities</h1>
        <a href="{{ url('/admin/activities/create') }}" 
        class="btn btn-danger btn-sm float-end" 
        style=" color: white; padding: 5px 20px; font-size: 16px; font-weight: bold; border-radius: 4px; text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">
        <span style="font-size: 20px; font-weight: bold; margin-right: 8px;">&#43;</span>
        Create Activity
     </a>
    </div>
    <!-- <div>
        <a href="{{ url('/admin/activities/create') }}" 
        class="btn btn-danger btn-sm" 
        style=" color: white; padding: 5px 20px; font-size: 16px; font-weight: bold; border-radius: 4px; text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">
     
        <span style="font-size: 20px; font-weight: bold; margin-right: 8px;">&#43;</span>
     
        Create Activity
     </a>
    </div> -->
</div>

    <div class="content">
        <div class="card">
            <div class="card-body">
                <div class=" mb-3">
                    <!-- Search and Filter Form -->
                    <form method="GET" action="{{ route('activities.index') }}" class="d-flex flex-column align-items-center">
                        <!-- Search Field -->
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search Activities" value="{{ request()->search }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-secondary"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                        <!-- Type Filter -->
                        <select name="type" class="form-control my-2" style="width: 100%;" onchange="this.form.submit()">
                            <option value="all" {{ request()->type == 'all' ? 'selected' : '' }}>All</option>
                            <option value="call" {{ request()->type == 'call' ? 'selected' : '' }}>Call</option>
                            <option value="meeting" {{ request()->type == 'meeting' ? 'selected' : '' }}>Meeting</option>
                            <option value="lunch" {{ request()->type == 'lunch' ? 'selected' : '' }}>Lunch</option>
                        </select>
                    </form>

                    <!-- Pagination and Filters -->
                    <div class="d-flex align-items-center flex-wrap">
                        <select class="form-control">
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
                    <div class="btn-group d-flex flex-wrap" role="group" style="gap: 5px;">
                        <a href="{{ route('activities.index', ['date_filter' => 'yesterday']) }}" class="btn btn-outline-secondary rounded {{ request()->date_filter == 'yesterday' ? 'active' : '' }}">Yesterday</a>
                        <a href="{{ route('activities.index', ['date_filter' => 'today']) }}" class="btn btn-outline-secondary rounded {{ request()->date_filter == 'today' ? 'active' : '' }}">Today</a>
                        <a href="{{ route('activities.index', ['date_filter' => 'tomorrow']) }}" class="btn btn-outline-secondary rounded {{ request()->date_filter == 'tomorrow' ? 'active' : '' }}">Tomorrow</a>
                        <a href="{{ route('activities.index', ['date_filter' => 'this_week']) }}" class="btn btn-outline-secondary rounded {{ request()->date_filter == 'this_week' ? 'active' : '' }}">This Week</a>
                        <a href="{{ route('activities.index', ['date_filter' => 'this_month']) }}" class="btn btn-outline-secondary rounded {{ request()->date_filter == 'this_month' ? 'active' : '' }}">This Month</a>
                        <a href="{{ route('activities.index', ['date_filter' => 'custom']) }}" class="btn btn-outline-secondary rounded {{ request()->date_filter == 'custom' ? 'active' : '' }}">Custom</a>
                    </div>
                </div>


                <!-- Activities Table -->
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    @foreach($activities as $activity)
                        <div class="col">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body" >
                                    <h5 class="card-title">{{ $activity->title }}</h5>
                                    <h6 class="card-subtitle mb-2 text-muted">{{ ucfirst($activity->type) }}</h6>

                                    <p class="mb-1"><strong>Is Done:</strong> 
                                        <span class="{{ $activity->is_done ? 'text-success' : 'text-danger' }}">
                                            {{ $activity->is_done ? 'Yes' : 'No' }}
                                        </span>
                                    </p>

                                    <p class="mb-1"><strong>Schedule From:</strong> {{ $activity->schedule_from }}</p>
                                    <p class="mb-3"><strong>Schedule To:</strong> {{ $activity->schedule_to }}</p>

                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('activities.edit', $activity->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('activities.destroy', $activity->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to                delete this activity?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
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
