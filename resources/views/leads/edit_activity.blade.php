@extends('main')

@section('title', 'Edit Activity')

@section('content')
<div class="content-wrapper">
    <div class="content-header sty-one">
        <h1>Edit Activity</h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/activities') }}">Activities</a></li>
            {{-- <li class="fa fa-angle-right">Edit Activity</li> --}}
        </ol>
    </div>

    <div class="content">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('activities.update', $activity->id) }}" method="POST">
                    @csrf
                    @method('POST')
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" id="title" name="title" value="{{ $activity->title }}" required>
                    </div>

                    <div class="form-group">
                        <label for="type">Type</label>
                        <select class="form-control" id="type" name="type" required>
                            <option value="call" {{ $activity->type == 'call' ? 'selected' : '' }}>Call</option>
                            <option value="meeting" {{ $activity->type == 'meeting' ? 'selected' : '' }}>Meeting</option>
                            <option value="lunch" {{ $activity->type == 'lunch' ? 'selected' : '' }}>Lunch</option>
                        </select>
                    </div>


                    <div class="form-group">
                        <label for="schedule_from">Schedule From</label>
                        <input type="datetime-local" class="form-control" id="schedule_from" name="schedule_from" value="{{ \Carbon\Carbon::parse($activity->schedule_from)->format('Y-m-d\TH:i') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="schedule_to">Schedule To</label>
                        <input type="datetime-local" class="form-control" id="schedule_to" name="schedule_to" value="{{ \Carbon\Carbon::parse($activity->schedule_to)->format('Y-m-d\TH:i') }}" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Activity</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
