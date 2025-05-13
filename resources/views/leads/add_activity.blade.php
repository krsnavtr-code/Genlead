@extends('main')

@section('title', 'Add Activity')

@section('content')

<style>
      label {
        font-size: 17px;
        color: #495057;
        font-family: sans-serif;
        font-weight: 100 !important;
        
    }
</style>
<div class="content-wrapper">

    @include('navbar')

    <!-- Content Header (Page header) -->
    
    
   <!-- Content Header (Page header) -->
   <div class="content-header sty-one d-flex justify-content-between align-items-center">
    <div>
        <h1>Add Activity</h1>
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
                <form action="{{ route('activities.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" id="title" name="title" placeholder="Enter Activity Title" required>
                    </div>

                    <div class="form-group">
                        <label for="type">Type</label>
                        <select class="form-control" id="type" name="type" required>
                            <option value="">Select Type</option>
                            <option value="call">Call</option>
                            <option value="meeting">Meeting</option>
                            <option value="lunch">Lunch</option>
                            <!-- Add more types as needed -->
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="4" placeholder="Enter Description"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="schedule_from">Schedule From</label>
                        <input type="datetime-local" class="form-control" id="schedule_from" name="schedule_from" required>
                    </div>

                    <div class="form-group">
                        <label for="schedule_to">Schedule To</label>
                        <input type="datetime-local" class="form-control" id="schedule_to" name="schedule_to" required>
                    </div>

                    {{-- <div class="form-group">
                        <label for="lead_id">Lead</label>
                        <select class="form-control" id="lead_id" name="lead_id" required>
                            @foreach($leads as $lead)
                                <option value="{{ $lead->id }}">{{ $lead->first_name }} {{ $lead->last_name }}</option>
                            @endforeach
                        </select>
                    </div> --}}

                    <button type="submit" class="btn btn-primary">Add Activity</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
